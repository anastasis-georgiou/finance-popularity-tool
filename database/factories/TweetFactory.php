<?php

namespace Database\Factories;

use App\Models\Handle;
use App\Models\Tweet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TweetFactory extends Factory
{
    protected $model = Tweet::class;

    public function definition()
    {
        return [
            'handle_id' => Handle::factory(),
            'tweet_id' => (string) Str::uuid(),
            'content' => $this->faker->sentence,
            'processed' => $this->faker->boolean(50), // 50% chance processed
            'created_at' => $this->faker->dateTimeBetween('-1 weeks', 'now'),
        ];
    }
}
