<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupAttendancePhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-attendance-photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up attendance photos older than today to save disk space';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting attendance photos cleanup...');
        
        $yesterday = \Carbon\Carbon::today(); // anything before today

        $attendances = \App\Models\Attendance::whereNotNull('photo_path')
            ->whereDate('date', '<', $yesterday)
            ->get();

        $count = 0;
        foreach ($attendances as $attendance) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($attendance->photo_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($attendance->photo_path);
            }
            $attendance->photo_path = null;
            $attendance->save();
            $count++;
        }

        $this->info("Cleanup completed. Deleted $count old photos.");
    }
}
