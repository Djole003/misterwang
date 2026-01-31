<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('restaurant_status', function (Blueprint $table) {

            // ako već postoji kolona, preskoči
            if (!Schema::hasColumn('restaurant_status', 'restaurant_id')) {
                $table->foreignId('restaurant_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('restaurants')
                    ->cascadeOnDelete();
            }
        });

        // 🧹 OBRIŠI STARE REDOVE SA NULL
        DB::table('restaurant_status')
            ->whereNull('restaurant_id')
            ->delete();
    }

    public function down(): void
    {
        Schema::table('restaurant_status', function (Blueprint $table) {
            if (Schema::hasColumn('restaurant_status', 'restaurant_id')) {
                $table->dropForeign(['restaurant_id']);
                $table->dropColumn('restaurant_id');
            }
        });
    }
};
