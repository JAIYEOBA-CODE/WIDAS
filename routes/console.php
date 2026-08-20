<?php

use App\Jobs\CleanupOldLogs;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new CleanupOldLogs())->daily();

Schedule::command('queue:prune-failed')->hourly();
