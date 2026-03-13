<?php

namespace App\Livewire;

use App\Models\Question;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Livewire\Forms\QuestionForm;

#[Layout('layouts.app')]
#[Title('Usuel - Gestion des questions')]
class GestionQuestions extends Component
{
    use WithFileUploads;

    public QuestionForm $form;

    public array  $questions    = [];
    public bool   $saved        = false;
    public string $savedMessage = '';
    public bool   $showPreview  = false;

    public array $poidsOptions = [
        '1'    => '+1 — Bonne réponse',
        '0.5'  => '+0.5 — Partielle',
        '0'    => '0 — Neutre',
        '-0.5' => '−0.5 — Partiellement mauvaise',
        '-1'   => '−1 — Mauvaise',
    ];

    public function mount(): void
    {
        $this->loadQuestions();

        // On pré-sélectionne la première question pour que l'interface
        // ne s'affiche jamais dans un état vide sans formulaire visible.
        if (!empty($this->questions)) {
            $this->selectQuestion($this->questions[0]['id']);
        }
    }

    private function loadQuestions(): void
    {
        // On ne sélectionne que les colonnes utiles à la liste latérale pour éviter
        // de charger les champs lourds (image, choix JSON...) inutilement.
        $this->questions = Question::orderBy('id')
            ->get(['id', 'intitule', 'categorie', 'active'])
            ->map(fn($q) => [
                'id'        => $q->id,
                'intitule'  => $q->intitule ?? '',
                'categorie' => $q->categorie ?? '',
                // Cast explicite en bool pour éviter qu'un "0"/"1" de la BDD
                // soit traité comme truthy/falsy de façon incohérente dans Blade.
                'active'    => (bool) $q->active,
            ])
            ->toArray();
    }

    public function toggleActive(int $id): void
    {
        $q = Question::findOrFail($id);
        $q->update(['active' => !$q->active]);
        $this->loadQuestions();
    }

    public function selectQuestion(int $id): void
    {
        $q = Question::findOrFail($id);
        $this->form->loadQuestion($q);
        $this->saved = false;
        $this->resetValidation();
    }

    public function nouvelleQuestion(): void
    {
        $this->form->resetForNew();
        $this->saved = false;
        $this->resetValidation();
    }

    public function updatedFormNbReponses($value): void
    {
        // On contraint la valeur entre 2 et 8 ici plutôt que seulement en validation
        // pour que l'UI se mette à jour immédiatement pendant la saisie, sans attendre
        // un submit qui afficherait une erreur après coup.
        $target = max(2, min(8, (int)$value));
        $this->form->nbReponses = $target;

        $current = count($this->form->choix);

        if ($target > $current) {
            for ($i = $current; $i < $target; $i++) {
                $this->form->choix[] = ['texte' => '', 'poids' => '0'];
            }
        } elseif ($target < $current) {
            $this->form->choix = array_slice($this->form->choix, 0, $target);
        }
    }

    public function toggleCorrect(int $index): void
    {
        if (($this->form->choix[$index]['poids'] ?? '0') === '1') {
            $this->form->choix[$index]['poids'] = '0';
        } else {
            $this->form->choix[$index]['poids'] = '1';
        }
    }

    public function supprimerImage(): void
    {
        $this->form->removeImage   = true;
        $this->form->existingImage = '';
        $this->form->newImage      = null;
    }

    public function sauvegarder(): void
    {
        $result = $this->form->save();

        $this->saved = true;
        $this->savedMessage = $result['message'];

        $this->loadQuestions();
    }

    public function previsualiser(): void
    {
        $this->showPreview = true;
    }

    public function fermerPrevisualisation(): void
    {
        $this->showPreview = false;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.gestion-questions');
    }
}
