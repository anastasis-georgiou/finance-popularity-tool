<?php

namespace Database\Factories;

use App\Models\Instrument;
use App\Models\InstrumentMention;
use App\Models\Tweet;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstrumentMentionFactory extends Factory
{
    protected $model = InstrumentMention::class;

    public function definition()
    {
        return [
            'instrument_id' => Instrument::factory(),
            'tweet_id' => Tweet::factory(),
            'mentioned_at' => $this->faker->dateTimeBetween('-1 weeks', 'now'),
        ];
    }
}
