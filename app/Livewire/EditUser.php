<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Livewire\Forms\EditUserForm;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Usuel - Modifier l\'utilisateur')]
class EditUser extends Component
{
    public User $user;
    public EditUserForm $form;

    public function mount(User $user): void
    {
        Gate::authorize('update', $user);

        $this->user = $user;
        $this->form->setUser($user);
    }

    public function save(): void
    {
        Gate::authorize('update', $this->user);

        $this->form->updateUser();

        session()->flash('toast_message', 'Utilisateur mis à jour avec succès');
        session()->flash('toast_type', 'success');

        $this->redirect(route('backoffice'));
    }

    public function render()
    {
        return view('livewire.edit-user');
    }
}
