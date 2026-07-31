<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::fa'ctory(10)->create();

        $this->call([
            RolesPermissionsSeeder::class,
            CountriesSeeder::class,
            CategoriesSeeder::class,
            CompaniesSeeder::class,  
            MediaSeeder::class,
            AdminSeeder::class,
            AdvertisersSeeder::class,
            CampaignsSeeder::class,
            AdvertisementsSeeder::class,
        ]);

        // Exemple d'utilisateur de test (idempotent)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                  'password' => Hash::make('password'),

            ]
        );
    }
}
