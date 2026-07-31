<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Advertiser;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition()
    {
        $starts = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $ends = (clone $starts)->modify('+'.rand(1,30).' days');

        return [
            'advertiser_id' => Advertiser::factory(),
            'name' => $this->faker->sentence(3),
            'objective' => $this->faker->sentence,
            'status' => 'draft',
            'starts_at' => $starts->format('Y-m-d'),
            'ends_at' => $ends->format('Y-m-d'),
            'budget' => $this->faker->randomFloat(4, 100, 10000),
            'spent' => 0,
            'targeting' => [],
        ];
    }
}
