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
    Schema::create('sous_prefectures', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('anstat_id')->unique(); // id fourni par l'API, seul champ réellement unique
    $table->string('cod_sp'); // non unique, même au sein d'un département
    $table->string('nom_sp');
    $table->string('cod_dep'); // lien vers le département parent (par code ANStat)
    $table->string('annee');
    $table->timestamps();

    $table->foreign('cod_dep')->references('cod_dep')->on('departements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sous_prefectures');
    }
};
