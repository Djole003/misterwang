<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryZone;

class DeliveryZoneMinimumSeeder extends Seeder
{
    public function run()
    {
        // Mapiranje naziva zona na minimume
        $minimums = [
            'Zelena'      => 900,
            'Zuta'        => 1000,
            'Narandzasta' => 1100,
            'Crvena'      => 1200,
        ];

        foreach ($minimums as $zoneName => $min) {
            DeliveryZone::where('name', $zoneName)
                ->update([
                    'minimum_amount' => $min
                ]);
        }
    }
}
