<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class ModeSelector extends Component
{
    public string $mode = 'fixe';

    public function mount(): void
    {
        $this->mode = Cache::get('global_mode_ordre', 'fixe');
    }

    public function updatedMode($value): void
    {
        $allowed = ['fixe', 'aleatoire', 'semi_aleatoire', 'carre_latin'];
        $newMode = in_array($value, $allowed, true) ? $value : 'fixe';

        Cache::forever('global_mode_ordre', $newMode);

        $nomMode = str_replace('_', ' ', $newMode);
        $this->dispatch('notify', message: "Le mode a été défini sur : " . ucfirst($nomMode));
    }

    public function render()
    {
        return view('livewire.mode-selector');
    }
}
