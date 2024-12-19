<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InstrumentMention;

class InstrumentMentionSeeder extends Seeder
{
    public function run()
    {
        $mentions = [
            ['instrument_id' => 1, 'tweet_id' => 1, 'mentioned_at' => now()], // $BTC in Tweet 1
            ['instrument_id' => 3, 'tweet_id' => 2, 'mentioned_at' => now()], // $EURUSD in Tweet 2
            ['instrument_id' => 2, 'tweet_id' => 3, 'mentioned_at' => now()], // $ETH in Tweet 3
        ];

        foreach ($mentions as $mention) {
            InstrumentMention::create($mention);
        }
    }
}
