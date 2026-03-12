<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use App\Models\Tracking;
use App\Models\Beneficiaire;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;


#[Layout('layouts.app')]
#[Title('Usuel - Statistiques Comportementale')]
class StatistiquesComportementale extends Component
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

    #[Url(as: 'mode')]
    public array $selectedModes = [];

    public array $availableModes = [];

    public string $customStartDate = '';
    public string $customEndDate   = '';

    public array $availableAges     = [];
    public array $availableGenres   = [];
    public array $availableCsps     = [];
    public array $availableDiplomes = [];

    public string $sortField = 'num';
    public string $sortDirection = 'asc';

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function mount(): void
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        $ageOrder = ['moins_18', '18_25', '26_35', '36_45', '46_55', '56_65', 'plus_65'];
        $rawAges  = Beneficiaire::whereNotNull('age')->distinct()->pluck('age')->toArray();
        $this->availableAges     = array_values(array_filter($ageOrder, fn($a) => in_array($a, $rawAges)));
        $this->availableGenres   = Beneficiaire::whereNotNull('genre')->distinct()->orderBy('genre')->pluck('genre')->toArray();
        $this->availableCsps     = Beneficiaire::whereNotNull('csp')->distinct()->orderBy('csp')->pluck('csp')->toArray();
        $this->availableDiplomes = Beneficiaire::whereNotNull('diplome')->distinct()->orderBy('diplome')->pluck('diplome')->toArray();
        $this->customStartDate   = Carbon::now()->startOfMonth()->toDateString();
        $this->customEndDate     = Carbon::now()->endOfMonth()->toDateString();
        $this->availableModes = Tracking::query()->join('passations', 'tracking.id_passation', '=', 'passations.id')->whereNotNull('passations.mode_ordre')->distinct()->pluck('passations.mode_ordre')->toArray();
    }

    public function updated(string $property): void
    {
        unset($this->trackingData);
        $this->dispatch('update-charts', data: $this->trackingData);
    }

    public function setTimeRange(string $range): void
    {
        $this->timeRange = $range;
        unset($this->trackingData);
        $this->dispatch('update-charts', data: $this->trackingData);
    }

    public function resetFilters(): void
    {
        $this->reset(['selectedAges', 'selectedGenres', 'selectedCsps', 'selectedDiplomes', 'selectedModes']);
        $this->timeRange = 'A';
        $this->sortField = 'num';
        unset($this->trackingData);
        $this->dispatch('update-charts', data: $this->trackingData);
    }

    private function baseQuery()
    {
        $query = Tracking::query()
            ->join('passations', 'tracking.id_passation', '=', 'passations.id')
            ->join('beneficiaires', 'passations.id_beneficiaire', '=', 'beneficiaires.id')
            ->whereNotNull('tracking.id_passation');

        if (!empty($this->selectedAges))     $query->whereIn('beneficiaires.age',     $this->selectedAges);
        if (!empty($this->selectedGenres))   $query->whereIn('beneficiaires.genre',   $this->selectedGenres);
        if (!empty($this->selectedCsps))     $query->whereIn('beneficiaires.csp',     $this->selectedCsps);
        if (!empty($this->selectedDiplomes)) $query->whereIn('beneficiaires.diplome', $this->selectedDiplomes);
        if (!empty($this->selectedModes))    $query->whereIn('passations.mode_ordre', $this->selectedModes);

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

        return $query;
    }

    #[Computed]
    public function trackingData(): array
    {
        $totalPassations = $this->baseQuery()
            ->distinct('tracking.id_passation')
            ->count('tracking.id_passation');

        if ($totalPassations === 0) {
            return $this->emptyData();
        }

        $byQuestion = $this->baseQuery()
            ->select(
                'tracking.id_question',
                DB::raw('AVG(tracking.temps_total_ms) as avg_temps'),
                DB::raw('AVG(tracking.latence_ms) as avg_latence'),
                DB::raw('AVG(tracking.nb_clics) as avg_clics'),
                DB::raw('AVG(tracking.nb_changements) as avg_changements'),
                DB::raw('AVG(tracking.nb_clics_hors_cible) as avg_hors_cible'),
                DB::raw('AVG(tracking.nb_pauses) as avg_pauses'),
                DB::raw('AVG(tracking.resultat) as avg_score'),
                DB::raw('COUNT(*) as nb_occurrences')
            )
            ->groupBy('tracking.id_question')
            ->get()
            ->keyBy('id_question');

        $questions = Question::orderBy('id')->get();

        $top5Latence = $byQuestion
            ->sortByDesc('avg_latence')
            ->take(5)
            ->map(fn($row) => [
                'label' => 'Q' . $row->id_question,
                'value' => round($row->avg_latence),
            ])
            ->values()
            ->toArray();

        $top5Changements = $byQuestion
            ->sortByDesc('avg_changements')
            ->take(5)
            ->map(fn($row) => [
                'label' => 'Q' . $row->id_question,
                'value' => round($row->avg_changements, 2),
            ])
            ->values()
            ->toArray();

        $tableau = [];
        foreach ($questions as $idx => $q) {
            $row = $byQuestion->get($q->id);
            $tableau[] = [
                'num'                => $idx + 1,
                'id'                 => $q->id,
                'intitule'           => mb_strimwidth($q->intitule ?? '', 0, 60, '…'),
                'categorie'          => $q->categorie ?? '—',
                'avg_temps'          => $row ? round($row->avg_temps) : null,
                'avg_latence'        => $row ? round($row->avg_latence) : null,
                'avg_clics'          => $row ? round($row->avg_clics, 1) : null,
                'avg_changements'    => $row ? round($row->avg_changements, 2) : null,
                'avg_hors_cible'     => $row ? round($row->avg_hors_cible, 1) : null,
                'avg_pauses'         => $row ? round($row->avg_pauses, 1) : null,
                'nb_occurrences'     => $row ? $row->nb_occurrences : 0,
                'avg_score'          => $row ? round($row->avg_score, 2) : null,
            ];
        }

        $byPosition = $this->baseQuery()
            ->select(
                'tracking.position',
                DB::raw('AVG(tracking.temps_total_ms) as avg_temps'),
                DB::raw('AVG(tracking.latence_ms) as avg_latence'),
                DB::raw('AVG(tracking.nb_changements) as avg_changements')
            )
            ->groupBy('tracking.position')
            ->orderBy('tracking.position')
            ->get();

        $ordrePositions   = $byPosition->pluck('position')->toArray();
        $ordreTemps       = $byPosition->map(fn($r) => round($r->avg_temps))->toArray();
        $ordreLatence     = $byPosition->map(fn($r) => round($r->avg_latence))->toArray();
        $ordreChangements = $byPosition->map(fn($r) => round($r->avg_changements, 2))->toArray();

        $collection = collect($tableau);
        if ($this->sortDirection === 'asc') {
            $tableau = $collection->sortBy($this->sortField)->values()->toArray();
        } else {
            $tableau = $collection->sortByDesc($this->sortField)->values()->toArray();
        }

        return [
            'total_passations'  => $totalPassations,
            'top5_latence'      => $top5Latence,
            'top5_changements'  => $top5Changements,
            'tableau'           => $tableau,
            'ordre_positions'   => $ordrePositions,
            'ordre_temps'       => $ordreTemps,
            'ordre_latence'     => $ordreLatence,
            'ordre_changements' => $ordreChangements,
        ];
    }

    private function emptyData(): array
    {
        return [
            'total_passations'  => 0,
            'top5_latence'      => [],
            'top5_changements'  => [],
            'tableau'           => [],
            'ordre_positions'   => [],
            'ordre_temps'       => [],
            'ordre_latence'     => [],
            'ordre_changements' => [],
        ];
    }


    public function render()
    {
        $labelsMap = [
            'moins_18' => 'Moins de 18 ans',
            '18_25' => '18 – 25 ans',
            '26_35' => '26 – 35 ans',
            '36_45' => '36 – 45 ans',
            '46_55' => '46 – 55 ans',
            '56_65' => '56 – 65 ans',
            'plus_65' => 'Plus de 65 ans',
            'homme' => 'Homme',
            'femme' => 'Femme',
            'autre' => 'Autre / Non-binaire',
            'non_precise' => 'Non précisé',
            'aucun' => 'Aucun diplôme',
            'brevet' => 'Brevet (DNB)',
            'cap_bep' => 'CAP / BEP',
            'bac' => 'Baccalauréat',
            'bac2' => 'Bac +2',
            'bac3' => 'Bac +3',
            'bac5' => 'Bac +5',
            'doctorat' => 'Doctorat',
            'agriculteur' => 'Agriculteur',
            'artisan' => 'Artisan / Commerçant',
            'cadre' => 'Cadre',
            'intermediaire' => 'Prof. intermédiaire',
            'employe' => 'Employé',
            'ouvrier' => 'Ouvrier',
            'retraite' => 'Retraité',
            'sans_activite' => 'Sans activité',
            'autre' => 'Autre',
        ];

        return view('livewire.statistiques-comportementale', compact('labelsMap'));
    }
}
