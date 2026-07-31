<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;
use App\Models\Category;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::firstOrCreate(['name' => 'Radio'], ['slug' => 'radio']);

        Media::firstOrCreate([
            'name' => 'Radio FM Example',
            'category_id' => $category->id
        ], [
            'type' => 'radio',
            'pricing_type' => 'per_spot',
            'base_price' => 50.00,
            'description' => 'Station locale exemple',
            'status' => 'active'
        ]);
    }
}
