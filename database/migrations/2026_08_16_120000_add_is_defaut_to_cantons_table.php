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
        Schema::table('cantons', function (Blueprint $table) {
            $table->boolean('is_defaut')->default(false)->after('sous_prefecture_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cantons', function (Blueprint $table) {
            $table->dropColumn('is_defaut');
        });
    }
};
