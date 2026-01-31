<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            if (Schema::hasColumn('products', 'category')) {
                $table->dropColumn('category');
            }

        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Ako želiš da može da se vrati
            $table->string('category')->nullable();
        });
    }
};
