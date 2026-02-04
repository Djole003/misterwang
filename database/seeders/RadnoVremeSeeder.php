<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RadnoVreme;
use Carbon\Carbon;

class RadnoVremeSeeder extends Seeder
{
    public function run(): void
    {
        // Čist start
        RadnoVreme::truncate();

        $now = Carbon::now();

        /*
        DAN:
        0 = Nedelja
        1 = Ponedeljak
        2 = Utorak
        3 = Sreda
        4 = Četvrtak
        5 = Petak
        6 = Subota
        */

        /* ======================
           MILJAKOVAC (ID = 1)
           Radni dani: 09:00 – 21:40
           Subota: ne radi
           Nedelja: 11:30 – 19:30
           ====================== */

        foreach ([1,2,3,4,5] as $dan) {
            RadnoVreme::create([
                'restaurant_id' => 1,
                'dan' => $dan,
                'otvara_se' => '09:00:00',
                'zatvara_se' => '21:40:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Nedelja – posebno vreme
        RadnoVreme::create([
            'restaurant_id' => 1,
            'dan' => 0,
            'otvara_se' => '11:30:00',
            'zatvara_se' => '19:30:00',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Subota – NE RADI (upis bez radnog vremena)
        RadnoVreme::create([
            'restaurant_id' => 1,
            'dan' => 6,
            'otvara_se' => null,
            'zatvara_se' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /* ======================
           OSTALI LOKALI (ID 2,3,4...)
           Radni dani: 10:00 – 21:40
           Subota: 10:00 – 21:40
           Nedelja: ne radi
           ====================== */

        $ostaliLokali = [2, 3, 4];

        foreach ($ostaliLokali as $lokal) {

            // Radni dani
            foreach ([1,2,3,4,5] as $dan) {
                RadnoVreme::create([
                    'restaurant_id' => $lokal,
                    'dan' => $dan,
                    'otvara_se' => '10:00:00',
                    'zatvara_se' => '21:40:00',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Subota – radi isto kao radni dan
            RadnoVreme::create([
                'restaurant_id' => $lokal,
                'dan' => 6,
                'otvara_se' => '10:00:00',
                'zatvara_se' => '21:40:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Nedelja – ne radi
            RadnoVreme::create([
                'restaurant_id' => $lokal,
                'dan' => 0,
                'otvara_se' => null,
                'zatvara_se' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
