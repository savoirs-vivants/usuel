<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tracking;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Livewire\Attributes\Reactive;

class ExportComportementaleModal extends Component
{
    // #[Reactive] permet à Livewire de re-rendre ce composant automatiquement
    // quand ces valeurs changent dans le composant parent, sans déclencher
    // un aller-retour réseau supplémentaire depuis ce composant enfant.
    #[Reactive]
    public array  $selectedAges     = [];

    #[Reactive]
    public array  $selectedGenres   = [];

    #[Reactive]
    public array  $selectedCsps     = [];

    #[Reactive]
    public array  $selectedDiplomes = [];

    #[Reactive]
    public array  $selectedModes    = [];

    #[Reactive]
    public string $timeRange        = 'A';

    #[Reactive]
    public string $customStartDate  = '';

    #[Reactive]
    public string $customEndDate    = '';

    private function baseQuery()
    {
        // On joint passations et beneficiaires ici plutôt que dans chaque méthode
        // pour centraliser la logique de filtrage : un seul endroit à modifier
        // si le schéma évolue.
        $query = Tracking::query()
            ->join('passations', 'tracking.id_passation', '=', 'passations.id')
            ->join('beneficiaires', 'passations.id_beneficiaire', '=', 'beneficiaires.id')
            ->whereNotNull('tracking.id_passation')
            ->where('passations.consentement_recherche', 1);

        // Chaque filtre n'est appliqué que s'il contient des valeurs, ce qui permet
        // d'éviter un WHERE IN () vide qui retournerait zéro résultat de façon silencieuse.
        if (!empty($this->selectedAges))     $query->whereIn('beneficiaires.age',     $this->selectedAges);
        if (!empty($this->selectedGenres))   $query->whereIn('beneficiaires.genre',   $this->selectedGenres);
        if (!empty($this->selectedCsps))     $query->whereIn('beneficiaires.csp',     $this->selectedCsps);
        if (!empty($this->selectedDiplomes)) $query->whereIn('beneficiaires.diplome', $this->selectedDiplomes);
        if (!empty($this->selectedModes))    $query->whereIn('passations.mode_ordre', $this->selectedModes);

        $now = Carbon::now();
        match ($this->timeRange) {
            'J'      => $query->whereDate('passations.created_at', $now->toDateString()),
            'M'      => $query->whereMonth('passations.created_at', $now->month)
                              ->whereYear('passations.created_at',  $now->year),
            'A'      => $query->whereYear('passations.created_at', $now->year),
            'Custom' => $query->whereBetween('passations.created_at', [
                            $this->customStartDate . ' 00:00:00',
                            $this->customEndDate   . ' 23:59:59',
                        ]),
            default  => null,
        };

        return $query;
    }

    private function getPeriodeLabel(): string
    {
        return match ($this->timeRange) {
            'J'      => "Aujourd'hui (" . Carbon::now()->format('d/m/Y') . ')',
            'M'      => 'Mois en cours (' . Carbon::now()->format('m/Y') . ')',
            'A'      => 'Année en cours (' . Carbon::now()->format('Y') . ')',
            'Custom' => 'Du ' . Carbon::parse($this->customStartDate)->format('d/m/Y')
                               . ' au ' . Carbon::parse($this->customEndDate)->format('d/m/Y'),
            default  => '',
        };
    }

    private function buildTableau(): array
    {
        // On agrège directement en base plutôt qu'en PHP pour tirer parti de l'index
        // sur id_question et éviter de rapatrier des milliers de lignes brutes.
        $byQuestion = $this->baseQuery()
            ->select(
                'tracking.id_question',
                DB::raw('AVG(tracking.temps_total_ms)      as avg_temps'),
                DB::raw('AVG(tracking.latence_ms)          as avg_latence'),
                DB::raw('AVG(tracking.nb_clics)            as avg_clics'),
                DB::raw('AVG(tracking.nb_changements)      as avg_changements'),
                DB::raw('AVG(tracking.nb_clics_hors_cible) as avg_hors_cible'),
                DB::raw('AVG(tracking.nb_pauses)           as avg_pauses'),
                DB::raw('AVG(tracking.resultat)            as avg_score'),
                DB::raw('COUNT(*)                          as nb_occurrences')
            )
            ->groupBy('tracking.id_question')
            ->get()
            ->keyBy('id_question');

        // On compte les passations distinctes pour afficher un total cohérent :
        // une même passation peut avoir plusieurs lignes de tracking
        $totalPassations = $this->baseQuery()
            ->distinct('tracking.id_passation')
            ->count('tracking.id_passation');

        $questions = Question::orderBy('id')->get();
        $rows = [];

        foreach ($questions as $idx => $q) {
            $row    = $byQuestion->get($q->id);
            $rows[] = [
                'num'             => $idx + 1,
                'id'              => $q->id,
                'intitule'        => $q->intitule ?? '',
                'categorie'       => $q->categorie ?? '—',
                'avg_score'       => $row ? round($row->avg_score, 2)      : null,
                'avg_temps'       => $row ? round($row->avg_temps)          : null,
                'avg_latence'     => $row ? round($row->avg_latence)        : null,
                'avg_clics'       => $row ? round($row->avg_clics, 1)       : null,
                'avg_changements' => $row ? round($row->avg_changements, 2) : null,
                'avg_hors_cible'  => $row ? round($row->avg_hors_cible, 1)  : null,
                'avg_pauses'      => $row ? round($row->avg_pauses, 1)      : null,
                'nb_occurrences'  => $row ? (int) $row->nb_occurrences      : 0,
            ];
        }

        return compact('rows', 'totalPassations');
    }

