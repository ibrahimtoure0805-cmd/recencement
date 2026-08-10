<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute la migration pour ajouter les pièces justificatives et les champs diaspora/locaux.
     */
    public function up(): void
    {
        Schema::table('ressortissants', function (Blueprint $table) {
            // Pièces justificatives et identité
            $table->string('type_piece')->nullable()->after('profession'); // CNI, Passeport, Carte Consulaire, Attestation, Extrait, Autre
            $table->string('numero_piece')->nullable()->after('type_piece');
            $table->string('document_identite_path')->nullable()->after('numero_piece');
            $table->string('justificatif_domicile_path')->nullable()->after('document_identite_path');

            // Rattachement Diaspora & Référent local
            $table->string('consulat_rattachement')->nullable()->after('justificatif_domicile_path');
            $table->string('contact_referent_nom')->nullable()->after('consulat_rattachement');
            $table->string('contact_referent_telephone')->nullable()->after('contact_referent_nom');

            // Informations sociodémographiques complémentaires
            $table->string('situation_matrimoniale')->nullable()->after('contact_referent_telephone'); // celibataire, marie, divorce, veuf
            $table->string('niveau_etude')->nullable()->after('situation_matrimoniale'); // aucun, primaire, secondaire, superieur
            $table->string('statut_occupation')->nullable()->after('niveau_etude'); // chef_menage, membre_foyer, resident_temporaire, autre
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        Schema::table('ressortissants', function (Blueprint $table) {
            $table->dropColumn([
                'type_piece',
                'numero_piece',
                'document_identite_path',
                'justificatif_domicile_path',
                'consulat_rattachement',
                'contact_referent_nom',
                'contact_referent_telephone',
                'situation_matrimoniale',
                'niveau_etude',
                'statut_occupation',
            ]);
        });
    }
};
