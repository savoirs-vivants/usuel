<?php

namespace App\Livewire;

use App\Models\Passation;
use App\Models\Question;
use App\Models\Tracking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class QuestionnaireRun extends Component
{
    public int    $currentIndex   = 0;
    public int    $totalQuestions = 0;
    public string $selectedAnswer = '';
    public bool   $showError      = false;
    public string $modeOrdre      = 'fixe';

    public array $scores = [
        'Resilience' => 0.0,
        'EC'         => 0.0,
        'CSDLEN'     => 0.0,
        'CT'         => 0.0,
        'TDLinfo'    => 0.0,
        'CDC'        => 0.0,
    ];

    public array $questionIds = [];
    public array $trackingIds = [];

    public function mount(): void
    {
        if (!session()->has('beneficiaire_id')) {
            $this->redirect(route('questionnaire.index'));
            return;
        }

        $mode = Cache::get('global_mode_ordre', 'fixe');
        $this->modeOrdre = $mode;

        $this->questionIds = $this->getOrderedQuestionIds($mode);
        $this->totalQuestions = count($this->questionIds);
    }

    private function getOrderedQuestionIds(string $mode): array
    {
        $allIds = Question::where('active', true)->orderBy('id')->pluck('id')->toArray();

        return match ($mode) {
            'aleatoire'      => $this->modeAleatoire($allIds),
            'semi_aleatoire' => $this->modeSemiAleatoire($allIds),
            'carre_latin'    => $this->modeCarreLatin(),
            default          => $allIds,
        };
    }

    private function modeAleatoire(array $ids): array
    {
        $collection = collect($ids);
        return $collection->shuffle()->values()->toArray();
    }

    private function modeSemiAleatoire(array $ids): array
    {
        if (count($ids) <= 2) {
            return $ids;
        }

        $first  = array_shift($ids);
        $last   = array_pop($ids);
        shuffle($ids);

        return array_merge([$first], $ids, [$last]);
    }

    private function modeCarreLatin(): array
    {
        $questions  = Question::where('active', true)->orderBy('id')->get();
        $byCategory = $questions->groupBy('categorie');
        $categories = $byCategory->keys()->sort()->values()->toArray();
        $n          = count($categories);

        if ($n === 0) {
            return $questions->pluck('id')->toArray();
        }

        $offset   = Passation::count() % $n;
        $rotated  = array_merge(
            array_slice($categories, $offset),
            array_slice($categories, 0, $offset)
        );

        $pools = [];
        foreach ($rotated as $cat) {
            $pools[$cat] = $byCategory[$cat]->pluck('id')->shuffle()->toArray();
        }

        $result = [];
        $maxLen = max(array_map('count', $pools));

        for ($i = 0; $i < $maxLen; $i++) {
            foreach ($rotated as $cat) {
                if (isset($pools[$cat][$i])) {
                    $result[] = $pools[$cat][$i];
                }
            }
        }

        return $result;
    }

    public function choisir(string $reponse): void
    {
        $this->selectedAnswer = $reponse;
        $this->showError      = false;
    }

    public function validerAvecTracking(array $tracking): void
    {
        if ($this->selectedAnswer === '') {
            $this->showError = true;
            return;
        }

        if (session('consentement_recherche')) {
            $this->insererTracking($tracking);
        }

        $this->enregistrerReponse($this->selectedAnswer);
    }

    public function jeSaisPasAvecTracking(array $tracking): void
    {
        if (session('consentement_recherche')) {
            $this->insererTracking($tracking);
        }

        $this->enregistrerReponse('E');
    }

    private function enregistrerReponse(string $reponse): void
    {
        $question = Question::find($this->questionIds[$this->currentIndex] ?? null);
        if (!$question) return;

        $poids = $question->getPoids($reponse);
        $cat   = $question->categorie;

        $this->scores[$cat] = round(($this->scores[$cat] ?? 0.0) + $poids, 2);

        $this->selectedAnswer = '';
        $this->showError      = false;
        $this->currentIndex++;

        if ($this->currentIndex >= $this->totalQuestions) {
            $this->terminer();
            return;
        }
        $nextQuestionId = $this->questionIds[$this->currentIndex];
        $this->dispatch('question-ready', questionId: $nextQuestionId, position: $this->currentIndex);
    }

    private function insererTracking(array $data): void
    {
        if (empty($data) || !isset($data['id_question'])) return;

        $row = Tracking::create([
            'id_passation'        => null,
            'id_question'         => (int)   ($data['id_question']         ?? 0),
            'position'            => (int)   ($data['position'] + 1           ?? $this->currentIndex + 1),
            'temps_total_ms'      => (float) ($data['temps_total_ms']      ?? 0),
            'latence_ms'          => (float) ($data['latence_ms']          ?? 0),
            'nb_clics'            => (int)   ($data['nb_clics']            ?? 0),
            'nb_changements'      => (int)   ($data['nb_changements']      ?? 0),
            'nb_clics_hors_cible' => (int)   ($data['nb_clics_hors_cible'] ?? 0),
            'nb_pauses'           => (int)   ($data['nb_pauses']           ?? 0),
            'suivi_souris'        => $data['suivi_souris'] ?? null,
        ]);

        $this->trackingIds[] = $row->id;
    }

    private function terminer(): void
    {
        $beneficiaireId        = session('beneficiaire_id');
        $consentementRecherche = session('consentement_recherche', false);

        session()->forget(['beneficiaire_id', 'consentement_recherche']);

        $passation = Passation::create([
            'id_beneficiaire'        => $beneficiaireId,
            'id_travailleur'         => Auth::id(),
            'score'                  => $this->scores,
            'consentement_recherche' => $consentementRecherche,
            'mode_ordre'             => $this->modeOrdre,
            'date'                   => now()->toDateString(),
            'scenario'               => null,
            'modules'                => null,
        ]);
        if ($consentementRecherche && !empty($this->trackingIds)) {
            Tracking::whereIn('id', $this->trackingIds)
                ->update(['id_passation' => $passation->id]);
        }

        session()->flash('autoriser_resultat', $passation->id);
        $this->redirect(route('questionnaire.result', $passation->id));
    }

    public function render(): \Illuminate\View\View
    {
        $currentQuestion = null;
        if ($this->currentIndex < $this->totalQuestions) {
            $currentQuestion = Question::find($this->questionIds[$this->currentIndex]);
        }

        return view('livewire.questionnaire-run', [
            'currentQuestion' => $currentQuestion,
        ]);
    }
}
