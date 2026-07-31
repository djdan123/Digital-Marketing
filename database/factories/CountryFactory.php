<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition()
    {
        return [
            'name' => 'United States',
            'iso_code' => 'USA',
            'currency_id' => Currency::factory(),
        ];
    }
}
