<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncQuotaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pkl:sync-quota';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi tanggal mulai, selesai, dan jalur khusus untuk data lama agar kuota otomatis berjalan.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi data kuota...');

        $applications = \App\Models\Application::where('status', 'accepted')
            ->whereNull('start_date')
            ->get();

        if ($applications->isEmpty()) {
            $this->info('Tidak ada data aplikasi lama yang perlu disinkronkan.');
            return;
        }

        $count = 0;
        $khususCount = 0;

        foreach ($applications as $index => $app) {
            // Set start date to today, end date to today + duration
            $startDate = now();
            $endDate = now()->addMonths($app->lama_durasi_bulan ?? 3);
            
            // Mark up to 4 excess students as 'jalur_khusus' if we have more than 35
            // But since the user said there are 39 total and wants quota to be exactly 35 for the rest:
            $isKhusus = false;
            if ($applications->count() == 39 && $index >= 35) {
                $isKhusus = true;
                $khususCount++;
            }

            $app->update([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_jalur_khusus' => $isKhusus,
            ]);

            $count++;
        }

        $this->info("Berhasil mensinkronisasi {$count} data. {$khususCount} diset sebagai jalur khusus.");
    }
}
