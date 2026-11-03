<?php

namespace App\Console\Commands;

use App\Services\AttendancePhotoLock;
use App\Services\AttendancePhotoMigration;
use App\Services\ImageOptimizer;
use Illuminate\Console\Command;

class OptimizeAttendancePhotos extends Command
{
    protected $signature = 'app:optimize-attendance-photos {--limit=10 : Maximum number of photos to process per run}';

    protected $description = 'Convert legacy attendance photos to optimized WebP in small batches';

    public function handle(AttendancePhotoMigration $migration, ImageOptimizer $optimizer, AttendancePhotoLock $lock): int
    {
        $limit = filter_var($this->option('limit'), FILTER_VALIDATE_INT);
        if ($limit === false || $limit < 1 || $limit > 50) {
            $this->error('The --limit option must be between 1 and 50.');

            return self::FAILURE;
        }

        $result = $migration->migrateBatch($optimizer, $lock, $limit);
        if ($result['locked']) {
            $this->info('Skipped photo optimization because an attendance export is in progress.');

            return self::SUCCESS;
        }

        $this->info("Optimized {$result['converted']} photo(s); skipped {$result['skipped']}; {$result['failed']} failed.");

        return $result['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}
