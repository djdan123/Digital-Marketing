<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Advertiser;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'advertiser_id' => Advertiser::factory(),
            'amount' => $this->faker->randomFloat(4, 10, 10000),
            'currency' => 'USD',
            'status' => 'completed',
            'payment_method' => 'stripe',
            'reference' => $this->faker->uuid,
            'metadata' => [],
        ];
    }
}
