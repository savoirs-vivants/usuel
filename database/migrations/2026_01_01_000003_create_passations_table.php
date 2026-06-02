<?php

// Migration de création de la table passations.
// Une passation représente une session complète du questionnaire pour un bénéficiaire,
// incluant les scores par compétence et les métadonnées de passation.
//
// Colonnes ajoutées ultérieurement par des migrations ALTER dédiées :
//   - langue    → 2026_03_11_111835_add_langue_to_passations_table
//   - audio     → 2026_03_12_083006_add_audio_to_passations_table
//   - scenario  → 2026_05_12_000001_add_scenario_modules_to_passations_table
//   - modules   → 2026_05_12_000001_add_scenario_modules_to_passations_table
//
// ⚠️ Si la table existe déjà (installation mise à jour), la migration est ignorée.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('passations')) {
            return;
        }

        Schema::create('passations', function (Blueprint $table) {
            $table->id();

            // Référence au bénéficiaire évalué. Cascade delete : supprimer une passation
            // peut orpheliner le bénéficiaire (géré applicativement dans PassationController).
            $table->foreignId('id_beneficiaire')
                  ->constrained('beneficiaires')
                  ->cascadeOnDelete();

            // Référence au travailleur social qui a conduit la passation.
            $table->foreignId('id_travailleur')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Scores bruts stockés en JSON :
            // { "Resilience": 3.5, "EC": -1.0, "CSDLEN": 2.0, "CT": 4.0, "TDLinfo": 1.5, "CDC": -0.5 }
            // Chaque valeur va de -5 à +5 (5 questions × poids max ±1).
            $table->json('score');

            $table->date('date');

            // Consentement explicite à l'utilisation des données comportementales
            // (tracking souris, clics, temps) à des fins de recherche.
            $table->boolean('consentement_recherche')->default(false);

            // Ordre de présentation des questions : 'fixe' | 'aleatoire' | 'semi_aleatoire' | 'carre_latin'
            $table->string('mode_ordre')->default('fixe');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passations');
    }
};
