<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class ModeSelector extends Component
{
    public string $mode = 'fixe';

    public function mount(): void
    {
        // On lit le cache plutôt que la BDD car ce paramètre est global et
        // consulté à chaque passation
        $this->mode = Cache::get('global_mode_ordre', 'fixe');
    }

    public function updatedMode($value): void
    {
        // La whitelist protège contre une valeur injectée manuellement via le DOM,
        $allowed = ['fixe', 'aleatoire', 'semi_aleatoire', 'carre_latin'];
        $newMode = in_array($value, $allowed, true) ? $value : 'fixe';

        // forever car ce réglage doit persister entre les sessions sans expiration ;
        Cache::forever('global_mode_ordre', $newMode);

        $nomMode = str_replace('_', ' ', $newMode);
        $this->dispatch('notify', message: "Le mode a été défini sur : " . ucfirst($nomMode));
    }

    public function render()
    {
        return view('livewire.mode-selector');
    }
}
