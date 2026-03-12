<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Beneficiaire;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class QuestionnaireForm extends Form
{
    public string $genre = '';
    public string $age = '';
    public string $diplome = '';
    public string $csp = '';
    public string $langue = 'fr';
    public bool $audio = false;

    public string $nom = '';
    public string $prenom = '';

    public bool $consentement = false;

    /**
     * Valide l'étape 1 (Profil socio-démographique)
     */
    public function validateProfil(): void
    {
        $this->validate([
            'genre'   => 'required|in:homme,femme,autre,non_precise',
            'age'     => 'required|in:moins_18,18_25,26_35,36_45,46_55,56_65,plus_65',
            'diplome' => 'required|in:aucun,brevet,cap_bep,bac,bac2,bac3,bac5,doctorat',
            'csp'     => 'required|in:agriculteur,artisan,cadre,intermediaire,employe,ouvrier,retraite,sans_activite,autre',
        ], [
            'genre.required'   => 'Veuillez sélectionner un genre.',
            'age.required'     => "Veuillez sélectionner une tranche d'âge.",
            'diplome.required' => 'Veuillez sélectionner un niveau de diplôme.',
            'csp.required'     => 'Veuillez sélectionner une catégorie socio-professionnelle.',
        ]);
    }

    /**
     * Valide l'étape 3 (Identité) et enregistre le bénéficiaire en session
     */
    public function validateIdentiteAndSave(): void
    {
        $this->validate([
            'prenom' => 'required|string|min:2|max:60',
            'nom'    => 'required|string|min:2|max:60',
            'langue' => 'required|string',
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
        } else {
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
        session(['audio'  => $this->audio]);
        session(['beneficiaire_id' => $beneficiaire->id]);
        session()->save();
    }

    /**
     * Valide l'étape 4 (Consentement)
     */
    public function validateConsentement(): void
    {
        $this->validate([
            'consentement' => 'boolean',
        ]);

        session(['consentement_recherche' => (bool) $this->consentement]);
        session()->forget('questionnaire_progress');
    }
}
