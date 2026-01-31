<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RestaurantContact;

class RestaurantContactsSeeder extends Seeder
{
    public function run(): void
    {
        RestaurantContact::truncate();

        RestaurantContact::insert([

            // ================= MILJAKOVAC =================
            [
                'restaurant_id' => 1,
                'address' => 'Borska 45i, Miljakovac',
                'phone' => '064 521 48 00',
                'email' => 'miljakovac@misterwang.rs',
                'working_hours' => '10:00 – 23:00',
            ],

            // ================= VRACAR =================
            [
                'restaurant_id' => 2,
                'address' => 'Gospodara Vučića 201, Vračar',
                'phone' => '064 548 89 90',
                'email' => 'vracar@misterwang.rs',
                'working_hours' => '10:00 – 23:00',
            ],

            // ================= SLAVIJA =================
            [
                'restaurant_id' => 3,
                'address' => 'Deligradska 3, Slavija',
                'phone' => '064 922 90 00',
                'email' => 'slavija@misterwang.rs',
                'working_hours' => '10:00 – 23:00',
            ],

            // ================= NOVI BEOGRAD =================
            [
                'restaurant_id' => 4,
                'address' => 'Omladinskih brigada 18, Novi Beograd',
                'phone' => '064 242 70 73',
                'email' => 'nbg@misterwang.rs',
                'working_hours' => '10:00 – 23:00',
            ],
        ]);
    }
}
