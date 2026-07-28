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
        Schema::create('tribus', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('canton_id')->constrained(); // On crée une clé étrangère vers la table des cantons, pour relier chaque tribu à un canton existant.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tribus');
    }
};
