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

    protected $description = 'Exporte la table Tracking en Excel ET en CSV, puis les envoie sur kDrive Infomaniak';

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
        $writerXlsx = new Xlsx($spreadsheet);
        $writerXlsx->save($excelPath);
        $this->info('Fichier Excel généré.');

        // --- 2. GÉNÉRATION CSV (.csv) ---
        $csvName = 'export_tracking_global.csv';
        $csvPath = storage_path('app/' . $csvName);
        $writerCsv = new Csv($spreadsheet);
        $writerCsv->setDelimiter(';');
        $writerCsv->setUseBOM(true);
        $writerCsv->save($csvPath);
        $this->info('Fichier CSV généré.');


        // --- 3. ENVOI DES DEUX FICHIERS SUR KDRIVE ---
        $token = env('INFOMANIAK_API_TOKEN');
        $driveId = env('INFOMANIAK_DRIVE_ID');
        $folderId = env('INFOMANIAK_FOLDER_ID');

        $fichiersAEnvoyer = [
            [
                'path' => $excelPath,
                'name' => $excelName,
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ],
            [
                'path' => $csvPath,
                'name' => $csvName,
                'mime' => 'text/csv'
            ]
        ];

        foreach ($fichiersAEnvoyer as $fichier) {
            $this->info("Envoi de {$fichier['name']} en cours...");

            $fileContent = file_get_contents($fichier['path']);
            $fileSize = filesize($fichier['path']);

            $parametres = http_build_query([
                'directory_id' => $folderId,
                'file_name'    => $fichier['name'],
                'total_size'   => $fileSize,
                'conflict'     => 'version'
            ]);

            $url = "https://api.infomaniak.com/3/drive/{$driveId}/upload?" . $parametres;

            $response = Http::withToken($token)
                ->withBody($fileContent, $fichier['mime'])
                ->post($url);

            if ($response->successful()) {
                $this->info("✅ {$fichier['name']} envoyé avec succès !");
            } else {
                $this->error("❌ Erreur pour {$fichier['name']} : " . $response->body());
            }

            File::delete($fichier['path']);
        }

        $this->info('Toutes les opérations sont terminées !');
    }
}
