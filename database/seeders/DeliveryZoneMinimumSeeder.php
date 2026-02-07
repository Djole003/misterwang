<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryZone;

class DeliveryZoneMinimumSeeder extends Seeder
{
    public function run()
    {
        

        $minimums = [

            // MILJAKOVAC
            1 => [
                'Zelena'      => 900,
                'Zuta'        => 1000,
                'Narandzasta' => 1100,
                'Crvena'      => 1200,
            ],

            // VRAČAR
            2 => [
                'Zelena'      => 1300,
                'Zuta'        => 1500,
                'Narandzasta' => 2000,
                'Crvena'      => 2500,
            ],

            // SLAVIJA
            3 => [
                'Zelena'      => 1500,
                'Zuta'        => 2000,
                'Narandzasta' => 2500,
                'Crvena'      => 3000,
            ],

            // NOVI BEOGRAD
            4 => [
                'Zelena'      => 1300,
                'Zuta'        => 1600,
                'Narandzasta' => 1800,
                'Crvena'      => 2500,
            ],
        ];

        foreach ($minimums as $restaurantId => $zones) {
            foreach ($zones as $zoneName => $min) {

                DeliveryZone::where('restaurant_id', $restaurantId)
                    ->where('name', $zoneName)
                    ->update([
                        'minimum_amount' => $min
                    ]);

            }
        }
    }
}
