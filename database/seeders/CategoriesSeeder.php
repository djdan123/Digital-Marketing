<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Radio', 'Television', 'Online', 'Social', 'Outdoor'];
        foreach ($categories as $c) {
            Category::firstOrCreate(['name' => $c], ['slug' => strtolower($c)]);
        }
    }
}
