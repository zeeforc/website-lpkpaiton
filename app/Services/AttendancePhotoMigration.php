<?php

namespace App\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class AttendancePhotoMigration
{
    private const MAX_OUTPUT_BYTES = 100 * 1024;

    public function migratePaths(int $attendanceId, array $paths, ImageOptimizer $optimizer, AttendancePhotoLock $lock): array
    {
        $lockHandle = $lock->acquire(false);
        if ($lockHandle === null) {
            return ['converted' => 0, 'skipped' => 0, 'locked' => true];
        }

        $converted = 0;
        $skipped = 0;
        $disk = Storage::disk('public');

        try {
            foreach (array_unique($paths) as $path) {
                if (! is_string($path) || $path === '') {
                    $skipped++;

                    continue;
                }

                try {
                    $result = $this->migratePath($path, $disk, $optimizer, $attendanceId);
                    if ($result === true) {
                        $converted++;
                    } else {
                        $skipped++;
                    }
                } catch (Throwable $exception) {
                    Log::error('Failed to optimize a queued attendance photo.', [
                        'path' => $path,
                        'exception' => $exception,
                    ]);

                    throw $exception;
                }
            }
        } finally {
            $lock->release($lockHandle);
        }

        return ['converted' => $converted, 'skipped' => $skipped, 'locked' => false];
    }

    public function migrateBatch(ImageOptimizer $optimizer, AttendancePhotoLock $lock, int $limit): array
    {
        $lockHandle = $lock->acquire(false);
        if ($lockHandle === null) {
            return ['converted' => 0, 'failed' => 0, 'stop' => false, 'locked' => true];
        }

        try {
            return $this->migrateLockedBatch($optimizer, $limit);
        } finally {
            $lock->release($lockHandle);
        }
    }

    private function migrateLockedBatch(ImageOptimizer $optimizer, int $limit): array
    {
        $disk = Storage::disk('public');
        $attempted = [];
        $converted = 0;
        $skipped = 0;
        $failed = 0;
        $stop = false;

        DB::table('attendances')
            ->select('id', 'photo_path', 'checkout_photos')
            ->orderBy('id')
            ->chunkById(100, function ($attendances) use (
                $disk,
                $optimizer,
                $limit,
                &$attempted,
                &$converted,
                &$skipped,
                &$failed,
                &$stop,
            ): bool {
                foreach ($attendances as $attendance) {
                    $paths = [];
                    if (is_string($attendance->photo_path) && $attendance->photo_path !== '') {
                        $paths[] = $attendance->photo_path;
                    }

                    if ($attendance->checkout_photos) {
                        $checkoutPhotos = json_decode($attendance->checkout_photos, true);
                        if (! is_array($checkoutPhotos)) {
                            Log::warning('Skipped attendance photo migration because checkout photo paths are invalid.', [
                                'attendance_id' => $attendance->id,
                            ]);
                            $failed++;

                            continue;
                        }

                        foreach ($checkoutPhotos as $path) {
                            if (is_string($path) && $path !== '') {
                                $paths[] = $path;
                            }
                        }
                    }

                    foreach (array_unique($paths) as $path) {
                        if (isset($attempted[$path])) {
                            continue;
                        }

                        $attempted[$path] = true;

                        try {
                            $result = $this->migratePath($path, $disk, $optimizer);
                            if ($result === true) {
                                $converted++;
                            } elseif ($result === null) {
                                $skipped++;
                            }
                        } catch (Throwable $exception) {
                            $failed++;
                            Log::error('Failed to optimize an attendance photo.', [
                                'path' => $path,
                                'exception' => $exception,
                            ]);
                        }

                        if ($converted >= $limit) {
                            $stop = true;

                            return false;
                        }
                    }
                }

                return true;
            });

        return [
            'converted' => $converted,
            'skipped' => $skipped,
            'failed' => $failed,
            'stop' => $stop,
            'locked' => false,
        ];
    }

