<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileForm extends Form
{
    public string $name = '';
    public string $firstname = '';
    public string $email = '';
    public string $structure = '';
    public string $password = '';
    public string $password_confirm = '';

    /**
     * Initialise le formulaire avec les données de l'utilisateur connecté.
     */
    public function setFromUser(User $user): void
    {
        $this->name = $user->name ?? '';
        $this->firstname = $user->firstname ?? '';
        $this->email = $user->email ?? '';
        $this->structure = $user->structure ?? '';
    }

    /**
     * Règles de validation dynamiques (l'email doit ignorer l'ID de l'utilisateur actuel).
     */
    protected function rules(): array
    {
        return [
            'name'             => 'nullable|string|max:255',
            'firstname'        => 'nullable|string|max:255',
            'email'            => ['required', 'email', Rule::unique('users', 'email')->ignore(Auth::id())],
            'structure'        => 'nullable|string|max:255',
            'password'         => 'nullable|string|min:8|same:password_confirm',
            'password_confirm' => 'nullable|string',
        ];
    }

    /**
     * Messages d'erreur personnalisés (Optionnel mais recommandé)
     */
    protected function messages(): array
    {
        return [
            'email.required'   => 'L\'adresse email est obligatoire.',
            'email.unique'     => 'Cette adresse email est déjà prise.',
            'password.min'     => 'Le mot de passe doit faire au moins 8 caractères.',
            'password.same'    => 'Les deux mots de passe ne correspondent pas.',
            'password_confirm.string' => 'La confirmation du mot de passe doit être une chaîne de caractères.',
            'password_confirm.nullable' => 'La confirmation du mot de passe est obligatoire.',
            'password.nullable' => 'Le mot de passe est obligatoire.',
            'password_confirm.required' => 'La confirmation du mot de passe est obligatoire.',
        ];
    }

    /**
     * Sauvegarde les modifications en base de données.
     */
    public function updateProfile(): void
    {
        $this->validate();

        /** @var User $user */
        $user = Auth::user();

        $user->name = $this->name;
        $user->firstname = $this->firstname;
        $user->email = $this->email;
        $user->structure = $this->structure;

        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        $this->password = '';
        $this->password_confirm = '';
    }
}
