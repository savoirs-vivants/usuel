<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'gestionnaire';
    }

    public function view(User $user, User $model): bool
    {
        if ($user->role === 'gestionnaire') {
            return $user->structure === $model->structure && $model->role === 'travailleur';
        }

        if ($user->role === 'travailleur') {
            return $user->id === $model->id;
        }

        return false;
    }

    public function update(User $user, User $model): bool
    {
        if ($user->role === 'gestionnaire') {
            return $user->structure === $model->structure && $model->role === 'travailleur';
        }

        if ($user->role === 'travailleur') {
            return $user->id === $model->id;
        }

        return false;
    }

    public function delete(User $user, User $model): bool
    {
        return false;
    }
}
