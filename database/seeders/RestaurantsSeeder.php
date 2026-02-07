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
                'center_lat' => 44.757310478274604,
                'center_lng' => 20.459934625524028,
                'is_active' => true,
            ],
            [
                'name' => 'Vračar',
                'slug' => 'vracar',
                'image_path' => 'assets/vracar.jpg',
                'center_lat' => 44.791516751015756,
                'center_lng' => 20.49703469852065,
                'is_active' => true,
            ],
            [
                'name' => 'Slavija',
                'slug' => 'slavija',
                'image_path' => 'assets/slavija.jpg',
                'center_lat' => 44.802051860142114,
                'center_lng' => 20.46567709852125,
                'is_active' => true,
            ],
            [
                'name' => 'Novi Beograd',
                'slug' => 'novi-beograd',
                'image_path' => 'assets/novi_beograd.jpg',
                'center_lat' => 44.82191858077459,
                'center_lng' => 20.410611596671448,
                'is_active' => true,
            ],
        ]);
    }
}
