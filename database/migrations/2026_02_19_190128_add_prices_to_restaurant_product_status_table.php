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
        Schema::table('restaurant_product_status', function (Blueprint $table) {
            $table->decimal('price_delivery', 8, 2)
                  ->nullable()
                  ->after('is_available');

            $table->decimal('price_takeaway', 8, 2)
                  ->nullable()
                  ->after('price_delivery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_product_status', function (Blueprint $table) {
            $table->dropColumn(['price_delivery', 'price_takeaway']);
        });
    }
};
