<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:cleanup-attendance-photos')
    ->monthlyOn(5, '01:00')
    ->timezone('Asia/Jakarta');

Schedule::command('app:cleanup-attendance-exports')->hourly();
