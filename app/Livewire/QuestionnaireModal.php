<?php

// app/Livewire/QuestionnaireModal.php

namespace App\Livewire;

use Livewire\Component;

class QuestionnaireModal extends Component
{
    public bool $open = false;

    public int $step = 1;

    public string $genre    = '';
    public string $age      = '';
    public string $diplome  = '';
    public string $csp      = '';

    public string $nom    = '';
    public string $prenom = '';

    public bool $consentement = false;

    public function ouvrir(): void
    {
        $this->reset(['genre', 'age', 'diplome', 'csp', 'nom', 'prenom', 'consentement']);
        $this->step = 1;
        $this->open = true;
    }

    public function fermer(): void
    {
        $this->open = false;
    }

    public function validerProfil(): void
    {
        $this->validate([
            'genre'   => 'required|in:homme,femme,autre,non_precise',
            'age'     => 'required|in:moins_18,18_25,26_35,36_45,46_55,56_65,plus_65',
            'diplome' => 'required|in:aucun,brevet,cap_bep,bac,bac2,bac3,bac5,doctorat',
            'csp'     => 'required|in:agriculteur,artisan,cadre,intermediaire,employe,ouvrier,retraite,sans_activite',
        ], [
            'genre.required'   => 'Veuillez sélectionner un genre.',
            'genre.in'         => 'Valeur invalide.',
            'age.required'     => 'Veuillez sélectionner une tranche d\'âge.',
            'age.in'           => 'Valeur invalide.',
            'diplome.required' => 'Veuillez sélectionner un niveau de diplôme.',
            'diplome.in'       => 'Valeur invalide.',
            'csp.required'     => 'Veuillez sélectionner une catégorie socio-professionnelle.',
            'csp.in'           => 'Valeur invalide.',
        ]);

        $this->step = 2;
    }

    public function confirmerTransmission(): void
    {
        $this->step = 3;
    }

    public function validerIdentite(): void
    {
        $this->validate([
            'prenom' => 'required|string|min:2|max:60',
            'nom'    => 'required|string|min:2|max:60',
        ], [
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.min'      => 'Le prénom doit comporter au moins 2 caractères.',
            'nom.required'    => 'Le nom est obligatoire.',
            'nom.min'         => 'Le nom doit comporter au moins 2 caractères.',
        ]);

        $this->step = 4;
    }

    public function validerConsentement(): void
    {
        $this->validate([
            'consentement' => 'accepted',
        ], [
            'consentement.accepted' => 'Vous devez accepter pour continuer.',
        ]);

        $this->redirect(route('questionnaire.run'), navigate: true);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.questionnaire-modal');
    }
}
