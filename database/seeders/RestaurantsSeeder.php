<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantsSeeder extends Seeder
{
    public function run(): void
    {
        Restaurant::insert([
            [
                'name' => 'Miljakovac',
                'slug' => 'miljakovac',
                'image_path' => 'assets/miljakovac.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Vračar',
                'slug' => 'vracar',
                'image_path' => 'assets/vracar.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Slavija',
                'slug' => 'slavija',
                'image_path' => 'assets/slavija.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Novi Beograd',
                'slug' => 'novi-beograd',
                'image_path' => 'assets/novi_beograd.jpg',
                'is_active' => true,
            ],
        ]);
    }
}
