<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AddOn;
use App\Models\Product;

class AddOnSeeder extends Seeder
{
    public function run(): void
    {

        $addons = [
            ['name' => 'Nudle dodatak', 'price' => 100],
            ['name' => 'Šampinjoni', 'price' => 100],
            ['name' => 'Šitaki pečurke', 'price' => 100],
            ['name' => 'Meso dodatak', 'price' => 100],
            ['name' => 'Kikiriki', 'price' => 100],
            ['name' => 'Indijski orah', 'price' => 100],
            ['name' => 'Badem', 'price' => 100],
            ['name' => 'Bambus', 'price' => 100],
            ['name' => 'Kineske pečurke', 'price' => 100],
            ['name' => 'Rezanci', 'price' => 100],
            ['name' => 'Tomato sos', 'price' => 100],
            ['name' => 'Soja sos', 'price' => 100],
            ['name' => 'Sečuan sos', 'price' => 100],
        ];

        foreach ($addons as $addon) {
            AddOn::create($addon);
        }

        // Povezivanje sa proizvodima (primer: svi proizvodi imaju sve dodatke)
        $products = Product::all();
        foreach ($products as $product) {
            $product->addOns()->attach(AddOn::all());
        }
    }
}
