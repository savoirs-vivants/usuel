<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use App\Models\Passation;
use App\Models\Beneficiaire;
use Carbon\Carbon;

class StatistiquesPublic extends Component
{
    #[Url(as: 'age')]
    public array $selectedAges = [];

    #[Url(as: 'genre')]
    public array $selectedGenres = [];

    #[Url(as: 'csp')]
    public array $selectedCsps = [];

    #[Url(as: 'diplome')]
    public array $selectedDiplomes = [];

    #[Url(as: 'periode')]
    public string $timeRange = 'A';

    public string $customStartDate = '';
    public string $customEndDate   = '';

    public array $availableAges     = [];
    public array $availableGenres   = [];
    public array $availableCsps     = [];
    public array $availableDiplomes = [];

    public function mount(): void
    {
        $this->availableAges     = Beneficiaire::whereNotNull('age')->distinct()->orderBy('age')->pluck('age')->toArray();
        $this->availableGenres   = Beneficiaire::whereNotNull('genre')->distinct()->orderBy('genre')->pluck('genre')->toArray();
        $this->availableCsps     = Beneficiaire::whereNotNull('csp')->distinct()->orderBy('csp')->pluck('csp')->toArray();
        $this->availableDiplomes = Beneficiaire::whereNotNull('diplome')->distinct()->orderBy('diplome')->pluck('diplome')->toArray();

        $this->customStartDate = Carbon::now()->startOfMonth()->toDateString();
        $this->customEndDate   = Carbon::now()->endOfMonth()->toDateString();
    }

    /**
     * Déclenché par wire:model.live (checkboxes, dates custom).
     * On dispatch l'événement JS pour mettre à jour les graphiques.
     */
    public function updated(string $property): void
    {
        // On évite de dispatcher pour les changements de timeRange
        // car setTimeRange() le gère déjà.
        if ($property !== 'timeRange') {
            unset($this->chartData); // Invalide le cache du computed
            $this->dispatch('update-charts', data: $this->chartData);
        }
    }

    public function setTimeRange(string $range): void
    {
        $this->timeRange = $range;
        unset($this->chartData);
        $this->dispatch('update-charts', data: $this->chartData);
    }

    public function resetFilters(): void
    {
        $this->reset(['selectedAges', 'selectedGenres', 'selectedCsps', 'selectedDiplomes']);
        $this->timeRange = 'A';
        unset($this->chartData);
        $this->dispatch('update-charts', data: $this->chartData);
    }

    #[Computed]
    public function chartData(): array
    {
        $query = Passation::query()
            ->join('beneficiaires', 'passations.id_beneficiaire', '=', 'beneficiaires.id')
            ->select(
                'passations.*',
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
            'M'      => $query->whereMonth('passations.created_at', $now->month)
                ->whereYear('passations.created_at', $now->year),
            'A'      => $query->whereYear('passations.created_at', $now->year),
            'Custom' => $query->whereBetween('passations.created_at', [
                $this->customStartDate . ' 00:00:00',
                $this->customEndDate   . ' 23:59:59',
            ]),
            default  => null,
        };

        $passations      = $query->get();
        $totalPassations = $passations->count();

        $labelsMap = [
            'moins_18'   => '< 18 ans',
            '18_25'      => '18-25',
            '26_35'      => '26-35',
            '36_45'      => '36-45',
            '46_55'      => '46-55',
            '56_65'      => '56-65',
            'plus_65'    => '> 65 ans',
            'etudiant'   => 'Étudiant',
            'employe'    => 'Employé',
            'cadre'      => 'Cadre',
            'sans_emploi' => 'Sans emploi',
            'retraite'   => 'Retraité',
        ];

        // ── Données par âge ──────────────────────────────────────────────
        $ageLabels = [];
        $ageScores = [];

        foreach ($this->availableAges as $age) {
            $subset = $passations->where('age', $age);
            if ($subset->count() > 0) {
                $ageLabels[] = $labelsMap[$age] ?? $age;
                $moyenne     = $subset->map(function ($p) {
                    $s = is_string($p->score) ? json_decode($p->score, true) : $p->score;
                    return is_array($s) ? array_sum($s) : 0;
                })->avg();
                $ageScores[] = round($moyenne, 1);
            }
        }

        // ── Données par genre ─────────────────────────────────────────────
        $genreLabels = [];
        $genreCounts = [];

        foreach ($this->availableGenres as $genre) {
            $count = $passations->where('genre', $genre)->count();
            if ($count > 0) {
                $genreLabels[] = ucfirst($genre);
                $genreCounts[] = $count;
            }
        }

        // ── Données par CSP ───────────────────────────────────────────────
        $cspLabels = [];
        $cspCounts = [];

        foreach ($this->availableCsps as $csp) {
            $count = $passations->where('csp', $csp)->count();
            if ($count > 0) {
                $cspLabels[] = $labelsMap[$csp] ?? $csp;
                $cspCounts[] = $count;
            }
        }

        // ── Données par dimension (radar) ─────────────────────────────────
        $dimensions = ['Résilience', 'Esprit Critique', 'Comp. sociales', 'Comp. Technique', 'Trait. info', 'Création'];
        $dimKeys    = ['Resilience', 'EC', 'CSDLEN', 'CT', 'TDLinfo', 'CDC'];
        $dimScores  = [];

        if ($totalPassations > 0) {
            $sums            = array_fill_keys($dimKeys, 0);
            $validPassations = 0;

            foreach ($passations as $passation) {
                $scoreArray = is_string($passation->score)
                    ? json_decode($passation->score, true)
                    : $passation->score;

                if (is_array($scoreArray)) {
                    $validPassations++;
                    foreach ($dimKeys as $key) {
                        $sums[$key] += $scoreArray[$key] ?? 0;
                    }
                }
            }

            foreach ($dimKeys as $key) {
                $moyenne    = $validPassations > 0 ? ($sums[$key] / $validPassations) : 0;
                $dimScores[] = round($moyenne, 1);
            }
        } else {
            $dimScores = array_fill(0, count($dimKeys), 0);
        }

        return [
            'age_labels'       => $ageLabels,
            'age_scores'       => $ageScores,
            'genre_labels'     => $genreLabels,
            'genre_counts'     => $genreCounts,
            'csp_labels'       => $cspLabels,
            'csp_counts'       => $cspCounts,
            'dim_labels'       => $dimensions,
            'dim_scores'       => $dimScores,
            'total_passations' => $totalPassations,
        ];
    }

    public function render()
    {
        $labelsMap = [
            'moins_18'    => 'Moins de 18 ans',
            '18_25'       => '18 – 25 ans',
            '26_35'       => '26 – 35 ans',
            '36_45'       => '36 – 45 ans',
            '46_55'       => '46 – 55 ans',
            '56_65'       => '56 – 65 ans',
            'plus_65'     => 'Plus de 65 ans',
            'etudiant'    => 'Étudiant',
            'employe'     => 'Employé',
            'cadre'       => 'Cadre',
            'sans_emploi' => 'Sans emploi',
            'retraite'    => 'Retraité',
            'sans'        => 'Sans diplôme',
            'bac'         => 'Bac',
            'bac2'        => 'Bac +2',
            'bac3'        => 'Bac +3',
            'bac5'        => 'Bac +5 et plus',
            'Homme'       => 'Homme',
            'Femme'       => 'Femme',
            'Autre'       => 'Autre',
        ];

        return view('livewire.statistiques-public', compact('labelsMap'));
    }
}
