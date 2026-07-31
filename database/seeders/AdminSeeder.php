<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Advertiser;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // Créer un profil annonceur pour l'admin (optionnel)
        Advertiser::firstOrCreate([
            'user_id' => $admin->id
        ], [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => $admin->email,
            'role' => 'admin',
            'status' => 'active',
        ]);
    }
}
