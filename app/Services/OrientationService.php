<?php

namespace App\Services;

use App\Models\Passation;

/**
 * OrientationService — Moteur d'orientation post-test Usuel.
 *
 * Principe général :
 *   1. Les scores bruts de la passation (pondérés de -5 à +5 par catégorie)
 *      sont normalisés sur une échelle 0–5 pour chaque compétence.
 *   2. Les 6 blocs opérationnels sont calculés comme moyennes des compétences associées.
 *   3. Un arbre de décision à priorités fixes détermine le parcours (A–H).
 *   4. Les modules de formation recommandés et la structure d'orientation sont déduits du parcours.
 *
 * Cette logique est centralisée ici pour ne pas disperser les règles métier dans les vues
 * ou les contrôleurs, et pour être appelée à la fois par le questionnaire en temps réel
 * et par la commande de backfill (php artisan orientation:compute).
 */
class OrientationService
{
    /**
     * Clés des 6 compétences telles qu'elles sont stockées dans passations.score (JSON).
     */
    public const CATEGORIES = ['Resilience', 'EC', 'CSDLEN', 'CT', 'TDLinfo', 'CDC'];

    /**
     * Tranches d'âge considérées comme "jeunes" pour le parcours G.
     * Correspond aux valeurs stockées dans beneficiaires.age.
     */
    public const YOUNG_AGE_GROUPS = ['moins_18', '18_25', '26_35'];

    /**
     * Noms complets des parcours (utilisés dans les vues et le certificat).
     */
    public const PARCOURS_LABELS = [
        'A' => 'Grand débutant numérique',
        'B' => 'Autonomie fragile administrative',
        'C' => 'Insertion professionnelle numérique',
        'D' => 'Vigilance numérique',
        'E' => 'Création et valorisation numérique',
        'F' => 'Consolidation – Autonome à renforcer',
        'G' => 'Usage intuitif mais fragile',
        'H' => 'Autonomie confirmée',
    ];

    /**
     * Structure d'orientation recommandée par parcours.
     *
     * Logique :
     *  - A, B, C → accompagnement renforcé par un conseiller numérique ou en APP
     *              (personnes avec des lacunes importantes qui nécessitent un suivi professionnel)
     *  - D, E, F, G → stages Savoirs Vivants
     *              (personnes avec un niveau suffisant pour profiter d'un stage thématique)
     *  - H → aucun accompagnement nécessaire (niveau expert, orientation GRETA si souhaité)
     */
    public const ORIENTATIONS = [
        'A' => 'APP / Conseiller Numérique France Services',
        'B' => 'APP / Conseiller Numérique France Services',
        'C' => 'APP / Conseiller Numérique France Services',
        'D' => 'Savoirs Vivants – inscription à un stage',
        'E' => 'Savoirs Vivants – inscription à un stage',
        'F' => 'Savoirs Vivants – inscription à un stage',
        'G' => 'Savoirs Vivants – inscription à un stage',
        'H' => 'Aucun accompagnement nécessaire (certification GRETA possible)',
    ];

