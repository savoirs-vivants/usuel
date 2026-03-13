<?php

namespace App\Livewire;

use App\Models\Passation;
use App\Models\Question;
use App\Models\Tracking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Url;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Usuel - Usuel - Passation du questionnaire')]
class QuestionnaireRun extends Component
{
    // #[Url] permet de reprendre la passation à la bonne question si l'utilisateur
    // rafraîchit la page, sans perdre sa progression.
    #[Url(as: 'step')]
    public int    $currentIndex   = 0;
    public int    $totalQuestions = 0;
    public string $selectedAnswer = '';
    public bool   $showError      = false;
    public string $modeOrdre      = 'fixe';
    public bool   $showEndModal   = false;
    public ?int   $completedPassationId = null;
    public string $langue = 'fr';
    public bool   $audio  = false;

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

        $this->langue = session('langue', 'fr');
        $this->audio  = (bool) session('audio', false);

        $progress = session()->get('questionnaire_progress');

        // On restaure la progression existante pour gérer les rechargements de page
        // ou les coupures réseau sans repartir de zéro.
        if ($progress && count($progress['questionIds']) > 0) {
            $this->currentIndex = $progress['currentIndex'];
            $this->scores       = $progress['scores'];
            $this->questionIds  = $progress['questionIds'];
            $this->trackingIds  = $progress['trackingIds'];
            $this->modeOrdre    = $progress['modeOrdre'];
            $this->langue       = $progress['langue'] ?? 'fr';
            $this->audio        = (bool) ($progress['audio'] ?? false);
        } else {
            $mode = Cache::get('global_mode_ordre', 'fixe');
            $this->modeOrdre = $mode;
            $this->questionIds = $this->getOrderedQuestionIds($mode);
            $this->currentIndex = 0;

            $this->saveProgress();
        }

        $this->totalQuestions = count($this->questionIds);
    }

    private function saveProgress(): void
    {
        session()->put('questionnaire_progress', [
            'currentIndex' => $this->currentIndex,
            'scores'       => $this->scores,
            'questionIds'  => $this->questionIds,
            'trackingIds'  => $this->trackingIds,
            'modeOrdre'    => $this->modeOrdre,
            'langue'       => $this->langue,
            'audio'        => $this->audio,
        ]);
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
        return collect($ids)->shuffle()->values()->toArray();
    }

    private function modeSemiAleatoire(array $ids): array
    {
        // On fixe la première et la dernière question pour assurer une cohérence
        // d'entrée/sortie du questionnaire, seul le milieu est mélangé.
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

        // Le modulo sur le nombre total de passations fait tourner l'ordre des catégories
        // à chaque nouvelle passation, équilibrant l'exposition sur l'ensemble des utilisateurs.
        $offset   = Passation::count() % $n;

        $rotated  = array_merge(
            array_slice($categories, $offset),
            array_slice($categories, 0, $offset)
        );

        $result = [];
        foreach ($rotated as $cat) {
            $result = array_merge($result, $byCategory[$cat]->pluck('id')->shuffle()->toArray());
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

        // 'E' est la lettre réservée pour "Je ne sais pas", traitée comme une réponse
        // neutre par getPoids() sans impacter positivement ou négativement le score.
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
        $this->saveProgress();

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

        // id_passation est null à l'insertion car la passation n'existe pas encore ;
        // elle sera rattachée en masse dans terminer() une fois l'ID connu.
        $row = Tracking::create([
            'id_passation'        => null,
            'id_question'         => (int)   ($data['id_question']         ?? 0),
            'position'            => (int)   ($data['position'] + 1        ?? $this->currentIndex + 1),
            'temps_total_ms'      => (float) ($data['temps_total_ms']      ?? 0),
            'latence_ms'          => (float) ($data['latence_ms']          ?? 0),
            'nb_clics'            => (int)   ($data['nb_clics']            ?? 0),
            'nb_changements'      => (int)   ($data['nb_changements']      ?? 0),
            'nb_clics_hors_cible' => (int)   ($data['nb_clics_hors_cible'] ?? 0),
            'nb_pauses'           => (int)   ($data['nb_pauses']           ?? 0),
            'suivi_souris'        => $data['suivi_souris'] ?? null,
        ]);

        $this->trackingIds[] = $row->id;
        $this->saveProgress();
    }

    private function terminer(): void
    {
        $beneficiaireId        = session('beneficiaire_id');
        $consentementRecherche = session('consentement_recherche', false);
        $langueChoisie         = session('langue', 'fr');
        $audioChoisi           = (bool) session('audio', false);

        // On purge la session avant de créer la passation pour éviter qu'un rechargement
        // de page après une erreur réseau ne crée une deuxième passation en doublon.
        session()->forget(['beneficiaire_id', 'consentement_recherche', 'questionnaire_progress', 'langue', 'audio']);

        $passation = Passation::create([
            'id_beneficiaire'        => $beneficiaireId,
            'id_travailleur'         => Auth::id(),
            'langue'                 => $langueChoisie,
            'audio'                  => $audioChoisi,
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

        // flash() pour que la page de résultat puisse vérifier
        // que l'accès est légitime, sans laisser la valeur en session indéfiniment.
        session()->flash('autoriser_resultat', $passation->id);
        $this->completedPassationId = $passation->id;
        $this->showEndModal = true;
    }

    private function traduire(string $texte): string
    {
        if ($this->langue === 'fr' || empty(trim($texte))) {
            return $texte;
        }

        // On met en cache la traduction pour éviter un appel API Google à chaque
        // render, ce qui serait coûteux et soumis aux limites de rate limiting.
        $cacheKey = "trans_{$this->langue}_" . md5($texte);

        return Cache::rememberForever($cacheKey, function () use ($texte) {
            $tr = new GoogleTranslate();
            $tr->setSource('fr');
            $tr->setTarget($this->langue);
            return $tr->translate($texte);
        });
    }

    public function validerFinEtRediriger(): void
    {
        if ($this->completedPassationId) {
            $this->redirect(route('questionnaire.result', $this->completedPassationId));
        }
    }

    public function render(): \Illuminate\View\View
    {
        $currentQuestion = null;
        $translatedIntitule = '';
        $translatedChoix = [];

        if ($this->currentIndex < $this->totalQuestions) {
            $currentQuestion = Question::find($this->questionIds[$this->currentIndex]);

            if ($currentQuestion) {
                $translatedIntitule = $this->traduire($currentQuestion->intitule);

                foreach ($currentQuestion->choixSansE as $lettre => $choixData) {
                    $texteOriginal = is_array($choixData) ? ($choixData['texte'] ?? '') : $choixData;

                    $translatedChoix[$lettre] = is_array($choixData)
                        ? array_merge($choixData, ['texte' => $this->traduire($texteOriginal)])
                        : $this->traduire($texteOriginal);
                }
            }
        }

        return view('livewire.questionnaire-run', [
            'currentQuestion'    => $currentQuestion,
            'translatedIntitule' => $translatedIntitule,
            'translatedChoix'    => $translatedChoix,
            'btnJeSaisPas'       => $this->traduire('Je ne sais pas'),
            'btnValider'         => $this->traduire('Valider'),
        ]);
    }
}
