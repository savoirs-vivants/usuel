<?php

namespace App\Console\Commands;

use App\Models\Passation;
use App\Services\OrientationService;
use Illuminate\Console\Command;

/**
 * Commande de backfill des orientations.
 *
 * Contexte : avant l'introduction d'OrientationService, les champs scenario et modules
 * de la table passations étaient null. Cette commande recalcule et renseigne ces champs
 * pour toutes les passations existantes sans toucher aux nouveaux tests (qui sont traités
 * en temps réel dans QuestionnaireRun::terminer()).
 *
 * Usage :
 *   php artisan orientation:compute              → toutes les passations sans scénario
 *   php artisan orientation:compute --all        → toutes les passations (recalcul forcé)
 *   php artisan orientation:compute --id=42      → une passation spécifique
 */
class ComputeOrientations extends Command
{
    protected $signature = 'orientation:compute
                            {--all    : Recalculer même les passations qui ont déjà un scénario}
                            {--id=    : ID d\'une seule passation à traiter}';

    protected $description = 'Calcule et persiste le scénario d\'orientation et les modules recommandés pour les passations existantes';

    public function handle(): int
    {
        $query = Passation::with('beneficiaire');

        if ($this->option('id')) {
            $query->where('id', (int) $this->option('id'));
        } elseif (!$this->option('all')) {
            $query->whereNull('scenario');
        }

        $passations = $query->get();
        $total      = $passations->count();

        if ($total === 0) {
            $this->info('Aucune passation à traiter.');
            return self::SUCCESS;
        }

        $this->info("Traitement de {$total} passation(s)...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $errors = 0;
        foreach ($passations as $passation) {
            try {
                OrientationService::compute($passation);
            } catch (\Throwable $e) {
                $errors++;
                $this->newLine();
                $this->error("Passation #{$passation->id} : {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($errors > 0) {
            $this->warn("{$errors} erreur(s) rencontrée(s). Les autres passations ont été traitées.");
        } else {
            $this->info("✓ {$total} passation(s) traitée(s) avec succès.");
        }

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
