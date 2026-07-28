<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute la migration pour créer la table.
     */
    public function up(): void
    {
        Schema::create('ressortissants', function (Blueprint $table) {
            $table->id();

            // Un ressortissant peut être lié à un compte utilisateur (facultatif et unique)
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            // Informations d'identité (Champs OBLIGATOIRES: nom, prenom, sexe)
            $table->string('nom');
            $table->string('prenom');
            $table->string('sexe'); // Ex: 'M' ou 'F'

            // Informations d'identité (Champs NULLABLES)
            $table->string('telephone')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('famille')->nullable(); // Nom de famille ou groupe familial

            // Rattachement administratif (Champs NULLABLES)
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('departement_id')->nullable()->constrained('departements')->nullOnDelete();
            $table->foreignId('sous_prefecture_id')->nullable()->constrained('sous_prefectures')->nullOnDelete();

            // Rattachement coutumier DÉNORMALISÉ (Champs NULLABLES)
            $table->foreignId('canton_id')->nullable()->constrained('cantons')->nullOnDelete();
            $table->foreignId('tribu_id')->nullable()->constrained('tribus')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->constrained('villages')->nullOnDelete();

            // Adresse de résidence intégrée
            $table->foreignId('pays_id')->nullable()->constrained('pays')->nullOnDelete();
            $table->string('pays')->default('Côte d\'Ivoire');
            $table->string('ville')->nullable();
            $table->string('quartier')->nullable();
            $table->string('adresse')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('ressortissants');
    }
};
