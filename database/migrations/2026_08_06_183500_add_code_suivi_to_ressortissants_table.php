<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Exécute la migration pour ajouter le champ code_suivi.
     */
    public function up(): void
    {
        Schema::table('ressortissants', function (Blueprint $table) {
            $table->string('code_suivi')->unique()->nullable()->after('id');
        });

        // Remplissage automatique des fiches existantes n'ayant pas de code_suivi
        $ressortissants = DB::table('ressortissants')->whereNull('code_suivi')->get();
        foreach ($ressortissants as $r) {
            $code = 'REC-2026-' . str_pad((string) $r->id, 5, '0', STR_PAD_LEFT);
            DB::table('ressortissants')->where('id', $r->id)->update(['code_suivi' => $code]);
        }
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        Schema::table('ressortissants', function (Blueprint $table) {
            $table->dropColumn('code_suivi');
        });
    }
};
