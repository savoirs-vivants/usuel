<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\ProfileForm;

class EditProfile extends Component
{
    public ProfileForm $form;

    public function mount(): void
    {
        $this->form->setFromUser(Auth::user());
    }

    public function save(): void
    {
        $this->form->updateProfile();

        session()->flash('toast_message', 'Profil mis à jour avec succès');
        session()->flash('toast_type', 'success');

        $this->redirect(route('profile.edit'), navigate: false);
    }

    public function render()
    {
        return view('livewire.edit-profile');
    }
}
