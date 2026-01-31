<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('restaurant_status')->truncate();

        DB::table('restaurant_status')->insert([
            'is_open' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