    public function exportCsv()
    {
        $data     = $this->buildTableau();
        $filename = 'tracking_comportemental_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($data) {
            $f = fopen('php://output', 'w');

            // Le BOM UTF-8 est indispensable pour qu'Excel (Windows) détecte
            // automatiquement l'encodage et n'affiche pas de caractères corrompus
            // sur les accents et caractères spéciaux.
            fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($f, ['EXPORT — STATISTIQUES COMPORTEMENTALES'], ';');
            fputcsv($f, ['Période',          $this->getPeriodeLabel()], ';');
            fputcsv($f, ['Total passations', $data['totalPassations']], ';');
            fputcsv($f, ['Date export',      now()->format('d/m/Y H:i')], ';');
            fputcsv($f, [], ';');

            fputcsv($f, [
                'N°', 'ID Question', 'Catégorie', 'Intitulé',
                'Score moyen', 'Temps moy. (ms)', 'Latence moy. (ms)',
                'Clics moy.', 'Changements moy.', 'Hors-cible moy.',
                'Pauses moy.', 'Nb occurrences',
            ], ';');

            foreach ($data['rows'] as $r) {
                // Le flag $noData évite d'afficher un zéro trompeur : une question
                // non tentée n'a pas un score de 0, elle n'a tout simplement pas
                // de données, ce que "N/A" traduit sans ambiguïté.
                $noData = $r['nb_occurrences'] === 0;
                fputcsv($f, [
                    $r['num'], $r['id'], $r['categorie'], $r['intitule'],
                    $noData ? 'N/A' : ($r['avg_score']       ?? ''),
                    $noData ? 'N/A' : ($r['avg_temps']       ?? ''),
                    $noData ? 'N/A' : ($r['avg_latence']     ?? ''),
                    $noData ? 'N/A' : ($r['avg_clics']       ?? ''),
                    $noData ? 'N/A' : ($r['avg_changements'] ?? ''),
                    $noData ? 'N/A' : ($r['avg_hors_cible']  ?? ''),
                    $noData ? 'N/A' : ($r['avg_pauses']      ?? ''),
                    $r['nb_occurrences'],
                ], ';');
            }

            fclose($f);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportExcel()
    {
        $data     = $this->buildTableau();
        $filename = 'tracking_comportemental_' . now()->format('Ymd_His') . '.xlsx';

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '222A60']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setTitle('Tracking Comportemental');

        // --- Feuille "Résumé" ---
        $sr = $spreadsheet->getActiveSheet()->setTitle('Résumé');
        $sr->mergeCells('A1:C1');
        $sr->setCellValue('A1', 'EXPORT — STATISTIQUES COMPORTEMENTALES');
        $sr->getStyle('A1')->getFont()->setBold(true)->setSize(13)->getColor()->setRGB('222A60');
        $sr->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EEF0F8');
        $sr->getRowDimension(1)->setRowHeight(28);

        foreach ([
            ['A3', 'Période',          'B3', $this->getPeriodeLabel()],
            ['A4', 'Total passations', 'B4', $data['totalPassations']],
            ['A5', 'Date export',      'B5', now()->format('d/m/Y H:i')],
        ] as [$kA, $vA, $kB, $vB]) {
            $sr->setCellValue($kA, $vA)->setCellValue($kB, $vB);
            $sr->getStyle($kA)->getFont()->setBold(true);
        }
        $sr->getStyle('B4')->getFont()->setBold(true)->setSize(13)->getColor()->setRGB('16987C');
        $sr->getColumnDimension('A')->setWidth(22);
        $sr->getColumnDimension('B')->setWidth(45);

        // --- Feuille "Par question" ---
        $sd = $spreadsheet->createSheet()->setTitle('Par question');
        $headers = [
            'N°', 'ID', 'Catégorie', 'Intitulé',
            'Score moy.', 'Temps moy. (ms)', 'Latence moy. (ms)',
            'Clics moy.', 'Changements moy.', 'Hors-cible moy.',
            'Pauses moy.', 'Nb occurrences',
        ];
        foreach ($headers as $col => $header) {
            $sd->setCellValue([$col + 1, 1], $header);
        }
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $sd->getStyle("A1:{$lastCol}1")->applyFromArray($headerStyle);
        $sd->getRowDimension(1)->setRowHeight(22);

        $row = 2;
        foreach ($data['rows'] as $r) {
            $noData  = $r['nb_occurrences'] === 0;
            $rowData = [
                $r['num'], $r['id'], $r['categorie'], $r['intitule'],
                $noData ? 'N/A' : $r['avg_score'],
                $noData ? 'N/A' : $r['avg_temps'],
                $noData ? 'N/A' : $r['avg_latence'],
                $noData ? 'N/A' : $r['avg_clics'],
                $noData ? 'N/A' : $r['avg_changements'],
                $noData ? 'N/A' : $r['avg_hors_cible'],
                $noData ? 'N/A' : $r['avg_pauses'],
            ];
            foreach ($rowData as $col => $value) {
                $sd->setCellValue([$col + 1, $row], $value);
            }
            if ($row % 2 === 0) {
                $sd->getStyle("A{$row}:{$lastCol}{$row}")
                   ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8F9FC');
            }
            if (!$noData && $r['avg_score'] !== null) {
                $c = $r['avg_score'] > 0 ? '16987C' : ($r['avg_score'] < 0 ? 'EF4444' : '9CA3AF');
                $sd->getStyle("E{$row}")->getFont()->setBold(true)->getColor()->setRGB($c);
            }
            if (!$noData && ($r['avg_changements'] ?? 0) > 0.5) {
                $sd->getStyle("I{$row}")->getFont()->setBold(true)->getColor()->setRGB('F97316');
            }
            $row++;
        }
        foreach ([6, 6, 18, 60, 12, 18, 18, 12, 18, 16, 13, 16] as $col => $width) {
            $sd->getColumnDimensionByColumn($col + 1)->setWidth($width);
        }

        // --- Feuille "Top hésitation" ---
        $st = $spreadsheet->createSheet()->setTitle('Top hésitation');
        $st->setCellValue('A1', 'Question')->setCellValue('B1', 'Latence moy. (ms)');
        $st->getStyle('A1:B1')->applyFromArray($headerStyle);
        $row = 2;
        foreach (collect($data['rows'])->where('nb_occurrences', '>', 0)->sortByDesc('avg_latence')->take(10) as $r) {
            // mb_strimwidth garantit que la troncature respecte les caractères
            // multi-octets (accents, etc.) pour éviter un intitulé corrompu en
            // coupant au milieu d'un caractère UTF-8.
            $st->setCellValue("A{$row}", 'Q' . $r['id'] . ' — ' . mb_strimwidth($r['intitule'], 0, 50, '…'))
               ->setCellValue("B{$row}", $r['avg_latence']);
            $row++;
        }
        $st->getColumnDimension('A')->setWidth(55);
        $st->getColumnDimension('B')->setWidth(20);

        // --- Feuille "Top incertitude" ---
        $si = $spreadsheet->createSheet()->setTitle('Top incertitude');
        $si->setCellValue('A1', 'Question')->setCellValue('B1', 'Changements moy.');
        $si->getStyle('A1:B1')->applyFromArray($headerStyle);
        $row = 2;
        foreach (collect($data['rows'])->where('nb_occurrences', '>', 0)->sortByDesc('avg_changements')->take(10) as $r) {
            $si->setCellValue("A{$row}", 'Q' . $r['id'] . ' — ' . mb_strimwidth($r['intitule'], 0, 50, '…'))
               ->setCellValue("B{$row}", $r['avg_changements']);
            $row++;
        }
        $si->getColumnDimension('A')->setWidth(55);
        $si->getColumnDimension('B')->setWidth(20);

        $spreadsheet->setActiveSheetIndex(0);

        return response()->streamDownload(function () use ($spreadsheet) {
            // streamDownload évite de stocker le fichier sur le disque du serveur,
            // ce qui est préférable pour éviter l'accumulation de fichiers temporaires
            // et pour ne pas exposer de données sensibles dans le filesystem.
            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.export-comportementale-modal');
    }
}
