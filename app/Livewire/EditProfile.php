<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EditProfile extends Component
{
    public string $name      = '';
    public string $firstname = '';
    public string $email     = '';
    public string $structure = '';
    public string $password         = '';
    public string $password_confirm = '';

    public function mount(): void
    {
        $user            = Auth::user();
        $this->name      = $user->name      ?? '';
        $this->firstname = $user->firstname ?? '';
        $this->email     = $user->email     ?? '';
        $this->structure = $user->structure ?? '';
    }

    public function save(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'name'             => 'nullable|string|max:255',
            'firstname'        => 'nullable|string|max:255',
            'email'            => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'structure'        => 'nullable|string|max:255',
            'password'         => 'nullable|string|min:8|same:password_confirm',
            'password_confirm' => 'nullable|string',
        ]);

        $user->name      = $this->name;
        $user->firstname = $this->firstname;
        $user->email     = $this->email;
        $user->structure = $this->structure;

        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        $this->password         = '';
        $this->password_confirm = '';

        session()->flash('toast_message', 'Profil mis à jour avec succès');
        session()->flash('toast_type', 'success');

        $this->redirect(route('profile.edit'), navigate: false);
    }

    public function render()
    {
        return view('livewire.edit-profile');
    }
}
