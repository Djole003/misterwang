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
        Schema::table('radno_vreme', function ($table) {
            $table->time('otvara_se')->nullable()->change();
            $table->time('zatvara_se')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radno_vreme', function ($table) {
            $table->time('otvara_se')->nullable(false)->change();
            $table->time('zatvara_se')->nullable(false)->change();
        });
    }
};
