<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('presensi:replace-files --months=3')
    ->monthlyOn(1, '00:00')
    ->withoutOverlapping();

Schedule::command('presensi:cleanup-selfies --months=3 --limit=100')
    ->dailyAt('00:30')
    ->withoutOverlapping();

Schedule::command('presensi:cleanup-selfies --months=3 --limit=100')
    ->dailyAt('00:35')
    ->withoutOverlapping();

Schedule::command('presensi:cleanup-selfies --months=3 --limit=100')
    ->dailyAt('00:40')
    ->withoutOverlapping();

Schedule::command('presensi:cleanup-selfies --months=3 --limit=100')
    ->dailyAt('00:45')
    ->withoutOverlapping();

Schedule::command('presensi:cleanup-selfies --months=3 --limit=100')
    ->dailyAt('00:50')
    ->withoutOverlapping();

Schedule::command('presensi:cleanup-selfies --months=3 --limit=100')
    ->dailyAt('00:55')
    ->withoutOverlapping();

Schedule::command('presensi:cleanup-selfies --months=3 --limit=100')
    ->dailyAt('01:00')
    ->withoutOverlapping();
