<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment('Keep going 🚀');
});

/*
|--------------------------------------------------------------------------
| Scheduler (Laravel 12)
|--------------------------------------------------------------------------
*/

app()->booted(function () {
    $schedule = app(Schedule::class);

    $schedule->command('barang:cek-kadaluarsa-terdekat --days=7')
        ->dailyAt('08:00')
        ->timezone('Asia/Jakarta');
});
