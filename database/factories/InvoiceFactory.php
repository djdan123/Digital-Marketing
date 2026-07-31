<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Advertiser;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        $subtotal = $this->faker->randomFloat(4, 10, 1000);
        $tax = $subtotal * 0.18;
        return [
            'advertiser_id' => Advertiser::factory(),
            'invoice_number' => strtoupper($this->faker->bothify('INV-#####')),
            'issued_at' => $this->faker->date(),
            'due_at' => $this->faker->dateTimeBetween('+7 days', '+30 days')->format('Y-m-d'),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $subtotal + $tax,
            'status' => 'issued',
            'line_items' => [],
        ];
    }
}
