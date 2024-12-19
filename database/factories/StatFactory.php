<?php

namespace Database\Factories;

use App\Models\Instrument;
use App\Models\Stat;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatFactory extends Factory
{
    protected $model = Stat::class;

    public function definition()
    {
        return [
            'instrument_id' => Instrument::factory(),
            'mentions_daily' => $this->faker->numberBetween(0, 100),
            'mentions_weekly' => $this->faker->numberBetween(0, 700),
            'mentions_monthly' => $this->faker->numberBetween(0, 3000),
            'last_updated' => $this->faker->dateTimeBetween('-1 hour', 'now'),
        ];
    }
}
