<?php

namespace App\Console\Commands;

use App\Services\AttendanceExportStorage;
use Illuminate\Console\Command;

class CleanupAttendanceExports extends Command
{
    protected $signature = 'app:cleanup-attendance-exports';

    protected $description = 'Delete temporary attendance ZIP exports older than six hours';

    public function handle(AttendanceExportStorage $exportStorage): int
    {
        $deleted = $exportStorage->deleteExpiredExports(now()->subHours(6)->getTimestamp());

        $this->info("Deleted {$deleted} attendance documentation export(s) older than six hours.");

        return self::SUCCESS;
    }
}
