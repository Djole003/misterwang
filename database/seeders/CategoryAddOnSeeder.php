<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\AddOn;

class CategoryAddOnSeeder extends Seeder
{
    public function run(): void
    {
        // Kategorije koje imaju dodatke
        $categoryIds = [7, 3, 4]; 
        // 7 = Jela sa mesom
        // 3 = Morski plodovi
        // 4 = Jela bez mesa

        $addons = AddOn::all();

        foreach ($categoryIds as $categoryId) {

            $category = Category::find($categoryId);

            if ($category) {
                $category->addOns()->syncWithoutDetaching(
                    $addons->pluck('id')->toArray()
                );
            }
        }
    }
}