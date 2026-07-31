<?php

namespace Database\Factories;

use App\Models\Advertiser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertiserFactory extends Factory
{
    protected $model = Advertiser::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'role' => 'advertiser',
            'status' => 'active',
        ];
    }
}
