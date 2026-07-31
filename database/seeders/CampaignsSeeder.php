<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;

class CampaignsSeeder extends Seeder
{
    public function run(): void
    {
        // Crée des campagnes liées aux annonceurs existants si aucune n'existe
        if (Campaign::count() === 0) {
            Campaign::factory()->count(20)->create();
        }
    }
}
