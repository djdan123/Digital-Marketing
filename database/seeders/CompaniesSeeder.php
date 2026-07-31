<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer un pays existant (ou en créer un)
        $country = Country::first();
        if (!$country) {
            // Créer un pays par défaut si aucun n'existe
            $currency = Currency::firstOrCreate(['code' => 'USD'], [
                'name' => 'US Dollar',
                'symbol' => '$',
                'exchange_rate' => 1.0,
                'is_default' => true,
            ]);
            $country = Country::firstOrCreate(['iso_code' => 'US'], [
                'name' => 'United States',
                'currency_id' => $currency->id,
            ]);
        }

        // Créer quelques entreprises
        $companies = [
            [
                'name' => 'TruckAll Media Group',
                'slug' => 'truckall-media-group',
                'description' => 'Groupe médias de la plateforme TruckAll',
                'address' => '123 Main Street, New York',
                'phone' => '+1 234 567 890',
                'website' => 'https://truckall.com',
                'status' => 'active',
                'country_id' => $country->id,
            ],
            [
                'name' => 'Radio Star Corporation',
                'slug' => 'radio-star-corp',
                'description' => 'Société de radiodiffusion',
                'address' => '456 Broadway, Los Angeles',
                'phone' => '+1 987 654 321',
                'website' => 'https://radiostar.com',
                'status' => 'active',
                'country_id' => $country->id,
            ],
            [
                'name' => 'TV Network Africa',
                'slug' => 'tv-network-africa',
                'description' => 'Chaîne de télévision panafricaine',
                'address' => '789 Avenue, Dakar',
                'phone' => '+221 33 123 456',
                'website' => 'https://tvnetwork.africa',
                'status' => 'active',
                'country_id' => $country->id,
            ],
        ];

        foreach ($companies as $data) {
            Company::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}