<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tweet;

class TweetSeeder extends Seeder
{
    public function run()
    {
        $tweets = [
            ['handle_id' => 1, 'tweet_id' => '1234567890', 'content' => 'The $BTC price is surging!', 'processed' => false, 'created_at' => now()],
            ['handle_id' => 1, 'tweet_id' => '1234567891', 'content' => 'Forex markets see $EURUSD rising.', 'processed' => false, 'created_at' => now()],
            ['handle_id' => 2, 'tweet_id' => '1234567892', 'content' => '$ETH shows strong momentum.', 'processed' => false, 'created_at' => now()],
        ];

        foreach ($tweets as $tweet) {
            Tweet::create($tweet);
        }
    }
}
