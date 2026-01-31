<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
 


        /*
        |--------------------------------------------------------------------------
        | OWNER / SUPERADMIN (TI)
        |--------------------------------------------------------------------------
        | role = editor
        | restaurant_id = NULL (vidi sve lokale)
        */
        User::create([
            'name'          => 'Djordje Kitic',
            'email'         => 'editor@gmail.rs',
            'password'      => Hash::make('editor'),
            'telefon'       => '0652345678',
            'adresa'        => 'Beograd',
            'role'          => 'editor',
            'restaurant_id' => null,
            'active'        => 1,
        ]);

        /*
        |--------------------------------------------------------------------------
        | ADMINI – PO JEDAN ZA SVAKI LOKAL
        |--------------------------------------------------------------------------
        */

        User::create([
            'name'          => 'Admin Miljakovac',
            'email'         => 'admin.miljakovac@gmail.rs',
            'password'      => Hash::make('admin'),
            'telefon'       => '0640000001',
            'adresa'        => 'Miljakovac',
            'role'          => 'admin',
            'restaurant_id' => 1,
            'active'        => 1,
        ]);

        User::create([
            'name'          => 'Admin Vračar',
            'email'         => 'admin.vracar@gmail.rs',
            'password'      => Hash::make('admin'),
            'telefon'       => '0640000002',
            'adresa'        => 'Vračar',
            'role'          => 'admin',
            'restaurant_id' => 2,
            'active'        => 1,
        ]);

        User::create([
            'name'          => 'Admin Slavija',
            'email'         => 'admin.slavija@gmail.rs',
            'password'      => Hash::make('admin'),
            'telefon'       => '0640000003',
            'adresa'        => 'Slavija',
            'role'          => 'admin',
            'restaurant_id' => 3,
            'active'        => 1,
        ]);

        User::create([
            'name'          => 'Admin Novi Beograd',
            'email'         => 'admin.nb@gmail.rs',
            'password'      => Hash::make('admin'),
            'telefon'       => '0640000004',
            'adresa'        => 'Novi Beograd',
            'role'          => 'admin',
            'restaurant_id' => 4,
            'active'        => 1,
        ]);

        /*
        |--------------------------------------------------------------------------
        | OBIČAN KORISNIK
        |--------------------------------------------------------------------------
        */
        User::create([
            'name'          => 'Marko Kitic',
            'email'         => 'markokitic@gmail.rs',
            'password'      => Hash::make('user'),
            'telefon'       => '0653456789',
            'adresa'        => 'Niš',
            'role'          => 'user',
            'restaurant_id' => null,
            'active'        => 1,
        ]);
    }
}
