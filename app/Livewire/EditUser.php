<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class EditUser extends Component
{
    public User $user;

    public string $name      = '';
    public string $firstname = '';
    public string $email     = '';
    public string $structure = '';
    public string $role      = '';

    public function mount(User $user): void
    {

        Gate::authorize('update', $this->user);

        $this->user      = $user;
        $this->name      = $user->name      ?? '';
        $this->firstname = $user->firstname ?? '';
        $this->email     = $user->email     ?? '';
        $this->structure = $user->structure ?? '';
        $this->role      = $user->role      ?? '';
    }

    public function save(): void
    {

        Gate::authorize('update', $this->user);

        $this->validate([
            'name'      => 'nullable|string|max:255',
            'firstname' => 'nullable|string|max:255',
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)],
            'structure' => 'nullable|string|max:255',
            'role'      => 'required|string|in:travailleur,gestionnaire,admin',
        ]);

        $this->user->update([
            'name'      => $this->name,
            'firstname' => $this->firstname,
            'email'     => $this->email,
            'structure' => $this->structure,
            'role'      => $this->role,
        ]);

        session()->flash('toast_message', 'Utilisateur mis à jour avec succès');
        session()->flash('toast_type', 'success');

        $this->redirect(route('backoffice'));
    }

    public function render()
    {
        return view('livewire.edit-user');
    }
}
