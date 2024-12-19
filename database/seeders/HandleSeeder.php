<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Handle;

class HandleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $handles = [
            ['handle' => '@finance_news', 'crawling_freq' => 3600],
            ['handle' => '@crypto_updates', 'crawling_freq' => 1800],
            ['handle' => '@forex_daily', 'crawling_freq' => 7200],
            ['handle' => '@finance_trader', 'crawling_freq' => 3600],
            ['handle' => '@all_the_updates', 'crawling_freq' => 1800],
            ['handle' => '@crypto_daily', 'crawling_freq' => 7200],
            ['handle' => '@finance_expert', 'crawling_freq' => 3600],
            ['handle' => '@crypto_mogul', 'crawling_freq' => 1800],
            ['handle' => '@forex_weekly', 'crawling_freq' => 7200],
            ['handle' => '@forex_news', 'crawling_freq' => 3600],
            ['handle' => '@crypto_assets', 'crawling_freq' => 1800],
            ['handle' => '@daily_trader', 'crawling_freq' => 7200],
        ];

        foreach ($handles as $handle) {
            Handle::create($handle);
        }
    }
}
