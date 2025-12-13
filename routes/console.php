<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('presensi:replace-files --months=3 --delete-files')
    ->monthlyOn(1, '00:00')
    ->withoutOverlapping();

Schedule::command('presensi:cleanup-old-selfies --months=3')
    ->monthlyOn(1, '00:30')
    ->withoutOverlapping();
