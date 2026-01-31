<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_status', function (Blueprint $table) {

            // dodaj restaurant_id
            $table->unsignedBigInteger('restaurant_id')
                  ->after('id')
                  ->nullable();

            // (opciono ali preporučeno) foreign key
            $table->foreign('restaurant_id')
                  ->references('id')
                  ->on('restaurants')
                  ->cascadeOnDelete();

            // index za brže upite
            $table->index(['restaurant_id']);
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_status', function (Blueprint $table) {

            $table->dropForeign(['restaurant_id']);
            $table->dropIndex(['restaurant_id']);
            $table->dropColumn('restaurant_id');
        });
    }
};
