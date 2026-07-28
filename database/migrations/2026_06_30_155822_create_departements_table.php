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
        Schema::create('departements', function (Blueprint $table) {
            $table->id();
            $table->string('cod_dep')->unique(); // code officiel ANStat, cible des FK sous-préfectures
            $table->string('nom_dep');
            $table->string('cod_reg'); // lien vers la région parente (par code ANStat)
            $table->string('annee');
            $table->timestamps();

            $table->foreign('cod_reg')->references('cod_reg')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departements');
    }
};
