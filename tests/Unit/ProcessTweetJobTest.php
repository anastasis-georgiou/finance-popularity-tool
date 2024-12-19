<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Jobs\ProcessTweetsJob;
use App\Models\Tweet;
use App\Models\Instrument;
use App\Models\InstrumentMention;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ProcessTweetJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_processes_tweet_and_creates_mentions()
    {
        // Create a tweet that needs processing
        $tweet = Tweet::factory()->create(['content' => 'The $BTC price!', 'processed' => false]);

        $job = new ProcessTweetsJob($tweet);
        $job->handle();

        // Assertions
        $this->assertTrue($tweet->fresh()->processed);
        $this->assertDatabaseHas('instruments', ['symbol' => '$BTC']);
        $this->assertDatabaseHas('instrument_mentions', ['tweet_id' => $tweet->id]);
    }
}
