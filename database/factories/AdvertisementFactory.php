<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\Campaign;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementFactory extends Factory
{
    protected $model = Advertisement::class;

    public function definition()
    {
        return [
            'campaign_id' => Campaign::factory(),
            'media_id' => Media::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'format' => $this->faker->randomElement(['audio','video','image','text']),
            'status' => 'draft',
            'meta' => [],
            'cost' => $this->faker->randomFloat(4, 10, 1000),
        ];
    }
}
