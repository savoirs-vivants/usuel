<?php

// Migration de création de la table tracking.
// Enregistre le comportement fin de l'utilisateur question par question :
// temps de réponse, clics, hésitations, mouvements de souris.
// Ces données ne sont collectées que si le bénéficiaire a donné son consentement
// (passations.consentement_recherche = true).
//
// Pas de timestamps Eloquent (created_at/updated_at) car l'horodatage est implicite
// via la passation parente. L'insertion est faite en temps réel, pas en batch.
//
// ⚠️ Si la table existe déjà (installation mise à jour), la migration est ignorée.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tracking')) {
            return;
        }

        Schema::create('tracking', function (Blueprint $table) {
            $table->id();

            // Nullable en insertion : la passation n'existe pas encore au moment où
            // le tracking est créé (elle est créée à la fin du questionnaire).
            // Le rattachement est fait en masse dans QuestionnaireRun::terminer().
            $table->foreignId('id_passation')
                  ->nullable()
                  ->constrained('passations')
                  ->nullOnDelete();

            $table->foreignId('id_question')
                  ->constrained('questions')
                  ->cascadeOnDelete();

            // Position de la question dans la session (1-based)
            $table->unsignedInteger('position');

            // Temps total passé sur la question (millisecondes)
            $table->float('temps_total_ms')->default(0);

            // Temps avant le premier clic de réponse (millisecondes)
            $table->float('latence_ms')->default(0);

            $table->unsignedInteger('nb_clics')->default(0);

            // Nombre de fois où l'utilisateur a changé de réponse
            $table->unsignedInteger('nb_changements')->default(0);

            // Clics en dehors des zones de réponse (indicateur de désorientation)
            $table->unsignedInteger('nb_clics_hors_cible')->default(0);

            // Nombre d'interruptions détectées (fenêtre réduite, onglet changé, etc.)
            $table->unsignedInteger('nb_pauses')->default(0);

            // Tableau JSON de coordonnées [x, y, t] enregistrées périodiquement.
            // Peut être null si le tracking souris n'est pas supporté par le navigateur.
            $table->json('suivi_souris')->nullable();

            // Poids de la réponse donnée (-1 à +1, selon la pondération de la question)
            $table->float('resultat')->nullable();

            // Pas de timestamps Eloquent — voir commentaire en tête de fichier.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking');
    }
};
