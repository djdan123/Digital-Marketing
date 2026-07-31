<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Models\Country;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $currency = Currency::firstOrCreate(['code' => 'USD'], ['name' => 'US Dollar', 'symbol' => '$', 'exchange_rate' => 1.0, 'is_default' => true]);

        Country::firstOrCreate(['iso_code' => 'USA'], ['name' => 'United States', 'currency_id' => $currency->id]);
    }
}
