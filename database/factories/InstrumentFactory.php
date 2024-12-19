<?php

namespace Database\Factories;

use App\Models\Instrument;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstrumentFactory extends Factory
{
    protected $model = Instrument::class;

    public function definition()
    {
        // Generate a symbol like $ABC or $BTC, etc.
        // We'll use uppercase letters to form random 3-letter codes.
        $symbol = '$' . strtoupper($this->faker->lexify('???'));

        return [
            'symbol' => $symbol
        ];
    }
}
