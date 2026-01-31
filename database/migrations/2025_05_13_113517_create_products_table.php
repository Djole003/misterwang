<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Auto increment id
            $table->string('name'); // Naziv proizvoda
            $table->text('description')->nullable(); // Opis proizvoda, može biti null
            $table->decimal('price', 8, 2); // Cena proizvoda (do 999,999.99)
            $table->string('category'); // Kategorija proizvoda
            $table->string('image_path')->nullable(); // Putanja do slike proizvoda
            $table->timestamps(); // Kreira created_at i updated_at kolone
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
