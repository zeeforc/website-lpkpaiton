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
        
        $thresholdDate = \Carbon\Carbon::now()->subMonth(); // hapus yang lebih lama dari 1 bulan

        $attendances = \App\Models\Attendance::where(function($query) {
                $query->whereNotNull('photo_path')
                      ->orWhereNotNull('checkout_photos');
            })
            ->whereDate('date', '<', $thresholdDate)
            ->get();

        $count = 0;
        foreach ($attendances as $attendance) {
            if ($attendance->photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($attendance->photo_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($attendance->photo_path);
            }
            $attendance->photo_path = null;

            if ($attendance->checkout_photos) {
                $photos = json_decode($attendance->checkout_photos, true) ?? [];
                foreach ($photos as $photo) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($photo);
                    }
                }
            }
            $attendance->checkout_photos = null;

            $attendance->save();
            $count++;
        }

        $this->info("Cleanup completed. Deleted $count old photos.");
    }
}
