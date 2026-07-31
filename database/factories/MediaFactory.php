<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company . ' ' . $this->faker->word,
            'category_id' => Category::factory(),
            'type' => $this->faker->randomElement(['radio','television','web','social','led']),
            'pricing_type' => $this->faker->randomElement(['fixed','cpm','per_spot']),
            'base_price' => $this->faker->randomFloat(4, 0, 1000),
            'description' => $this->faker->sentence,
            'status' => 'active',
        ];
    }
}
