<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->catchPhrase,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'website' => $this->faker->url,
            'status' => 'active',
        ];
    }
}
