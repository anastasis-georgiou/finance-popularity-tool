<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command("crawl:tweets")->everyMinute();
Schedule::command('tweets:process')->everyMinute();
Schedule::command("stats:update")->everyFiveMinutes();
