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
        
        $yesterday = \Carbon\Carbon::today(); // hapus foto wajah (24 jam)
        $lastMonth = \Carbon\Carbon::now()->subMonth(); // hapus foto dokumentasi (1 bulan)

        // 1. Bersihkan foto wajah (Check-In) yang lebih lama dari 1 hari
        $attendancesForPhoto = \App\Models\Attendance::whereNotNull('photo_path')
            ->whereDate('date', '<', $yesterday)
            ->get();

        $countPhoto = 0;
        foreach ($attendancesForPhoto as $attendance) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($attendance->photo_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($attendance->photo_path);
            }
            $attendance->photo_path = null;
            $attendance->save();
            $countPhoto++;
        }

        // 2. Bersihkan foto dokumentasi (Check-Out) yang lebih lama dari 1 bulan
        $attendancesForCheckout = \App\Models\Attendance::whereNotNull('checkout_photos')
            ->whereDate('date', '<', $lastMonth)
            ->get();

        $countCheckout = 0;
        foreach ($attendancesForCheckout as $attendance) {
            $photos = json_decode($attendance->checkout_photos, true) ?? [];
            foreach ($photos as $photo) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($photo);
                }
            }
            $attendance->checkout_photos = null;
            $attendance->save();
            $countCheckout++;
        }

        $this->info("Cleanup completed. Deleted $countPhoto old check-in photos and $countCheckout old documentation photos.");
    }
}
