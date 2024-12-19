<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tweet;
use App\Jobs\ProcessTweetsJob;

class ProcessTweetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Example: php artisan tweets:process
     */
    protected $signature = 'tweets:process';

    /**
     * The console command description.
     */
    protected $description = 'Dispatch jobs to process unprocessed tweets and extract instruments';

    public function handle()
    {
        $this->info('Fetching unprocessed tweets...');

        // Fetch all tweets that haven't been processed yet
        $unprocessedTweets = Tweet::where('processed', false)->get();

        if ($unprocessedTweets->isEmpty()) {
            $this->info('No unprocessed tweets found.');
            return 0;
        }

        // Dispatch a ProcessTweetsJob for each unprocessed tweet
        foreach ($unprocessedTweets as $tweet) {
            ProcessTweetsJob::dispatch($tweet);
            $this->info("Dispatched ProcessTweetsJob for tweet ID: {$tweet->id}");
        }

        $this->info('All unprocessed tweets have been dispatched for processing.');
        return 0;
    }
}
