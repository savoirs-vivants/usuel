<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Models\Tracking;
use App\Models\Passation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class ExportTrackingToDrive extends Command
{
    protected $signature = 'drive:export';
    protected $description = 'Exporte les données (avec consentement recherche) vers Seafile Unistra';

    public function handle()
    {
        $this->info('Début de la génération des rapports (Filtre consentement actif)...');

        // --- 1. PRÉPARATION DES COMPÉTENCES (DIMENSIONS) ---
        $firstPassation = Passation::where('consentement_recherche', true)->whereNotNull('score')->first();
        $dimKeys = [];
        if ($firstPassation) {
            $scores = is_string($firstPassation->score) ? json_decode($firstPassation->score, true) : $firstPassation->score;
            if (is_array($scores)) {
                $dimKeys = array_keys($scores);
            }
        }

        // --- 2. GÉNÉRATION EXCEL : TRACKING (COMPORTEMENT) ---
        $spreadsheetTracking = new Spreadsheet();
        $sheetT = $spreadsheetTracking->getActiveSheet();
        $sheetT->setTitle('Comportement');

        $headersT = ['id', 'id_passation', 'id_question', 'position', 'temps_total_ms', 'latence_ms', 'nb_clics', 'nb_changements', 'nb_clics_hors_cible', 'nb_pauses', 'resultat', 'suivi_souris', 'timestamp'];
        $sheetT->fromArray($headersT, NULL, 'A1', true);
        $sheetT->getStyle('A1:M1')->getFont()->setBold(true);

        $rowT = 2;
        Tracking::whereHas('passation', function($query) {
            $query->where('consentement_recherche', true);
        })->select($headersT)->chunk(500, function ($trackings) use ($sheetT, &$rowT) {
            foreach ($trackings as $tracking) {
                $sheetT->fromArray($tracking->toArray(), null, 'A' . $rowT, true);
                $rowT++;
            }
        });

        foreach (range('A', 'M') as $col) { $sheetT->getColumnDimension($col)->setAutoSize(true); }
        $sheetT->getColumnDimension('L')->setAutoSize(false)->setWidth(60);
        $sheetT->getStyle('L2:L' . ($rowT - 1))->getAlignment()->setWrapText(true);

        $excelTrackingPath = storage_path('app/export_comportement_global.xlsx');
        (new Xlsx($spreadsheetTracking))->save($excelTrackingPath);


        // --- 3. GÉNÉRATION EXCEL : SOCIO-DÉMOGRAPHIQUE (PASSATIONS) ---
        $spreadsheetSocio = new Spreadsheet();
        $sheetS = $spreadsheetSocio->getActiveSheet();
        $sheetS->setTitle('Socio-Demographique');

        $headersS = ['ID Passation', 'ID Bénéficiaire', 'Langue', 'Mode Audio', 'ID Travailleur'];
        foreach($dimKeys as $key) { $headersS[] = "Score " . ucfirst($key); }
        $headersS[] = 'Score Total (/30)';
        $headersS[] = 'Date';
        $headersS[] = 'Ordre des blocs';

        $sheetS->fromArray($headersS, NULL, 'A1', true);
        $lastCol = $sheetS->getHighestColumn();
        $sheetS->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);

        $rowS = 2;
        Passation::where('consentement_recherche', true)->chunk(200, function ($passations) use ($sheetS, &$rowS, $dimKeys) {
            foreach ($passations as $p) {
                $scores = is_string($p->score) ? json_decode($p->score, true) : $p->score;
                $scores = is_array($scores) ? $scores : [];

                $rowData = [
                    $p->id,
                    $p->id_beneficiaire,
                    $p->langue ?: 'Français',
                    $p->audio ? 'Oui' : 'Non',
                    $p->id_travailleur,
                ];

                foreach ($dimKeys as $key) {
                    $rowData[] = $scores[$key] ?? 0;
                }

                $totalScore = array_sum($scores);
                $rowData[] = round($totalScore, 2);
                $rowData[] = Carbon::parse($p->created_at)->format('d/m/Y H:i');
                $rowData[] = $p->mode_ordre;

                $sheetS->fromArray($rowData, null, 'A' . $rowS, true);
                $rowS++;
            }
        });

        foreach (range('A', $lastCol) as $col) { $sheetS->getColumnDimension($col)->setAutoSize(true); }

        $excelSocioPath = storage_path('app/export_socio_demo_global.xlsx');
        (new Xlsx($spreadsheetSocio))->save($excelSocioPath);


        // --- 4. ENVOI SUR SEAFILE UNISTRA ---
        $seafileUrl = rtrim(env('SEAFILE_URL'), '/');
        $token = env('SEAFILE_TOKEN');

        $fichiersAEnvoyer = [
            ['path' => $excelTrackingPath, 'name' => 'export_comportement_global.xlsx'],
            ['path' => $excelSocioPath,    'name' => 'export_socio_demo_global.xlsx'],
        ];

        foreach ($fichiersAEnvoyer as $fichier) {
            $this->info("Envoi de {$fichier['name']}...");

            $linkResponse = Http::withHeaders(['Authorization' => 'Token ' . $token])
                ->get("{$seafileUrl}/api/v2.1/via-repo-token/upload-link/");

            if ($linkResponse->successful()) {
                $uploadLink = trim($linkResponse->body(), " \t\n\r\0\x0B\"");
                Http::withHeaders(['Authorization' => 'Token ' . $token])
                    ->attach('file', file_get_contents($fichier['path']), $fichier['name'])
                    ->post($uploadLink, ['parent_dir' => '/', 'replace' => 1]);

                $this->info("✅ {$fichier['name']} envoyé !");
            }

            File::delete($fichier['path']);
        }

        $this->info('Export terminé. Seules les données avec consentement ont été traitées.');
    }
}
