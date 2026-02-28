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

            RestaurantsSeeder::class,       
            RestaurantContactsSeeder::class,
            RadnoVremeSeeder::class,        
            RestaurantStatusSeeder::class,
            DeliveryZonesSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | KORISNICI
            |--------------------------------------------------------------------------
            */

            UserSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | PROIZVODI
            |--------------------------------------------------------------------------
            */

            CategoriesTableSeeder::class,   // 1️⃣ mora prvo kategorije
            AddOnSeeder::class,             // 2️⃣ pa dodaci
            ProductsTableSeeder::class,     // 3️⃣ pa proizvodi
            CategoryAddOnSeeder::class,     // 4️⃣ pa povezivanje kategorija i dodataka
            RestaurantProductSeeder::class, // 5️⃣ pivot za cene po lokalu

            /*
            |--------------------------------------------------------------------------
            | TEST PODACI (po potrebi)
            |--------------------------------------------------------------------------
            */
            // OrderProductSeeder::class,

        ]);
    }
}