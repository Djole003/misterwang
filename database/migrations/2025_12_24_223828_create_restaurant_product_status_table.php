<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_product_status', function (Blueprint $table) {
            $table->id();

            $table->foreignId('restaurant_id')
                ->constrained('restaurants')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // true = dostupno, false = nema na stanju
            $table->boolean('is_available')->default(true);

            $table->timestamps();

            // Jedan zapis po (restoran + proizvod)
            $table->unique(['restaurant_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_product_status');
    }
};
