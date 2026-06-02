<?php

// Migration de création de la table beneficiaires.
// Cette table stocke le profil sociodémographique de chaque personne
// évaluée (anonyme côté nom si mode anonyme, mais toujours lié à une passation).
// ⚠️ Si la table existe déjà (installation mise à jour), la migration est ignorée.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('beneficiaires')) {
            return;
        }

        Schema::create('beneficiaires', function (Blueprint $table) {
            $table->id();

            $table->string('nom');
            $table->string('prenom');

            // Données socio-démographiques optionnelles — servent aux statistiques
            // agrégées. Nullable car la collecte est conditionnelle au consentement.
            $table->string('genre')->nullable();

            // Tranche d'âge stockée sous forme de slug (ex: '18_25', 'plus_65')
            // plutôt qu'un âge exact, pour respecter la vie privée et simplifier
            // les filtres statistiques.
            $table->string('age')->nullable();

            $table->string('diplome')->nullable();

            // Catégorie Socio-Professionnelle
            $table->string('csp')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiaires');
    }
};
