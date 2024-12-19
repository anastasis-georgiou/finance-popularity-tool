<?php

namespace App\Jobs;

use App\Models\Handle;
use App\Models\Tweet;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Import this
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawlTweetsJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $handle;

    public function __construct(Handle $handle)
    {
        $this->handle = $handle;
    }

    public function handle(): void
    {
        try {
            Log::info("Crawling tweets for handle: {$this->handle->handle}");

            // Simulate fetching tweets
            $tweets = $this->fetchTweets();

            foreach ($tweets as $tweet) {
                // Save tweets in the database
                Tweet::updateOrCreate(
                    ['tweet_id' => $tweet['id']],
                    [
                        'handle_id' => $this->handle->id,
                        'content' => $tweet['content'],
                        'processed' => false,
                        'created_at' => $tweet['created_at'],
                    ]
                );
            }

            // Update last_crawled_at
            $this->handle->update(['last_crawled_at' => now()]);

        } catch (\Exception $e) {
            Log::error("Error crawling tweets for handle: {$this->handle->handle}. Error: " . $e->getMessage());
        }
    }

    private function fetchTweets(): array
    {
        // Simulating fetching tweets; replace with scraping logic if necessary
        return [
            [
                'id' => uniqid(),
                'content' => 'The $BTC is breaking new highs!',
                'created_at' => now(),
            ],
            [
                'id' => uniqid(),
                'content' => '$ETH and $XAUUSD are gaining momentum.',
                'created_at' => now(),
            ],
        ];
    }

    public function getJobId()
    {
        // TODO: Implement getJobId() method.
    }

    public function getRawBody()
    {
        // TODO: Implement getRawBody() method.
    }
}
