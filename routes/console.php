<?php

use App\Jobs\EmptyRecycleBin;
use App\Jobs\TaskNearing;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new TaskNearing)->dailyAt('7:00');
Schedule::job(new EmptyRecycleBin)->dailyAt('3:00');
