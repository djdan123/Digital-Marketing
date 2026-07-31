<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Advertisement;

class AdvertisementsSeeder extends Seeder
{
    public function run(): void
    {
        // Génère des publicités pour les campagnes créées si aucune n'existe
        if (Advertisement::count() === 0) {
            Advertisement::factory()->count(50)->create();
        }
    }
}
