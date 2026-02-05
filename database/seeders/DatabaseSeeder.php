<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            /*
            |--------------------------------------------------------------------------
            | OSNOVNI PODACI (REDOSLED JE BITAN)
            |--------------------------------------------------------------------------
            */

            RestaurantsSeeder::class,       // 1️⃣ prvo lokali
            RestaurantContactsSeeder::class,
            RadnoVremeSeeder::class,       // 2️⃣ radno vreme po lokalu
            RestaurantStatusSeeder::class, // 3️⃣ status restorana (open/closed)
            DeliveryZonesSeeder::class,
            DeliveryZoneMinimumSeeder::class,
            /*
            |--------------------------------------------------------------------------
            | KORISNICI
            |--------------------------------------------------------------------------
            */

            UserSeeder::class,              // 4️⃣ editor + admini + user

            /*
            |--------------------------------------------------------------------------
            | PROIZVODI
            |--------------------------------------------------------------------------
            */

            CategoriesTableSeeder::class,   // 5️⃣ kategorije
            AddOnSeeder::class,             // 6️⃣ dodaci
            ProductsTableSeeder::class,     // 7️⃣ proizvodi

            /*
            |--------------------------------------------------------------------------
            | ❌ NE SEEDUJEMO PORUDŽBINE AUTOMATSKI
            |--------------------------------------------------------------------------
            | OrderProductSeeder nam treba samo za testiranje,
            | NE u osnovnom seedingu.
            */

        ]);
    }
}
