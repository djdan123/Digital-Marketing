<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Advertiser;

class AdvertisersSeeder extends Seeder
{
    public function run(): void
    {
        // Crée 10 annonceurs via la factory si la table est vide
        if (Advertiser::count() === 0) {
            Advertiser::factory()->count(10)->create();
        }
    }
}
