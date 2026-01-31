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
        Schema::create('restaurant_contacts', function (Blueprint $table) {

            $table->id();

            // 🔗 veza sa restoranom
            $table->foreignId('restaurant_id')
                ->constrained('restaurants')
                ->cascadeOnDelete();

            // 📍 kontakt podaci
            $table->string('address');
            $table->string('phone');
            $table->string('email')->nullable();

            // ⏰ radno vreme (tekstualno)
            $table->string('working_hours')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_contacts');
    }
};
