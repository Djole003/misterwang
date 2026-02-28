<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    private function getCategoryDefaults($categoryId)
    {
        $map = [

            // 1 - Predjela i salate
            1 => [
                'has_size' => 0,
                'has_sos' => 0,
                'has_meat' => 0,
                'has_rice_option' => 0,
            ],

            // 2 - Supe
            2 => [
                'has_size' => 0,
                'has_sos' => 0,
                'has_meat' => 0,
                'has_rice_option' => 0,
            ],

            // 3 - Morski plodovi
            3 => [
                'has_size' => 0,
                'has_sos' => 1,
                'has_meat' => 0,
                'has_rice_option' => 1,
            ],

            // 4 - Jela bez mesa
            4 => [
                'has_size' => 0,
                'has_sos' => 1,
                'has_meat' => 0,
                'has_rice_option' => 1,
            ],

            // 5 - Pirinač i nudle
            5 => [
                'has_size' => 0,
                'has_sos' => 0,
                'has_meat' => 0,
                'has_rice_option' => 0,
            ],

            // 6 - Dezerti
            6 => [
                'has_size' => 0,
                'has_sos' => 0,
                'has_meat' => 0,
                'has_rice_option' => 0,
            ],

            // 7 - Jela sa mesom
            7 => [
                'has_size' => 1,
                'has_sos' => 1,
                'has_meat' => 1,
                'has_rice_option' => 1,
            ],

            // 8 - Pića
            8 => [
                'has_size' => 0,
                'has_sos' => 0,
                'has_meat' => 0,
                'has_rice_option' => 0,
            ],

            // 9 - Akcije
            9 => [
                'has_size' => 0,
                'has_sos' => 0,
                'has_meat' => 0,
                'has_rice_option' => 0,
            ],

        ];

        return $map[$categoryId] ?? [
            'has_size' => 0,
            'has_sos' => 0,
            'has_meat' => 0,
            'has_rice_option' => 0,
        ];
    }

    public function run(): void
    {
        $products = [
            // Predjela i salate (category_id = 1)
            ['name' => 'Salata sa algama', 'description' => 'alge, povrće, beli luk, začini', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 1, 'image_path' => 'assets/salata_sa_algama.JPG'],
            ['name' => 'Salata sa nudlama', 'description' => 'pirinčane nudle, povrće, začini', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 1, 'image_path' => 'assets/salata_sa_nudlama.JPG'],
            ['name' => 'Salata sa susamom', 'description' => 'susam, povrće, kukuruz, začini', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 1, 'image_path' => 'assets/salata_susam.JPG'],
            ['name' => 'Rolnice sa mesom i povrćem', 'description' => '', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 1, 'image_path' => 'assets/rolnice_meso.JPG'],
            ['name' => 'Rolnice sa povrćem', 'description' => '', 'price_delivery' => 270, 'price_takeaway' => 270, 'category_id' => 1, 'image_path' => 'assets/rolnice_povrce.JPG'],
            ['name' => 'Rolnice sa sirom', 'description' => '', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 1, 'image_path' => 'assets/rolnice-sir.png'],
            ['name' => 'Cips od škampa', 'description' => '', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 1, 'image_path' => 'assets/cips.JPG'],
            ['name' => 'Rolnice sa jabukama', 'description' => '', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 1, 'image_path' => 'assets/rolnice-jabuka.png'],

            // Supe (category_id = 2)
            ['name' => 'Tomato supa', 'description' => '', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 2, 'image_path' => 'assets/tomato_supa.JPG'],
            ['name' => 'Kiselo ljuta supa', 'description' => '', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 2, 'image_path' => 'assets/kiselo_ljuta_supa.JPG'],

            // Morski plodovi (category_id = 3)
            ['name' => 'Pohovani riblji file', 'description' => '', 'price_delivery' => 1100, 'price_takeaway' => 1000, 'category_id' => 3, 'image_path' => 'assets/riblji-file.png'],
            ['name' => 'Pohovane lignje', 'description' => '', 'price_delivery' => 1100, 'price_takeaway' => 1000, 'category_id' => 3, 'image_path' => 'assets/pohovane-lignje.png'],
            ['name' => 'Gambori', 'description' => 'paprika, praziluk, šampinjoni', 'price_delivery' => 1250, 'price_takeaway' => 1200, 'category_id' => 3, 'image_path' => 'assets/gambori.png'],

            // Jela bez mesa (category_id = 4)
            ['name' => 'Tofu sir', 'description' => 'tofu, povrće, šampinjoni, sos po želji', 'price_delivery' => 800, 'price_takeaway' => 750, 'category_id' => 4, 'image_path' => 'assets/tofu.png'],
            ['name' => 'Mesano povrće', 'description' => 'povrće, paprika, kineske pečurke, šampinjoni, sos po želji', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 4, 'image_path' => 'assets/mesano_povrce.JPG'],
            ['name' => 'Prženi rezanci', 'description' => 'rezanci, povrće, šampinjoni, sos po želji', 'price_delivery' => 800, 'price_takeaway' => 750, 'category_id' => 4, 'image_path' => 'assets/przeni-rezanci.png'],
            ['name' => 'Pirinčane nudle', 'description' => 'nudle, povrće, šampinjoni, sos po želji', 'price_delivery' => 800, 'price_takeaway' => 750, 'category_id' => 4, 'image_path' => 'assets/pirincane-nudle.png'],

            // Pirinač i nudle (category_id = 5)
            ['name' => 'Beli pirinac', 'description' => '', 'price_delivery' => 200, 'price_takeaway' => 200, 'category_id' => 5, 'image_path' => 'assets/beli_pirinac.JPG'],
            ['name' => 'Kari pirinac', 'description' => '', 'price_delivery' => 250, 'price_takeaway' => 250, 'category_id' => 5, 'image_path' => 'assets/kari_pirinac.JPG'],
            ['name' => 'Sareni pirinac', 'description' => 'jaja i povrće', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 5, 'image_path' => 'assets/sareni_pirinac.JPG'],
            ['name' => 'Pirinac sa jajima', 'description' => '', 'price_delivery' => 250, 'price_takeaway' => 250, 'category_id' => 5, 'image_path' => 'assets/jaje_pirinac.JPG'],
            ['name' => 'Pirinac-Meso-Povrće', 'description' => '', 'price_delivery' => 450, 'price_takeaway' => 450, 'category_id' => 5, 'image_path' => 'assets/pmp.JPG'],

            // Dezerti (category_id = 6)
            ['name' => 'Pohovani ananas', 'description' => '', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 6, 'image_path' => 'assets/poh_ananas.JPG'],
            ['name' => 'Pohovana banana', 'description' => '', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 6, 'image_path' => 'assets/poh_banana.JPG'],
            ['name' => 'Pohovana cokolada', 'description' => '', 'price_delivery' => 350, 'price_takeaway' => 350, 'category_id' => 6, 'image_path' => 'assets/poh_cokolada.JPG'],
            ['name' => 'Pohovana jabuka', 'description' => '', 'price_delivery' => 300, 'price_takeaway' => 300, 'category_id' => 6, 'image_path' => 'assets/poh_jabuka.JPG'],
            ['name' => 'Rolnice sa čokoladom i višnjom', 'description' => '', 'price_delivery' => 350, 'price_takeaway' => 350, 'category_id' => 6, 'image_path' => 'assets/rolnice-cokolada-visnja.png'],

            // Jela sa mesom (category_id = 7)
            ['name' => 'Chow mein', 'description' => '', 'price_delivery' => 850, 'price_takeaway' => 800, 'category_id' => 7, 'image_path' => 'assets/chow-mein.png'],
            ['name' => 'Hrskava piletina', 'description' => '', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/hrskava.png'],
            
            ['name' => 'Bambus-Kineske Pecurke', 'description' => 'povrće, šampinjoni, bambus, kineske pečurke, sos', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/bambus_kin_pecurke.JPG'],
            ['name' => 'Meso u kari sosu', 'description' => 'Povrće, sos', 'price_delivery' => 700, 'price_takeaway' => 650, 'category_id' => 7, 'image_path' => 'assets/kari_sos.JPG'],
            ['name' => 'Meso u kiselo ljutom sosu', 'description' => 'Povrće, sos', 'price_delivery' => 700, 'price_takeaway' => 650, 'category_id' => 7, 'image_path' => 'assets/kiselo_ljuti.JPG'],
            ['name' => 'Kraljevska Piletina', 'description' => 'Kupus na dnu, susam piletina, ananas, tomato sos', 'price_delivery' => 800, 'price_takeaway' => 750, 'category_id' => 7, 'image_path' => 'assets/kralj.JPG'],
            ['name' => 'Kung pao piletina', 'description' => 'krastavac, paprika, krompir, šargarepa, kikiriki, tomato sos', 'price_delivery' => 850, 'price_takeaway' => 800, 'category_id' => 7, 'image_path' => 'assets/kung_pao.JPG'],
            ['name' => 'Meso u ostriga sosu', 'description' => 'Kupus na dnu, meso, sos', 'price_delivery' => 800, 'price_takeaway' => 750, 'category_id' => 7, 'image_path' => 'assets/ostriga_sos.JPG'],
            ['name' => 'Meso sa paprikom u peking sosu', 'description' => 'paprika, šampinjoni, šargarepa, sos', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/paprika_peking.JPG'],
            ['name' => 'Meso sa prazilukom u peking sosu', 'description' => 'praziluk, šampinjoni, šargarepa, sos', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/praziluk_peking.JPG'],
            ['name' => 'Meso sa kikirikijem', 'description' => 'povrće, kikiriki, sos', 'price_delivery' => 700, 'price_takeaway' => 650, 'category_id' => 7, 'image_path' => 'assets/sa_kikirikijem.JPG'],
            ['name' => 'Meso sa bademom', 'description' => 'povrće, badem, sos', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/sa_bademom.JPG'],
            ['name' => 'Meso sa indijskim orahom', 'description' => 'povrće, indijski orah, sos', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/sa_indijskim_orahom.JPG'],
            ['name' => 'Meso sa nudlama', 'description' => 'povrće, šampinjoni, nudle, sos', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/sa_nudlama.JPG'],
            ['name' => 'Meso sa sampinjonima', 'description' => 'povrće, šampinjoni, sos', 'price_delivery' => 700, 'price_takeaway' => 650, 'category_id' => 7, 'image_path' => 'assets/sa_sampinjonima.JPG'],
            ['name' => 'Meso sa sitaki pecurkama', 'description' => 'povrće, šitaki pečurke, sos', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/sitaki.JPG'],
            ['name' => 'Susam piletina', 'description' => 'povrće, šampinjoni, sos po želji', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/susam_pile.JPG'],
            ['name' => 'Pohovani komadići', 'description' => 'povrće, šampinjoni, sos po želji', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/pohovani-komadici.png'],
            ['name' => 'Usijani tiganj', 'description' => 'kupus na dnu porcije, crni luk, meso, sos', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/usijani_tiganj.png'],
            ['name' => 'Meso u sečuan sosu', 'description' => 'povrće, sos po želji', 'price_delivery' => 700, 'price_takeaway' => 650, 'category_id' => 7, 'image_path' => 'assets/secuan-pile.png'],
            ['name' => 'Meso sa krompirom i graškom', 'description' => 'krompir, grasak, sos po želji', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/krompir-grasak.png'],
            ['name' => 'Meso sa ljutom paprikom i prazilukom', 'description' => 'paprika, praziluk, sos po želji', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/paprika-praziluk.png'],
            ['name' => 'Meso sa prženim špagetama', 'description' => 'povrće, špagete, jaja,  sos po želji', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/spagete.png'],
            ['name' => 'Meso sa karfiolom', 'description' => 'brokoli, karfiol, šargarepa, sos po želji', 'price_delivery' => 750, 'price_takeaway' => 700, 'category_id' => 7, 'image_path' => 'assets/brokoli.png'],

            // Pića (category_id = 8)
            ['name' => 'Coca cola', 'description' => '0,33l', 'price_delivery' => 110, 'price_takeaway' => 100, 'category_id' => 8, 'image_path' => 'assets/kategorija_pice.jpg'],
            ['name' => 'Coca cola zero', 'description' => '0,33l', 'price_delivery' => 110, 'price_takeaway' => 100, 'category_id' => 8, 'image_path' => 'assets/coca-cola-zero.jpg'],
            ['name' => 'Fanta', 'description' => '0,33l', 'price_delivery' => 110, 'price_takeaway' => 100, 'category_id' => 8, 'image_path' => 'assets/fanta.jpg'],
            ['name' => 'Ultra energy', 'description' => '0,33l', 'price_delivery' => 130, 'price_takeaway' => 120, 'category_id' => 8, 'image_path' => 'assets/ultra.jpg'],
            ['name' => 'Sprite', 'description' => '0,33l', 'price_delivery' => 110, 'price_takeaway' => 100, 'category_id' => 8, 'image_path' => 'assets/sprite.jpg'],
            ['name' => 'Joy zova', 'description' => '0,5l', 'price_delivery' => 120, 'price_takeaway' => 110, 'category_id' => 8, 'image_path' => 'assets/joy-zova.jpg'],
            ['name' => 'Joy višnja', 'description' => '0,5l', 'price_delivery' => 120, 'price_takeaway' => 110, 'category_id' => 8, 'image_path' => 'assets/joy-visnja.jpg'],
            ['name' => 'Joy multivitamin', 'description' => '0,5l', 'price_delivery' => 120, 'price_takeaway' => 110, 'category_id' => 8, 'image_path' => 'assets/joy-multivitamin.jpg'],
            ['name' => 'Fuze tea šumsko voće', 'description' => '0,5l', 'price_delivery' => 120, 'price_takeaway' => 110, 'category_id' => 8, 'image_path' => 'assets/fuze-tea-sumsko.webp'],
            ['name' => 'Fuze tea limun', 'description' => '0,5l', 'price_delivery' => 120, 'price_takeaway' => 110, 'category_id' => 8, 'image_path' => 'assets/fuze-tea-limun.jpg'],
            ['name' => 'Fuze tea višnja', 'description' => '0,5l', 'price_delivery' => 120, 'price_takeaway' => 110, 'category_id' => 8, 'image_path' => 'assets/fuze-tea-visnja.jpg'],
            ['name' => 'Voda', 'description' => '0,5l', 'price_delivery' => 70, 'price_takeaway' => 70, 'category_id' => 8, 'image_path' => 'assets/voda.jpg'],
            ['name' => 'Schweppes', 'description' => '0,33l', 'price_delivery' => 110, 'price_takeaway' => 100, 'category_id' => 8, 'image_path' => 'assets/sveps.jpg'],
            ['name' => 'Monster žuti', 'description' => '0,5l', 'price_delivery' => 250, 'price_takeaway' => 250, 'category_id' => 8, 'image_path' => 'assets/monster-zuti.jpg'],
            ['name' => 'Monster beli', 'description' => '0,5l', 'price_delivery' => 250, 'price_takeaway' => 250, 'category_id' => 8, 'image_path' => 'assets/monster-beli.jpg'],
            ['name' => 'Monster crni', 'description' => '0,5l', 'price_delivery' => 250, 'price_takeaway' => 250, 'category_id' => 8, 'image_path' => 'assets/monster-crni.webp'],
            ['name' => 'Monster rozi', 'description' => '0,5l', 'price_delivery' => 250, 'price_takeaway' => 250, 'category_id' => 8, 'image_path' => 'assets/monster-rozi.jpg'],

            
            
            
            // ======================
            // AKCIJA – COMBO PONUDA
            // ======================
            [
                'name' => 'Susam piletina + Coca-Cola',
                'description' => 'Specijalna akcija: Susam piletina + Coca-Cola (ograničena ponuda)',
                'price_delivery' => 750,
                'price_takeaway' => 750,
                'category_id' => 9, // Akcije
                'image_path' => 'assets/susam-akcija.png',
            ],

        ];


        foreach ($products as $product) {

            $defaults = $this->getCategoryDefaults($product['category_id']);

            Product::create(array_merge($product, $defaults));
        }

        // =========================
        // IZUZECI (OVDE DODAJEMO)
        // =========================

        // Hrskava piletina – nema izbor mesa ni sosa
        Product::where('name', 'Hrskava piletina')
            ->update([
                'has_meat' => 0,
            ]);

        // Kung Pao – nema izbor mesa ni sosa
        Product::where('name', 'Kung pao piletina')
            ->update([
                'has_sos' => 0,
                'has_meat' => 0,
            ]);

        // Kraljevska – nema izbor mesa
        Product::where('name', 'Kraljevska Piletina')
            ->update([
                'has_sos' => 0,
                'has_meat' => 0,
            ]);


        Product::where('name', 'Susam piletina')
            ->update([
                'has_meat' => 0,
            ]);

        Product::where('name', 'Chow mein')
        ->update([
            'has_meat' => 0,
        ]);
        
        Product::where('name', 'Pohovani komadići')
        ->update([
            'has_meat' => 0,
        ]);

        Product::where('name', 'Meso sa prazilukom u peking sosu')
        ->update([
            'has_sos' => 0,
        ]);

        Product::where('name', 'Meso sa paprikom u peking sosu')
        ->update([
            'has_sos' => 0,
        ]);
}
}