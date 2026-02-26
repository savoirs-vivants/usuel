<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Passation;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ExportModal extends Component
{
    public array  $selectedAges     = [];
    public array  $selectedGenres   = [];
    public array  $selectedCsps     = [];
    public array  $selectedDiplomes = [];
    public string $timeRange        = 'A';
    public string $customStartDate  = '';
    public string $customEndDate    = '';

    private function getFilteredPassations()
    {
        $query = Passation::query()
            ->join('beneficiaires', 'passations.id_beneficiaire', '=', 'beneficiaires.id')
            ->select(
                'passations.id',
                'passations.score',
                'passations.created_at',
                'beneficiaires.prenom',
                'beneficiaires.nom',
                'beneficiaires.age',
                'beneficiaires.genre',
                'beneficiaires.csp',
                'beneficiaires.diplome'
            );

        if (!empty($this->selectedAges))     $query->whereIn('beneficiaires.age',     $this->selectedAges);
        if (!empty($this->selectedGenres))   $query->whereIn('beneficiaires.genre',   $this->selectedGenres);
        if (!empty($this->selectedCsps))     $query->whereIn('beneficiaires.csp',     $this->selectedCsps);
        if (!empty($this->selectedDiplomes)) $query->whereIn('beneficiaires.diplome', $this->selectedDiplomes);

        $now = Carbon::now();
        match ($this->timeRange) {
            'J'      => $query->whereDate('passations.created_at', $now->toDateString()),
            'M'      => $query->whereMonth('passations.created_at', $now->month)->whereYear('passations.created_at', $now->year),
            'A'      => $query->whereYear('passations.created_at', $now->year),
            'Custom' => $query->whereBetween('passations.created_at', [
                             $this->customStartDate . ' 00:00:00',
                             $this->customEndDate   . ' 23:59:59',
                         ]),
            default  => null,
        };

        return $query->orderBy('passations.created_at', 'desc')->get();
    }

    private function getAggregatedData(): array
    {
        $passations = $this->getFilteredPassations();
        $labelsMap  = $this->getLabelsMap();
        $dimKeys    = ['Resilience', 'EC', 'CSDLEN', 'CT', 'TDLinfo', 'CDC'];
        $dimLabels  = ['Résilience', 'Esprit Critique', 'Comp. sociales', 'Comp. Technique', 'Trait. info', 'Création'];

        $genreMap = [];
        foreach ($passations as $p) {
            $key = $labelsMap[$p->genre] ?? $p->genre;
            $genreMap[$key] = ($genreMap[$key] ?? 0) + 1;
        }

        $cspMap = [];
        foreach ($passations as $p) {
            $key = $labelsMap[$p->csp] ?? $p->csp;
            $cspMap[$key] = ($cspMap[$key] ?? 0) + 1;
        }

        $ageMap = [];
        foreach ($passations as $p) {
            $key    = $labelsMap[$p->age] ?? $p->age;
            $scores = is_string($p->score) ? json_decode($p->score, true) : $p->score;
            $total  = is_array($scores) ? array_sum($scores) : 0;
            $ageMap[$key][] = $total;
        }
        $ageAvg = [];
        foreach ($ageMap as $label => $scores) {
            $ageAvg[$label] = round(array_sum($scores) / count($scores), 2);
        }

        $dimSums  = array_fill_keys($dimKeys, 0);
        $dimCount = 0;
        foreach ($passations as $p) {
            $scores = is_string($p->score) ? json_decode($p->score, true) : $p->score;
            if (is_array($scores)) {
                $dimCount++;
                foreach ($dimKeys as $k) {
                    $dimSums[$k] += $scores[$k] ?? 0;
                }
            }
        }
        $dimAvg = [];
        foreach ($dimKeys as $i => $k) {
            $dimAvg[$dimLabels[$i]] = $dimCount > 0 ? round($dimSums[$k] / $dimCount, 2) : 0;
        }

        return compact('passations', 'genreMap', 'cspMap', 'ageAvg', 'dimAvg', 'dimKeys', 'dimLabels');
    }

    private function getLabelsMap(): array
    {
        return [
            'moins_18' => '< 18 ans', '18_25' => '18-25', '26_35' => '26-35',
            '36_45' => '36-45', '46_55' => '46-55', '56_65' => '56-65', 'plus_65' => '> 65 ans',
            'homme' => 'Homme', 'femme' => 'Femme', 'autre' => 'Autre / Non-binaire', 'non_precise' => 'Non précisé',
            'aucun' => 'Aucun diplôme', 'brevet' => 'Brevet (DNB)', 'cap_bep' => 'CAP / BEP',
            'bac' => 'Baccalauréat', 'bac2' => 'Bac +2', 'bac3' => 'Bac +3', 'bac5' => 'Bac +5', 'doctorat' => 'Doctorat',
            'agriculteur' => 'Agriculteur', 'artisan' => 'Artisan / Commerçant', 'cadre' => 'Cadre',
            'intermediaire' => 'Prof. intermédiaire', 'employe' => 'Employé', 'ouvrier' => 'Ouvrier',
            'retraite' => 'Retraité', 'sans_activite' => 'Sans activité',
        ];
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


    public function exportCsv()
    {
        $d          = $this->getAggregatedData();
        $labelsMap  = $this->getLabelsMap();
        $filename   = 'statistiques_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($d, $labelsMap) {
            $f = fopen('php://output', 'w');
            fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($f, ['EXPORT STATISTIQUES'], ';');
            fputcsv($f, ['Période', $this->getPeriodeLabel()], ';');
            fputcsv($f, ['Total passations', $d['passations']->count()], ';');
            fputcsv($f, ['Date export', now()->format('d/m/Y H:i')], ';');
            fputcsv($f, [], ';');

            fputcsv($f, ['--- RÉPARTITION PAR GENRE ---'], ';');
            fputcsv($f, ['Genre', 'Nombre', '% du total'], ';');
            $totalGenre = array_sum($d['genreMap']);
            foreach ($d['genreMap'] as $label => $count) {
                fputcsv($f, [$label, $count, $totalGenre > 0 ? round($count / $totalGenre * 100, 1) . '%' : '0%'], ';');
            }
            fputcsv($f, [], ';');

            fputcsv($f, ["--- SCORE MOYEN PAR TRANCHE D'ÂGE ---"], ';');
            fputcsv($f, ["Tranche d'âge", 'Score moyen (/ 30)'], ';');
            foreach ($d['ageAvg'] as $label => $score) {
                fputcsv($f, [$label, $score], ';');
            }
            fputcsv($f, [], ';');

            fputcsv($f, ['--- RÉPARTITION PAR Catégorie Socio Professionnelle---'], ';');
            fputcsv($f, ['Catégorie', 'Nombre', '% du total'], ';');
            $totalCsp = array_sum($d['cspMap']);
            foreach ($d['cspMap'] as $label => $count) {
                fputcsv($f, [$label, $count, $totalCsp > 0 ? round($count / $totalCsp * 100, 1) . '%' : '0%'], ';');
            }
            fputcsv($f, [], ';');

            fputcsv($f, ['--- SCORES MOYENS PAR DIMENSION (/ 5) ---'], ';');
            fputcsv($f, ['Dimension', 'Score moyen'], ';');
            foreach ($d['dimAvg'] as $label => $score) {
                fputcsv($f, [$label, $score], ';');
            }
            fputcsv($f, [], ';');

            fputcsv($f, ['--- DÉTAIL DES PASSATIONS ---'], ';');
            fputcsv($f, ['ID', 'Prénom', 'Nom', 'Âge', 'Genre', 'Catégorie Socio Professionnelle', 'Diplôme', ...$d['dimLabels'], 'Score total (/30)', 'Date'], ';');
            foreach ($d['passations'] as $p) {
                $scores    = is_string($p->score) ? json_decode($p->score, true) : $p->score;
                $scores    = is_array($scores) ? $scores : [];
                $dimScores = array_map(fn($k) => $scores[$k] ?? 0, $d['dimKeys']);
                fputcsv($f, [
                    $p->id,
                    $p->prenom ?? '',
                    $p->nom    ?? '',
                    $labelsMap[$p->age]     ?? $p->age,
                    $labelsMap[$p->genre]   ?? $p->genre,
                    $labelsMap[$p->csp]     ?? $p->csp,
                    $labelsMap[$p->diplome] ?? $p->diplome,
                    ...$dimScores,
                    round(array_sum($dimScores), 2),
                    Carbon::parse($p->created_at)->format('d/m/Y H:i'),
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
        $d         = $this->getAggregatedData();
        $labelsMap = $this->getLabelsMap();
        $filename  = 'statistiques_' . now()->format('Ymd_His') . '.xlsx';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setTitle('Statistiques');

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A9E7E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        $s = $spreadsheet->getActiveSheet()->setTitle('Résumé');
        $s->setCellValue('A1', 'EXPORT STATISTIQUES')->mergeCells('A1:B1');
        $s->getStyle('A1:B1')->getFont()->setBold(true)->setSize(13);
        $s->getStyle('A1:B1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E8F8F4');
        $s->setCellValue('A3', 'Période')->setCellValue('B3', $this->getPeriodeLabel());
        $s->setCellValue('A4', 'Total passations')->setCellValue('B4', $d['passations']->count());
        $s->setCellValue('A5', 'Date export')->setCellValue('B5', now()->format('d/m/Y H:i'));
        $s->getStyle('A3:A5')->getFont()->setBold(true);
        $s->getColumnDimension('A')->setWidth(22);
        $s->getColumnDimension('B')->setWidth(40);

        $sg = $spreadsheet->createSheet()->setTitle('Genre');
        $sg->setCellValue('A1', 'Genre')->setCellValue('B1', 'Nombre')->setCellValue('C1', '% du total');
        $sg->getStyle('A1:C1')->applyFromArray($headerStyle);
        $totalGenre = array_sum($d['genreMap']);
        $row = 2;
        foreach ($d['genreMap'] as $label => $count) {
            $sg->setCellValue("A$row", $label)
               ->setCellValue("B$row", $count)
               ->setCellValue("C$row", $totalGenre > 0 ? round($count / $totalGenre * 100, 1) . '%' : '0%');
            $row++;
        }
        foreach (['A', 'B', 'C'] as $col) $sg->getColumnDimension($col)->setAutoSize(true);

        $sa = $spreadsheet->createSheet()->setTitle('Âge');
        $sa->setCellValue('A1', "Tranche d'âge")->setCellValue('B1', 'Score moyen (/ 30)');
        $sa->getStyle('A1:B1')->applyFromArray($headerStyle);
        $row = 2;
        foreach ($d['ageAvg'] as $label => $score) {
            $sa->setCellValue("A$row", $label)->setCellValue("B$row", $score);
            $row++;
        }
        foreach (['A', 'B'] as $col) $sa->getColumnDimension($col)->setAutoSize(true);

        $sc = $spreadsheet->createSheet()->setTitle('Catégorie Socio Professionnelle');
        $sc->setCellValue('A1', 'Catégorie Socio Professionnelle')->setCellValue('B1', 'Nombre')->setCellValue('C1', '% du total');
        $sc->getStyle('A1:C1')->applyFromArray($headerStyle);
        $totalCsp = array_sum($d['cspMap']);
        $row = 2;
        foreach ($d['cspMap'] as $label => $count) {
            $sc->setCellValue("A$row", $label)
               ->setCellValue("B$row", $count)
               ->setCellValue("C$row", $totalCsp > 0 ? round($count / $totalCsp * 100, 1) . '%' : '0%');
            $row++;
        }
        foreach (['A', 'B', 'C'] as $col) $sc->getColumnDimension($col)->setAutoSize(true);

        $sd = $spreadsheet->createSheet()->setTitle('Dimensions');
        $sd->setCellValue('A1', 'Dimension')->setCellValue('B1', 'Score moyen (/ 5)');
        $sd->getStyle('A1:B1')->applyFromArray($headerStyle);
        $row = 2;
        foreach ($d['dimAvg'] as $label => $score) {
            $sd->setCellValue("A$row", $label)->setCellValue("B$row", $score);
            $row++;
        }
        foreach (['A', 'B'] as $col) $sd->getColumnDimension($col)->setAutoSize(true);

        $sp = $spreadsheet->createSheet()->setTitle('Détail passations');
        $headers = ['ID', 'Prénom', 'Nom', 'Âge', 'Genre', 'Catégorie Socio Professionnelle', 'Diplôme', ...$d['dimLabels'], 'Score total (/30)', 'Date'];
        foreach ($headers as $col => $header) {
            $sp->setCellValue([$col + 1, 1], $header);
        }
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $sp->getStyle("A1:{$lastCol}1")->applyFromArray($headerStyle);

        $row = 2;
        foreach ($d['passations'] as $p) {
            $scores    = is_string($p->score) ? json_decode($p->score, true) : $p->score;
            $scores    = is_array($scores) ? $scores : [];
            $dimScores = array_map(fn($k) => $scores[$k] ?? 0, $d['dimKeys']);
            $rowData   = [
                $p->id,
                $p->prenom ?? '',
                $p->nom    ?? '',
                $labelsMap[$p->age]     ?? $p->age,
                $labelsMap[$p->genre]   ?? $p->genre,
                $labelsMap[$p->csp]     ?? $p->csp,
                $labelsMap[$p->diplome] ?? $p->diplome,
                ...$dimScores,
                round(array_sum($dimScores), 2),
                Carbon::parse($p->created_at)->format('d/m/Y H:i'),
            ];
            foreach ($rowData as $col => $value) {
                $sp->setCellValue([$col + 1, $row], $value);
            }
            if ($row % 2 === 0) {
                $sp->getStyle("A{$row}:{$lastCol}{$row}")->getFill()
                   ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F9FAFB');
            }
            $row++;
        }
        for ($col = 1; $col <= count($headers); $col++) {
            $sp->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        $spreadsheet->setActiveSheetIndex(0);

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    public function render()
    {
        return view('livewire.export-modal');
    }
}
