<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Question;

class QuestionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Question $question): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Question $question): bool
    {
        return $user->role === 'admin';
    }
}
