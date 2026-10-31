<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CleanupAttendanceExports extends Command
{
    protected $signature = 'app:cleanup-attendance-exports';

    protected $description = 'Delete temporary attendance ZIP exports older than six hours';

    public function handle(): int
    {
        $disk = Storage::disk('local');
        $cutoff = now()->subHours(6)->getTimestamp();
        $deleted = 0;

        foreach ($disk->files('attendance-exports') as $path) {
            if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'zip') {
                continue;
            }

            try {
                if ($disk->lastModified($path) >= $cutoff) {
                    continue;
                }

                if (! $disk->delete($path)) {
                    Log::error('Failed to delete a stale attendance ZIP export.', ['path' => $path]);
                    continue;
                }

                $deleted++;
            } catch (Throwable $exception) {
                Log::error('Failed to clean up a stale attendance ZIP export.', [
                    'path' => $path,
                    'exception' => $exception,
                ]);
            }
        }

        $this->info("Deleted {$deleted} attendance ZIP export(s) older than six hours.");

        return self::SUCCESS;
    }
}
