<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stat;

class StatSeeder extends Seeder
{
    public function run()
    {
        $stats = [
            ['instrument_id' => 1, 'mentions_daily' => 10, 'mentions_weekly' => 50, 'mentions_monthly' => 200, 'last_updated' => now()], // $BTC
            ['instrument_id' => 2, 'mentions_daily' => 5, 'mentions_weekly' => 30, 'mentions_monthly' => 100, 'last_updated' => now()],  // $ETH
            ['instrument_id' => 3, 'mentions_daily' => 3, 'mentions_weekly' => 15, 'mentions_monthly' => 50, 'last_updated' => now()],  // $EURUSD
        ];

        foreach ($stats as $stat) {
            Stat::create($stat);
        }
    }
}
