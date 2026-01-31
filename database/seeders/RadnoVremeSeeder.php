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
           Radni dani: 09–22
           Subota: ne radi
           Nedelja: 11–20
           ====================== */

        foreach ([1,2,3,4,5] as $dan) {
            RadnoVreme::create([
                'restaurant_id' => 1,
                'dan' => $dan,
                'otvara_se' => '09:00:00',
                'zatvara_se' => '22:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        RadnoVreme::create([
            'restaurant_id' => 1,
            'dan' => 0,
            'otvara_se' => '11:00:00',
            'zatvara_se' => '20:00:00',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /* ======================
           VRAČAR (ID = 2)
           Svaki dan radi
           Nedelja: 10–22
           ====================== */

        foreach ([1,2,3,4,5,6] as $dan) {
            RadnoVreme::create([
                'restaurant_id' => 2,
                'dan' => $dan,
                'otvara_se' => '09:00:00',
                'zatvara_se' => '22:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        RadnoVreme::create([
            'restaurant_id' => 2,
            'dan' => 0,
            'otvara_se' => '10:00:00',
            'zatvara_se' => '22:00:00',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /* ======================
           SLAVIJA (ID = 3)
           Radni dani: 09–22
           Subota: 11–22
           Nedelja: ne radi
           ====================== */

        foreach ([1,2,3,4,5] as $dan) {
            RadnoVreme::create([
                'restaurant_id' => 3,
                'dan' => $dan,
                'otvara_se' => '09:00:00',
                'zatvara_se' => '22:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        RadnoVreme::create([
            'restaurant_id' => 3,
            'dan' => 6,
            'otvara_se' => '11:00:00',
            'zatvara_se' => '22:00:00',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /* ======================
           NOVI BEOGRAD (ID = 4)
           Svaki dan radi
           Nedelja: 10–22
           ====================== */

        foreach ([1,2,3,4,5,6] as $dan) {
            RadnoVreme::create([
                'restaurant_id' => 4,
                'dan' => $dan,
                'otvara_se' => '09:00:00',
                'zatvara_se' => '22:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        RadnoVreme::create([
            'restaurant_id' => 4,
            'dan' => 0,
            'otvara_se' => '10:00:00',
            'zatvara_se' => '22:00:00',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
