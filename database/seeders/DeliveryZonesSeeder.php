<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryZone;

class DeliveryZonesSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 očisti tabelu
        DeliveryZone::truncate();

        $zones = [
            [
                'restaurant_id' => 1,
                'center_lat' => 44.757310478274604,
                'center_lng' => 20.459934625524028,
            ],
            [
                'restaurant_id' => 2,
                'center_lat' => 44.791516751015756,
                'center_lng' => 20.49703469852065,
            ],
            [
                'restaurant_id' => 3,
                'center_lat' => 44.802051860142114,
                'center_lng' => 20.46567709852125,
            ],
            [
                'restaurant_id' => 4,
                'center_lat' => 44.82191858077459,
                'center_lng' => 20.410611596671448,
            ],
        ];

        foreach ($zones as $center) {

            DeliveryZone::insert([
                [
                    'restaurant_id' => $center['restaurant_id'],
                    'name' => 'Zelena',
                    'center_lat' => $center['center_lat'],
                    'center_lng' => $center['center_lng'],
                    'radius' => 2000,
                    'price' => 100,
                ],
                [
                    'restaurant_id' => $center['restaurant_id'],
                    'name' => 'Zuta',
                    'center_lat' => $center['center_lat'],
                    'center_lng' => $center['center_lng'],
                    'radius' => 4000,
                    'price' => 150,
                ],
                [
                    'restaurant_id' => $center['restaurant_id'],
                    'name' => 'Narandzasta',
                    'center_lat' => $center['center_lat'],
                    'center_lng' => $center['center_lng'],
                    'radius' => 6000,
                    'price' => 200,
                ],
                [
                    'restaurant_id' => $center['restaurant_id'],
                    'name' => 'Crvena',
                    'center_lat' => $center['center_lat'],
                    'center_lng' => $center['center_lng'],
                    'radius' => 8500,
                    'price' => 250,
                ],
            ]);
        }
    }
}
