<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instrument;

class InstrumentSeeder extends Seeder
{
    public function run()
    {
        $instruments = [
            ['symbol' => '$BTC'],
            ['symbol' => '$ETH'],
            ['symbol' => '$EURUSD'],
        ];

        foreach ($instruments as $instrument) {
            Instrument::create($instrument);
        }
    }
}
