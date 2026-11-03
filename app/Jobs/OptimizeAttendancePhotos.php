<?php

namespace App\Jobs;

use App\Services\AttendancePhotoLock;
use App\Services\AttendancePhotoMigration;
use App\Services\ImageOptimizer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OptimizeAttendancePhotos implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 10;

    public array $backoff = [30, 60, 120, 300];

    public int $timeout = 45;

    public function __construct(
        public int $attendanceId,
        public array $paths,
    ) {}

    public function handle(
        AttendancePhotoMigration $migration,
        ImageOptimizer $optimizer,
        AttendancePhotoLock $lock,
    ): void {
        $result = $migration->migratePaths($this->attendanceId, $this->paths, $optimizer, $lock);

        if ($result['locked']) {
            $this->release(60);
        }
    }
}
