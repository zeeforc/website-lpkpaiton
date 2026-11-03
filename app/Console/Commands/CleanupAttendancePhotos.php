<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CleanupAttendancePhotos extends Command
{
    protected $signature = 'app:cleanup-attendance-photos {--before= : Delete photos for attendance dates before YYYY-MM-DD}';

    protected $description = 'Delete attendance photos older than the selected retention cutoff';

    public function handle(): int
    {
        $cutoff = $this->option('before') ?: now()->startOfMonth()->toDateString();
        try {
            $cutoffDate = Carbon::createFromFormat('!Y-m-d', $cutoff);
        } catch (Throwable) {
            $cutoffDate = false;
        }

        if ($cutoffDate === false || $cutoffDate->toDateString() !== $cutoff) {
            $this->error('The --before option must be a valid date in YYYY-MM-DD format.');

            return self::FAILURE;
        }

        $disk = Storage::disk('public');
        $updatedRecords = 0;

        DB::table('attendances')
            ->whereDate('date', '<', $cutoff)
            ->where(function ($query): void {
                $query->whereNotNull('photo_path')
                    ->orWhereNotNull('checkout_photos');
            })
            ->orderBy('id')
            ->chunkById(100, function ($attendances) use ($cutoff, $disk, &$updatedRecords): void {
                foreach ($attendances as $attendance) {
                    $updates = [];

                    if ($attendance->photo_path && $this->deleteManagedPhoto($attendance->photo_path, $cutoff, $disk)) {
                        $updates['photo_path'] = null;
                    }

                    if ($attendance->checkout_photos) {
                        $photos = json_decode($attendance->checkout_photos, true);

                        if (! is_array($photos)) {
                            Log::warning('Skipped attendance cleanup because checkout photo paths are invalid.', [
                                'attendance_id' => $attendance->id,
                            ]);
                        } else {
                            $remainingPhotos = [];

                            foreach ($photos as $photo) {
                                if (! is_string($photo) || ! $this->deleteManagedPhoto($photo, $cutoff, $disk)) {
                                    $remainingPhotos[] = $photo;
                                }
                            }

                            if ($remainingPhotos === []) {
                                $updates['checkout_photos'] = null;
                            } elseif (count($remainingPhotos) !== count($photos)) {
                                $updates['checkout_photos'] = json_encode($remainingPhotos, JSON_THROW_ON_ERROR);
                            }
                        }
                    }

                    if ($updates !== []) {
                        DB::table('attendances')
                            ->where('id', $attendance->id)
                            ->whereDate('date', '<', $cutoff)
                            ->update($updates);
                        $updatedRecords++;
                    }
                }
            });

        $orphanedFiles = $this->deleteExpiredOrphanedPhotos($cutoffDate->getTimestamp(), $disk);
        $this->info("Attendance photo cleanup completed before {$cutoff}. Updated {$updatedRecords} attendance records and deleted {$orphanedFiles} orphaned photo files.");

        return self::SUCCESS;
    }

    private function deleteExpiredOrphanedPhotos(int $cutoff, Filesystem $disk): int
    {
        $referencedPaths = [];
        $invalidReferencesFound = false;

        DB::table('attendances')
            ->select('id', 'photo_path', 'checkout_photos')
            ->orderBy('id')
            ->chunkById(500, function ($attendances) use (&$referencedPaths, &$invalidReferencesFound): void {
                foreach ($attendances as $attendance) {
                    if (is_string($attendance->photo_path) && $attendance->photo_path !== '') {
                        $referencedPaths[$attendance->photo_path] = true;
                    }

                    if (! $attendance->checkout_photos) {
                        continue;
                    }

                    $photos = json_decode($attendance->checkout_photos, true);
                    if (! is_array($photos)) {
                        $invalidReferencesFound = true;
                        Log::warning('Skipped orphan attendance photo cleanup because checkout photo paths are invalid.', [
                            'attendance_id' => $attendance->id,
                        ]);

                        continue;
                    }

                    foreach ($photos as $photo) {
                        if (! is_string($photo) || $photo === '') {
                            $invalidReferencesFound = true;

                            continue;
                        }

                        $referencedPaths[$photo] = true;
                    }
                }
            });

        if ($invalidReferencesFound) {
            $this->warn('Skipped orphan photo scan because some attendance photo references are invalid.');

            return 0;
        }

        $deleted = 0;
        foreach ($disk->allFiles('attendances') as $path) {
            if (isset($referencedPaths[$path])) {
                continue;
            }

            try {
                if ($disk->lastModified($path) >= $cutoff) {
                    continue;
                }

                if (! $disk->delete($path)) {
                    Log::error('Failed to delete an orphaned attendance photo.', ['path' => $path]);

                    continue;
                }

                $deleted++;
            } catch (Throwable $exception) {
                Log::error('Failed to clean up an orphaned attendance photo.', [
                    'path' => $path,
                    'exception' => $exception,
                ]);
            }
        }

        return $deleted;
    }

    private function deleteManagedPhoto(string $path, string $cutoff, Filesystem $disk): bool
    {
        if (! Str::startsWith($path, 'attendances/') || in_array('..', explode('/', $path), true)) {
            Log::warning('Skipped attendance cleanup for a path outside the attendance photo directory.', [
                'path' => $path,
            ]);

            return false;
        }

        $referencedByCurrentAttendance = DB::table('attendances')
            ->whereDate('date', '>=', $cutoff)
            ->where(function ($query) use ($path): void {
                $query->where('photo_path', $path)
                    ->orWhereJsonContains('checkout_photos', $path);
            })
            ->exists();

        if ($referencedByCurrentAttendance) {
            return true;
        }

        try {
            if ($disk->exists($path) && ! $disk->delete($path)) {
                Log::error('Failed to delete an expired attendance photo.', ['path' => $path]);

                return false;
            }
        } catch (Throwable $exception) {
            Log::error('Failed to delete an expired attendance photo.', [
                'path' => $path,
                'exception' => $exception,
            ]);

            return false;
        }

        return true;
    }
}
