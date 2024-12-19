<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class InstrumentExtractionTest extends TestCase
{
    public function test_extract_instruments_from_tweet_content()
    {
        $content = 'Check out $BTC and $ETH, but ignore $$$$$$.';
        preg_match_all('/\$[A-Z]{2,}/', $content, $matches);
        $symbols = $matches[0];

        $this->assertEquals(['$BTC', '$ETH'], $symbols);
    }

    public function test_extract_instruments_from_simple_tweet()
    {
        $content = 'Check out $BTC and $ETH.';
        preg_match_all('/\$[A-Z]{2,}/', $content, $matches);
        $symbols = $matches[0];

        $this->assertEquals(['$BTC', '$ETH'], $symbols);
    }

    public function test_extract_instruments_ignores_invalid_symbols()
    {
        $content = 'Ignore $$$$$$ and check $EURUSD.';
        preg_match_all('/\$[A-Z]{2,}/', $content, $matches);
        $symbols = $matches[0];

        // Valid instrument: $EURUSD (6 chars including $), Invalid: $$$$$$ (not standard)
        $this->assertEquals(['$EURUSD'], $symbols);
    }

    public function test_no_instruments_found()
    {
        $content = 'No financial symbols here.';
        preg_match_all('/\$[A-Z]{2,}/', $content, $matches);
        $symbols = $matches[0];

        $this->assertEmpty($symbols);
    }

    public function test_top_instruments_is_cached()
    {
        Cache::flush();
        $response1 = $this->getJson('/api/top-instruments/daily');
        $response1->assertStatus(200);

        // The second call should hit the cache
        $response2 = $this->getJson('/api/top-instruments/daily');
        $response2->assertStatus(200);

        // If you logged queries, you would see fewer queries on the second request.
        // Alternatively, check if the cache key exists:
        $this->assertTrue(Cache::has('top_instruments_daily'));
    }

    public function test_fetch_tweets_from_twitter()
    {
        $mock = \Mockery::mock(Client::class);
        $mock->shouldReceive('get')->andReturn(new \GuzzleHttp\Psr7\Response(200, [], '<html>fake twitter html</html>'));

        $this->app->instance(Client::class, $mock);

        // Call your method that uses Guzzle
        $tweets = $this->app->make('App\Jobs\CrawlTweetsJob')->fetchTweets('@test_handle');
        $this->assertIsArray($tweets);
    }


}
