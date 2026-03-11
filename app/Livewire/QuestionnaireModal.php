<?php

namespace App\Livewire;

use App\Models\Beneficiaire;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class QuestionnaireModal extends Component
{
    public bool   $open  = false;
    public int    $step  = 1;

    public string $genre   = '';
    public string $age     = '';
    public string $diplome = '';
    public string $csp     = '';
    public string $langue = 'fr';

    public string $nom    = '';
    public string $prenom = '';

    public bool $consentement = false;


    public function ouvrir(): void
    {
        $this->reset(['genre', 'age', 'diplome', 'csp', 'nom', 'prenom', 'consentement']);
        $this->langue = 'fr';
        $this->step = 1;
        $this->open = true;
    }

    public function fermer(): void
    {
        $this->open = false;
    }

    #[On('mode-changed')]
    public function setModeOrdre(string $mode): void
    {
        $allowed = ['fixe', 'aleatoire', 'semi_aleatoire', 'carre_latin'];
        $newMode = in_array($mode, $allowed, true) ? $mode : 'fixe';

        Cache::forever('global_mode_ordre', $newMode);

        $nomMode = str_replace('_', ' ', $newMode);
        $this->dispatch('notify', message: "Le mode d'ordre a été défini sur : " . ucfirst($nomMode));
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
            'age.required'     => "Veuillez sélectionner une tranche d'âge.",
            'diplome.required' => 'Veuillez sélectionner un niveau de diplôme.',
            'csp.required'     => 'Veuillez sélectionner une catégorie socio-professionnelle.',
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
            'langue'  => 'required|string',
        ], [
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.min'      => 'Le prénom doit comporter au moins 2 caractères.',
            'nom.required'    => 'Le nom est obligatoire.',
            'nom.min'         => 'Le nom doit comporter au moins 2 caractères.',
        ]);

        $prenomPropre = Str::title(trim($this->prenom));
        $nomPropre = Str::upper(trim($this->nom));

        $user = Auth::user();

        $beneficiaire = Beneficiaire::where('nom', $nomPropre)
            ->where('prenom', $prenomPropre)
            ->whereHas('passations.user', function ($query) use ($user) {
                $query->where('structure', $user->structure);
            })
            ->first();

        if ($beneficiaire) {
            $beneficiaire->update([
                'genre'   => $this->genre,
                'age'     => $this->age,
                'diplome' => $this->diplome,
                'csp'     => $this->csp,
            ]);
        }
        else {
            $beneficiaire = Beneficiaire::create([
                'nom'     => $nomPropre,
                'prenom'  => $prenomPropre,
                'genre'   => $this->genre,
                'age'     => $this->age,
                'diplome' => $this->diplome,
                'csp'     => $this->csp,
            ]);
        }

        session(['langue' => $this->langue]);
        session(['beneficiaire_id' => $beneficiaire->id]);
        session()->save();

        $this->step = 4;
    }

    public function validerConsentement(): void
    {
        $this->validate([
            'consentement' => 'boolean',
        ]);

        session(['consentement_recherche' => (bool) $this->consentement]);

        session()->forget('questionnaire_progress');

        $this->redirect(route('questionnaire.run'), navigate: true);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.questionnaire-modal');
    }
}
