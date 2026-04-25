<?php

use Illuminate\Support\Facades\Schedule;

foreach (config('portal.seo.dispatch_times', ['02:00']) as $time) {
    Schedule::command('seo:dispatch-daily')
        ->dailyAt($time)
        ->withoutOverlapping();
}
