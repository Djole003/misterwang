<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review; 

class ReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::create([
            'user_id' => 1, // ID korisnika koji ostavlja recenziju
            'rating' => 5, // Ocena (1-5)
            'comment' => 'Odličan proizvod, mnogo mi se dopao!', // Komentar
        ]);

        Review::create([
            'user_id' => 2,
            'rating' => 4,
            'comment' => 'Vrlo dobar, ali mogao je da bude malo bolji.',
        ]);

        Review::create([
            'user_id' => 3,
            'rating' => 3,
            'comment' => 'Nije loše, ali ništa posebno.',
        ]);
    }
}
