<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Landforms',
            'Water Bodies',
            'Vegetation',
            'Urban Areas',
            'Agricultural Use',
            'Country',
            'State/Province',
            'Municipalities',
            'Districts/Counties'
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
