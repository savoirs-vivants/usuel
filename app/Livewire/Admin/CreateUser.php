<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Component
{
    public $isOpen = false;

    public $name;
    public $firstname;
    public $email;
    public $role;
    public $structure;

    protected $rules = [
        'name'      => 'nullable|string|max:255',
        'firstname' => 'nullable|string|max:255',
        'email'     => 'required|email|unique:users,email',
        'role'      => 'required|string',
        'structure' => 'nullable|string|max:255',
    ];

    public function openModal()
    {
        $this->resetValidation();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['name', 'firstname', 'email', 'role', 'structure']);
    }

    public function save()
    {
        $this->validate();

        User::create([
            'name'      => $this->name,
            'firstname' => $this->firstname,
            'email'     => $this->email,
            'role'      => $this->role,
            'structure' => $this->structure,
            'password'  => Hash::make('motdepasse123'),
        ]);

        return redirect()->route('admin.backoffice');
    }

    public function render()
    {
        return view('livewire.admin.create-user');
    }
}
