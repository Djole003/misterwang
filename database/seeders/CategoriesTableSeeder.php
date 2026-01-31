<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Predjela i salate', 'image' => 'assets/kategorija_salata.jpg'],
            ['name' => 'Supe', 'image' => 'assets/kategorija_supe.jpg'],
            ['name' => 'Morski plodovi', 'image' => 'assets/kategorija_morski_plodovi.jpg'],
            ['name' => 'Jela bez mesa', 'image' => 'assets/kategorija_jela_bez_mesa.jpg'],
            ['name' => 'Pirinač i nudle', 'image' => 'assets/kategorija_pirinac.jpg'],
            ['name' => 'Dezerti', 'image' => 'assets/poh_ananas.jpg'],
            ['name' => 'Jela sa mesom', 'image' => 'assets/susam_pile.jpg'],
            ['name' => 'Piće', 'image' => 'assets/kategorija_pice.jpg'],
            ['name' => 'Akcije', 'image' => 'assets/susam-akcija.PNG'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'image' => $cat['image'], // ovde mora da stoji "image"
            ]);
        }
    }
}
