<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passations', function (Blueprint $table) {
            // On ajoute la colonne avec 'fr' par défaut
            $table->string('langue', 5)->default('fr')->after('id_beneficiaire');
        });
    }

    public function down(): void
    {
        Schema::table('passations', function (Blueprint $table) {
            $table->dropColumn('langue');
        });
    }
};
