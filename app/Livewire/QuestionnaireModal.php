<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use App\Livewire\Forms\QuestionnaireForm; 

class QuestionnaireModal extends Component
{
    public QuestionnaireForm $form;

    public bool $open = false;
    public int  $step = 1;

    public function ouvrir(): void
    {
        $this->form->reset();
        $this->form->langue = 'fr';
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
        $this->form->validateProfil();
        $this->step = 2;
    }

    public function confirmerTransmission(): void
    {
        $this->step = 3;
    }

    public function validerIdentite(): void
    {
        $this->form->validateIdentiteAndSave();
        $this->step = 4;
    }

    public function validerConsentement(): void
    {
        $this->form->validateConsentement();
        $this->redirect(route('questionnaire.run'), navigate: true);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.questionnaire-modal');
    }
}
