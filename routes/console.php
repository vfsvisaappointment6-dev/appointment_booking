<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Mark past bookings as completed (run every hour)
Schedule::command('bookings:mark-completed')
    ->hourly()
    ->withoutOverlapping();
