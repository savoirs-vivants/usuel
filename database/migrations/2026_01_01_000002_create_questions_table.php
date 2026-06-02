<?php

// Migration de création de la table questions.
// Chaque question appartient à une compétence (categorie) et porte ses choix
// de réponse en JSON, chaque choix ayant un poids pondéré (-1 à +1).
//
// Note : la colonne `active` est ajoutée par la migration
// 2026_03_06_083001_add_active_to_questions_table.php (déjà existante).
// ⚠️ Si la table existe déjà (installation mise à jour), la migration est ignorée.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('questions')) {
            return;
        }

        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->text('intitule');

            // Chemin vers l'image illustrant la question (stockée dans storage/).
            // Nullable : toutes les questions n'ont pas d'image.
            $table->string('image')->nullable();

            // Clé de compétence : 'Resilience' | 'EC' | 'CSDLEN' | 'CT' | 'TDLinfo' | 'CDC'
            $table->string('categorie');

            // Lettre de la réponse correcte (A, B, C, D — 'E' est réservé à "Je ne sais pas")
            $table->string('reponse_correcte', 2);

            // Structure JSON : { "A": {"texte": "...", "poids": 1}, "B": {...}, "E": {"texte": "Je ne sais pas", "poids": 0} }
            // Le poids va de -1 à +1 et sert au calcul pondéré du score par compétence.
            $table->json('choix');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
