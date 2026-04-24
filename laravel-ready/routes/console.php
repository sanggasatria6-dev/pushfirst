<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('seo:dispatch-daily')
    ->dailyAt('02:00')
    ->withoutOverlapping();
