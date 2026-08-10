<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute la migration pour ajouter la colonne village_nom.
     */
    public function up(): void
    {
        Schema::table('ressortissants', function (Blueprint $table) {
            $table->string('village_nom')->nullable()->after('village_id');
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        Schema::table('ressortissants', function (Blueprint $table) {
            $table->dropColumn('village_nom');
        });
    }
};
