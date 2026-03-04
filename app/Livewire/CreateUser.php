<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;
use Illuminate\Support\Facades\Auth;

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
        $currentUser = Auth::user();

        if ($currentUser->role === 'gestionnaire') {
            $this->structure = $currentUser->structure;
            $this->role = 'travailleur';
        }
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

        $token = Str::uuid()->toString();

        $user = User::create([
            'name'             => $this->name,
            'firstname'        => $this->firstname,
            'email'            => $this->email,
            'role'             => $this->role,
            'structure'        => $this->structure,
            'password'         => Hash::make(Str::random(32)),
            'invitation_token' => $token,
            'is_registered'    => false,
        ]);

        Mail::to($user->email)->send(new InvitationMail($user, $token));

        $email = $this->email;
        $this->closeModal();
        session()->flash('toast_message', 'Invitation envoyée à ' . $email);
        session()->flash('toast_type', 'success');
        return redirect()->route('backoffice');
    }

    public function render()
    {
        return view('livewire.create-user');
    }
}
