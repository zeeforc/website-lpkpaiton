<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CleanupAttendancePhotos extends Command
{
    protected $signature = 'app:cleanup-attendance-photos';

    protected $description = 'Delete attendance photos from months before the current month';

    public function handle(): int
    {
        $cutoff = now()->startOfMonth()->toDateString();
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

        $this->info("Attendance photo cleanup completed for records before {$cutoff}. Updated {$updatedRecords} attendance records.");

        return self::SUCCESS;
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