<?php

namespace App\Policies;

use App\Models\Letter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LetterPolicy
{
    use HandlesAuthorization;
public function create(User $user)
{
    // For example, allow any authenticated user to create a letter
    return true;
}

    public function reply(User $user, Letter $letter)
    {
        // Example logic: only allow if user owns the letter or has some role
           return true;
    }


public function view(User $user, Letter $letter)
{
    if ($user->role_id == 5) {
        return true;
    }

    return $user->id === $letter->sender_id || $user->id === $letter->receiver_id;
}



    // Can update? Only sender and only if draft
    public function update(User $user, Letter $letter)
    {
        return $user->id === $letter->sender_id && $letter->status === 'draft';
    }

    // Can delete? Only sender and only if draft
    public function delete(User $user, Letter $letter)
    {
        return $user->id === $letter->sender_id && $letter->status === 'draft';
    }
}
