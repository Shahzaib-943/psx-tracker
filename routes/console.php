<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Schedule as ScheduleFacade;

ScheduleFacade::command('stocks:fetch-closing-prices')
    // ->hourly()
    ->everyMinute()
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();
