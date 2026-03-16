<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Models\Tracking;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class ExportTrackingToDrive extends Command
{
    protected $signature = 'drive:export';

    protected $description = 'Exporte la table Tracking en Excel ET en CSV vers Seafile Unistra';

    public function handle()
    {
        $this->info('Début de la récupération des données...');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['id', 'id_passation', 'id_question', 'position', 'temps_total_ms', 'latence_ms', 'nb_clics', 'nb_changements', 'nb_clics_hors_cible', 'nb_pauses', 'resultat', 'suivi_souris', 'timestamp'];

        $sheet->fromArray($headers, NULL, 'A1', true);
        $sheet->getStyle('A1:M1')->getFont()->setBold(true);

        $row = 2;
        Tracking::select($headers)->chunk(500, function ($trackings) use ($sheet, &$row) {
            foreach ($trackings as $tracking) {
                $sheet->fromArray($tracking->toArray(), null, 'A' . $row, true);
                $row++;
            }
        });

        // --- STYLE EXCEL ---
        $colonnesAuto = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'M'];
        foreach ($colonnesAuto as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getColumnDimension('L')->setAutoSize(false);
        $sheet->getColumnDimension('L')->setWidth(60);
        $sheet->getStyle('L2:L' . ($row - 1))->getAlignment()->setWrapText(true);


        // --- 1. GÉNÉRATION EXCEL (.xlsx) ---
        $excelName = 'export_tracking_global.xlsx';
        $excelPath = storage_path('app/' . $excelName);
        (new Xlsx($spreadsheet))->save($excelPath);

        // --- 2. GÉNÉRATION CSV (.csv) ---
        $csvName = 'export_tracking_global.csv';
        $csvPath = storage_path('app/' . $csvName);
        $writerCsv = new Csv($spreadsheet);
        $writerCsv->setDelimiter(';');
        $writerCsv->setUseBOM(true);
        $writerCsv->save($csvPath);

        $this->info('Fichiers locaux générés.');

        // --- 2. ENVOI SUR SEAFILE UNISTRA ---
        $seafileUrl = rtrim(env('SEAFILE_URL'), '/');
        $repoId     = env('SEAFILE_REPO_ID');
        $seafileDir = env('SEAFILE_DIR', '/');
        $token      = env('SEAFILE_TOKEN');

        $fichiersAEnvoyer = [
            ['path' => $excelPath, 'name' => $excelName],
            ['path' => $csvPath,   'name' => $csvName],
        ];

        foreach ($fichiersAEnvoyer as $fichier) {
            $this->info("Envoi de {$fichier['name']}...");

            // ÉTAPE 1 : Obtenir le lien d'upload via le repo token
            $linkResponse = Http::withHeaders([
                'Authorization' => 'Token ' . $token,
            ])->get("{$seafileUrl}/api/v2.1/via-repo-token/upload-link/");

            if (! $linkResponse->successful()) {
                $this->error("❌ Impossible d'obtenir le lien d'upload : " . $linkResponse->status() . ' - ' . $linkResponse->body());
                File::delete($fichier['path']);
                continue;
            }

            $uploadLink = trim($linkResponse->body(), " \t\n\r\0\x0B\"");
            $this->info("Lien d'upload obtenu : {$uploadLink}");

            // ÉTAPE 2 : Envoyer le fichier
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $token,
            ])
                ->attach('file', file_get_contents($fichier['path']), $fichier['name'])
                ->post($uploadLink, [
                    'parent_dir' => '/',
                    'replace'    => 1,
                ]);

            if ($response->successful()) {
                $this->info("✅ {$fichier['name']} envoyé !");
            } else {
                $this->error("❌ Erreur upload {$fichier['name']} : " . $response->status() . ' - ' . $response->body());
            }

            File::delete($fichier['path']);
        }

        $this->info('Terminé !');
    }
}
