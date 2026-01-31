<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('radno_vreme', function (Blueprint $table) {
            $table->id();

            // 0 = nedelja, 1 = ponedeljak ... 6 = subota
            $table->tinyInteger('dan');

            // vreme otvaranja i zatvaranja
            $table->time('otvara_se');
            $table->time('zatvara_se');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('radno_vreme');
    }
};
