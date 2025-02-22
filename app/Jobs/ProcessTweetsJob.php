<?php

namespace App\Jobs;

use App\Models\Instrument;
use App\Models\InstrumentMention;
use App\Models\Tweet;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTweetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tweet;

    public function __construct(Tweet $tweet)
    {
        $this->tweet = $tweet;
    }

    public function handle()
    {
        try {
            Log::info("Processing tweet ID: {$this->tweet->id}");

            preg_match_all('/\$[A-Z]{2,}/', $this->tweet->content, $matches);
            $symbols = $matches[0];

            foreach ($symbols as $symbol) {
                if (strlen($symbol) > 5) {
                    continue; // Skip invalid symbols like $$$$$$
                }

                $instrument = Instrument::firstOrCreate(['symbol' => $symbol]);
                InstrumentMention::create([
                    'instrument_id' => $instrument->id,
                    'tweet_id' => $this->tweet->id,
                    'mentioned_at' => $this->tweet->created_at,
                ]);
            }

            $this->tweet->update(['processed' => true]);
        } catch (\Exception $e) {
            Log::error("Error processing tweet ID: {$this->tweet->id}. Error: " . $e->getMessage());
        }
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
