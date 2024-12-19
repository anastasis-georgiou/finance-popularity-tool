<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateStatsJob;

class UpdateStatsCommand extends Command
{
    protected $signature = 'stats:update';
    protected $description = 'Update the stats table with aggregated daily, weekly, and monthly mentions.';

    public function handle()
    {
        UpdateStatsJob::dispatch();
        $this->info('Stats update job dispatched.');
    }
}
