<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:cleanup-attendance-exports')->hourly();

Schedule::command('app:optimize-attendance-photos --limit=10')
    ->everyFiveMinutes()
    ->withoutOverlapping();

Schedule::command('queue:work database --queue=attendance-photos --stop-when-empty --timeout=45 --max-time=50 --tries=10')
    ->everyMinute()
    ->withoutOverlapping(2);
