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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('cod_reg')->unique(); // code officiel ANStat, cible des FK départements
            $table->string('nom_reg');
            $table->string('cod_dist'); // lien vers le district parent (par code ANStat)
            $table->string('annee');
            $table->timestamps();

            $table->foreign('cod_dist')->references('code_district')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
