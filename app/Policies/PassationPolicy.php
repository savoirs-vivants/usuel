<?php

namespace App\Policies;

use App\Models\Passation;
use App\Models\User;

class PassationPolicy
{
    public function view(User $user, Passation $passation): bool
    {
        if ($user->role === 'travailleur') {
            return $user->id === $passation->id_travailleur;
        }

        if ($user->role === 'gestionnaire') {
            return $passation->user && $user->structure === $passation->user->structure;
        }

        return false;
    }

    public function delete(User $user, Passation $passation): bool
    {
        return true;

    }
}
