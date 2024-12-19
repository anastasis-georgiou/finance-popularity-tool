<?php

namespace App\Jobs;

use App\Models\Handle;
use App\Models\Tweet;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\DomCrawler\Crawler;

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
            $tweets = $this->fetchMokTweets();

            // Fetch tweets from twitter
            //$tweets = $this->fetchTweets($this->handle->handle);

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

    public function fetchMokTweets(): array
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

    public function fetchTweets(string $twitterHandle): array
    {
        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
            ],
            'timeout' => 10,
            'verify' => false
        ]);

        // Request the Twitter page for the given handle
        $url = env("CRAWL_TARGET_SITE", "https://x.com")."/{$twitterHandle}";
        $response = $client->get($url);

        $html = (string) $response->getBody();

        Log::info("response data: {$html}");

        // Parse HTML to extract tweets
        $crawler = new Crawler($html);

        // This CSS selector may break if Twitter changes its layout.
        // It might not work due to JS rendering of tweets.
        $tweets = $crawler->filter('article div[data-testid="tweet"]')->each(function (Crawler $node) {
            // Attempt to find tweet text
            // This is a heuristic and may need adjusting if Twitter changes structure
            $contentNode = $node->filter('div[lang]');
            $content = $contentNode->count() ? $contentNode->text() : '';

            // Without full JS-rendered HTML, some tweets may not appear correctly.
            // Also, determining "created_at" is non-trivial without API or JSON data.
            return [
                'id' => uniqid(),
                'content' => $content,
                'created_at' => now(), // Placeholder since we don't get an actual timestamp easily
            ];
        });

        return $tweets;
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
