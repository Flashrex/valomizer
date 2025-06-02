<?php

use App\Console\Commands\FetchAllCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(FetchAllCommand::class)->everyMinute()->withoutOverlapping();