    private function migratePath(string $path, Filesystem $disk, ImageOptimizer $optimizer, ?int $attendanceId = null): ?bool
    {
        if (! Str::startsWith($path, 'attendances/') || in_array('..', explode('/', $path), true)) {
            Log::warning('Skipped attendance photo migration for a path outside the attendance directory.', [
                'path' => $path,
            ]);

            return null;
        }

        if (! $disk->exists($path)) {
            Log::warning('Skipped attendance photo migration because the source file is missing.', [
                'path' => $path,
            ]);

            return null;
        }

        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'webp' && $disk->size($path) <= self::MAX_OUTPUT_BYTES) {
            return false;
        }

        $contents = $disk->get($path);
        $webp = $optimizer->toWebp($contents);
        if (strlen($webp) > self::MAX_OUTPUT_BYTES) {
            throw new RuntimeException('Hasil optimasi melebihi batas 100 KB.');
        }

        $newPath = 'attendances/optimized/'.Str::uuid().'.webp';
        if (! $disk->put($newPath, $webp)) {
            throw new RuntimeException("Gagal menyimpan foto WebP baru: {$newPath}");
        }

        try {
            $hasInvalidCheckoutReferences = DB::transaction(function () use ($path, $newPath, $attendanceId): bool {
                if ($attendanceId !== null) {
                    $attendance = DB::table('attendances')
                        ->where('id', $attendanceId)
                        ->lockForUpdate()
                        ->first(['photo_path', 'checkout_photos']);

                    if ($attendance === null) {
                        return false;
                    }

                    if ($attendance->photo_path === $path) {
                        DB::table('attendances')
                            ->where('id', $attendanceId)
                            ->update(['photo_path' => $newPath]);
                    }

                    if ($attendance->checkout_photos === null) {
                        return false;
                    }

                    $photos = json_decode($attendance->checkout_photos, true);
                    if (! is_array($photos)) {
                        return str_contains($attendance->checkout_photos, $path);
                    }

                    $updated = false;
                    foreach ($photos as &$photo) {
                        if ($photo === $path) {
                            $photo = $newPath;
                            $updated = true;
                        }
                    }
                    unset($photo);

                    if ($updated) {
                        DB::table('attendances')
                            ->where('id', $attendanceId)
                            ->update(['checkout_photos' => json_encode($photos, JSON_THROW_ON_ERROR)]);
                    }

                    return false;
                }

                DB::table('attendances')
                    ->where('photo_path', $path)
                    ->update(['photo_path' => $newPath]);

                $invalidReferencesFound = false;

                DB::table('attendances')
                    ->select('id', 'checkout_photos')
                    ->whereNotNull('checkout_photos')
                    ->orderBy('id')
                    ->chunkById(100, function ($attendances) use ($path, $newPath, &$invalidReferencesFound): void {
                        foreach ($attendances as $attendance) {
                            $photos = json_decode($attendance->checkout_photos, true);
                            if (! is_array($photos)) {
                                if (str_contains($attendance->checkout_photos, $path)) {
                                    $invalidReferencesFound = true;
                                }

                                continue;
                            }

                            $updated = false;
                            foreach ($photos as &$photo) {
                                if ($photo === $path) {
                                    $photo = $newPath;
                                    $updated = true;
                                }
                            }
                            unset($photo);

                            if ($updated) {
                                DB::table('attendances')
                                    ->where('id', $attendance->id)
                                    ->update(['checkout_photos' => json_encode($photos, JSON_THROW_ON_ERROR)]);
                            }
                        }
                    });

                return $invalidReferencesFound;
            });
        } catch (Throwable $exception) {
            $disk->delete($newPath);
            throw $exception;
        }

        if ($hasInvalidCheckoutReferences) {
            Log::warning('Kept the original attendance photo because invalid checkout photo references exist.', [
                'path' => $path,
                'optimized_path' => $newPath,
            ]);

            return true;
        }

        if (! $disk->delete($path)) {
            throw new RuntimeException("Referensi diperbarui, tetapi file lama gagal dihapus: {$path}");
        }

        return true;
    }
}
