<?php

use App\Console\Commands\FetchAllCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(FetchAllCommand::class)->dailyAt('04:00')->timezone('Europe/Berlin');
