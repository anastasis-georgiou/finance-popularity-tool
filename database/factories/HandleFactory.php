<?php

namespace Database\Factories;

use App\Models\Handle;
use Illuminate\Database\Eloquent\Factories\Factory;

class HandleFactory extends Factory
{
    protected $model = Handle::class;

    public function definition()
    {
        return [
            'handle' => '@' . $this->faker->unique()->word,
            'crawling_freq' => $this->faker->numberBetween(300, 7200), // between 5min to 2hrs
            'last_crawled_at' => $this->faker->dateTimeBetween('-1 days', 'now'),
        ];
    }
}
