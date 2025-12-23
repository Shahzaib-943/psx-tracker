<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Schedule as ScheduleFacade;

ScheduleFacade::command('stocks:fetch-closing-prices')
    ->dailyAt('17:00')
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();

ScheduleFacade::command('stocks:fetch-closing-prices')
    ->dailyAt('17:10')
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();

ScheduleFacade::command('stocks:fetch-closing-prices')
    ->dailyAt('18:40')
    ->timezone('Asia/Karachi')
    ->withoutOverlapping();