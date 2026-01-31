<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Napravi user_id nullable
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        // Obriši stari FK ako postoji
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Isključi privremeno FK checks
        DB::statement('ALTER TABLE orders DROP FOREIGN KEY IF EXISTS orders_user_id_foreign;');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Dodaj novi FK sa ON DELETE SET NULL
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade'); // vraća original
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
