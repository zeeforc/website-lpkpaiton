<?php

namespace App\Console\Commands;

use App\Services\AttendanceExportStorage;
use Illuminate\Console\Command;

class CleanupAttendanceExports extends Command
{
    protected $signature = 'app:cleanup-attendance-exports {--all : Remove all generated documentation exports, regardless of age}';

    protected $description = 'Delete temporary attendance ZIP exports older than six hours';

    public function handle(AttendanceExportStorage $exportStorage): int
    {
        $deleted = $this->option('all')
            ? $exportStorage->clearPreviousExports()
            : $exportStorage->deleteExpiredExports(now()->subHours(6)->getTimestamp());

        $this->info($this->option('all')
            ? "Deleted {$deleted} generated attendance documentation export(s)."
            : "Deleted {$deleted} attendance documentation export(s) older than six hours.");

        return self::SUCCESS;
    }
}