    /**
     * Modules de formation prioritaires par parcours.
     * Les blocs correspondent aux 6 blocs opérationnels du référentiel Usuel.
     */
    public const MODULES = [
        'A' => ['Bloc 1 – Environnement numérique'],
        'B' => ['Bloc 2 – Démarches administratives', 'Bloc 1 – Environnement numérique'],
        'C' => ['Bloc 1 – Environnement numérique', 'Bloc 2 – Démarches administratives', 'Bloc 4 – Communication'],
        'D' => ['Bloc 3 – Sécurité numérique', 'Bloc 5 – Recherche & Information'],
        'E' => ['Bloc 6 – Création de contenus'],
        'F' => ['Bloc 1 – Environnement numérique', 'Bloc 4 – Communication'],
        'G' => ['Bloc 2 – Démarches administratives', 'Bloc 5 – Recherche & Information'],
        'H' => ['Certification GRETA'],
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Calculs de scores
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Normalise un score brut pondéré (-5 à +5) vers l'échelle d'orientation (0 à 5).
     *
     * Pourquoi cette formule ?
     *   Les scores bruts utilisent des poids de réponse (-1, -0.5, 0, +0.5, +1).
     *   L'arbre d'orientation raisonne sur une échelle 0–5 (faible ≤ 2, intermédiaire = 3, maîtrise ≥ 4).
     *   La formule round((raw + 5) / 2) produit exactement cette correspondance :
     *     -5 → 0, -3 → 1, -1 → 2, 0 → 3 (arrondi), +3 → 4, +5 → 5
     */
    public static function normalizeScore(float $raw): int
    {
        return max(0, min(5, (int) round(($raw + 5.0) / 2.0)));
    }

    /**
     * Calcule les 6 scores de blocs opérationnels à partir des scores normalisés par compétence.
     *
     * Correspondance blocs ↔ compétences (référentiel Usuel) :
     *   Bloc 1 – Prendre en main un environnement numérique → CT + Résilience
     *   Bloc 2 – Réaliser des démarches administratives      → CT + TDLinfo
     *   Bloc 3 – Sécuriser ses usages numériques             → EC seul
     *   Bloc 4 – Communiquer et interagir                    → CSDLEN seul
     *   Bloc 5 – Chercher et comprendre l'information        → TDLinfo + EC
     *   Bloc 6 – Produire, partager et créer                 → CDC seul
     *
     * @param  array<string,int> $normalized  Scores 0–5 indexés par clé de compétence
     * @return array<int,int>                 Scores 0–5 indexés par numéro de bloc (1–6)
     */
    public static function calculateBlocks(array $normalized): array
    {
        return [
            1 => (int) round(($normalized['CT']      + $normalized['Resilience']) / 2),
            2 => (int) round(($normalized['CT']      + $normalized['TDLinfo'])    / 2),
            3 => $normalized['EC'],
            4 => $normalized['CSDLEN'],
            5 => (int) round(($normalized['TDLinfo'] + $normalized['EC'])         / 2),
            6 => $normalized['CDC'],
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Arbre de décision
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Détermine le parcours d'orientation (A–H) selon l'arbre de décision Usuel.
     *
     * L'ordre des vérifications est intentionnel et correspond à une priorité politique :
     *   1. A – Exclusion totale (urgence pédagogique maximale)
     *   2. B – Accès aux droits (priorité politique publique, non-recours)
     *   3. H – Expert (pas de besoin d'accompagnement)
     *   4. E – Création (usage passif malgré bon niveau global)
     *   5. D – Vigilance (risques sécurité/désinformation)
     *   6. C – Insertion professionnelle
     *   7. G – Jeune usage intuitif (nécessite la tranche d'âge)
     *   8. F – Consolidation senior (parcours par défaut)
     *
     * @param  int          $total     Score total normalisé (0–30)
     * @param  array<int,int> $blocks  Scores de blocs (0–5 chacun)
     * @param  string|null  $ageGroup  Tranche d'âge du bénéficiaire (ex: '18_25', 'plus_65')
     */
    public static function determineParcours(int $total, array $blocks, ?string $ageGroup = null): string
    {
        // ── A : score global très faible, difficultés majeures sur l'ensemble des compétences
        if ($total <= 9) {
            return 'A';
        }

        // ── B : bloc administratif faible → risque de non-recours aux droits (priorité politique)
        if ($blocks[2] <= 2) {
            return 'B';
        }

        // ── H : aucun bloc faible, autonomie numérique confirmée
        if ($total >= 25) {
            return 'H';
        }

        // ── E : bon niveau global mais création absente → passage de l'usage passif à l'actif
        if ($blocks[6] <= 2 && $total >= 18) {
            return 'E';
        }

        // ── D : failles en esprit critique ou traitement de l'info → exposition aux risques
        if ($blocks[3] <= 2 || $blocks[5] <= 2) {
            return 'D';
        }

        // ── C : niveau insuffisant pour l'emploi numérique
        if ($total >= 15 && $total <= 19) {
            return 'C';
        }

        // ── G : profil jeune avec fragilités administratives/info (smartphone OK, démarches KO)
        // Note : à ce stade, bloc 2 > 2 (sinon B aurait été retourné), donc on élargit
        // "faible" à ≤ 3 (intermédiaire inclus) pour capturer les jeunes en difficulté partielle.
        $isYoung = in_array($ageGroup, self::YOUNG_AGE_GROUPS, true);
        if ($isYoung && $blocks[2] <= 3) {
            return 'G';
        }

        // ── F : par défaut — bons résultats globaux mais consolidation nécessaire (profil senior)
        return 'F';
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Point d'entrée principal
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Calcule et persiste le scénario et les modules pour une passation.
     *
     * Appelé par :
     *  - QuestionnaireRun::terminer() après la création de chaque nouvelle passation
     *  - php artisan orientation:compute pour le backfill des passations existantes
     *
     * La passation doit avoir sa relation beneficiaire eager-loaded (ou être accessible)
     * pour que la tranche d'âge soit prise en compte dans la décision G/F.
     */
    public static function compute(Passation $passation): void
    {
        $raw = $passation->score ?? [];

        $normalized = [];
        foreach (self::CATEGORIES as $cat) {
            $normalized[$cat] = self::normalizeScore((float) ($raw[$cat] ?? 0.0));
        }

        $blocks = self::calculateBlocks($normalized);

        $total = array_sum($normalized);

        $ageGroup = $passation->beneficiaire->age ?? null;

        $parcours = self::determineParcours($total, $blocks, $ageGroup);

        $passation->update([
            'scenario' => $parcours,
            'modules'  => self::MODULES[$parcours] ?? [],
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Accesseurs pour les vues
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Retourne les scores normalisés (0–5) par compétence depuis les scores bruts d'une passation.
     * Utile dans les vues pour afficher les niveaux sans refaire le calcul.
     *
     * @return array<string,int>
     */
    public static function getNormalizedScores(array $rawScores): array
    {
        $result = [];
        foreach (self::CATEGORIES as $cat) {
            $result[$cat] = self::normalizeScore((float) ($rawScores[$cat] ?? 0.0));
        }
        return $result;
    }

    /**
     * Retourne le niveau textuel d'un score normalisé (0–5).
     * Utilisé dans le certificat pour afficher "Faible", "Intermédiaire" ou "Maîtrise".
     */
    public static function getLevelLabel(int $normalizedScore): string
    {
        if ($normalizedScore <= 2) return 'Faible';
        if ($normalizedScore === 3) return 'Intermédiaire';
        return 'Maîtrise';
    }

    /**
     * Retourne la couleur CSS associée à un niveau (pour les badges dans les vues).
     */
    public static function getLevelColor(int $normalizedScore): string
    {
        if ($normalizedScore <= 2) return 'red';
        if ($normalizedScore === 3) return 'amber';
        return 'green';
    }
}
