<?php

namespace App\Livewire;

use App\Models\Passation;
use App\Models\Question;
use Livewire\Component;

class QuestionnaireRun extends Component
{
    public int    $currentIndex   = 0;
    public int    $totalQuestions = 0;
    public string $selectedAnswer = '';
    public bool   $showError      = false;

    public array $scores = [
        'Resilience' => 0.0,
        'EC'         => 0.0,
        'CSDLEN'     => 0.0,
        'CT'         => 0.0,
        'TDLinfo'    => 0.0,
        'CDC'        => 0.0,
    ];

    public array $questionIds = [];


    public function mount(): void
    {
        $this->questionIds    = Question::orderBy('id')->pluck('id')->toArray();
        $this->totalQuestions = count($this->questionIds);
    }

    public function getCurrentQuestionProperty(): ?Question
    {
        if ($this->currentIndex >= $this->totalQuestions) {
            return null;
        }
        return Question::find($this->questionIds[$this->currentIndex]);
    }

    public function choisir(string $reponse): void
    {
        $this->selectedAnswer = $reponse;
        $this->showError      = false;
    }

    public function valider(): void
    {
        if ($this->selectedAnswer === '') {
            $this->showError = true;
            return;
        }
        $this->enregistrerReponse($this->selectedAnswer);
    }

    public function jeSaisPas(): void
    {
        $this->enregistrerReponse('E');
    }

    private function enregistrerReponse(string $reponse): void
    {
        $question = $this->currentQuestion;
        if (!$question) return;

        $poids = $question->getPoids($reponse);
        $cat   = $question->categorie;

        $this->scores[$cat] = round(($this->scores[$cat] ?? 0.0) + $poids, 2);

        $this->selectedAnswer = '';
        $this->showError      = false;
        $this->currentIndex++;

        if ($this->currentIndex >= $this->totalQuestions) {
            $this->terminer();
        }
    }

    private function terminer(): void
    {
        $beneficiaireId       = session('beneficiaire_id');
        $consentementRecherche = session('consentement_recherche', false);

        session()->forget(['beneficiaire_id', 'consentement_recherche']);

        $passation = Passation::create([
            'id_beneficiaire'       => $beneficiaireId,
            'id_travailleur'        => auth()->id(),
            'score'                 => $this->scores,  
            'consentement_recherche'=> $consentementRecherche,
            'mode_ordre'            => 'fixe',
            'date'                  => now()->toDateString(),
            'scenario'              => null,
            'modules'               => null,
        ]);

        $this->redirect(route('questionnaire.result', $passation->id));
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.questionnaire-run');
    }
}
