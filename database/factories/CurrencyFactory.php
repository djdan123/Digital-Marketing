<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition()
    {
        return [
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 1.000000,
            'is_default' => true,
        ];
    }
}
