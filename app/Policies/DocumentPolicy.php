<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function view(User $user, Document $document)
    {
        return $this->canAccess($user, $document);
    }

    public function update(User $user, Document $document)
    {
        return $this->canAccess($user, $document);
    }

    public function create(User $user)
    {
        return $user !== null;
    }

    public function delete(User $user, Document $document)
    {
        return $this->canAccess($user, $document);
    }

    public function restore(User $user, Document $document)
    {
        return $this->canAccess($user, $document);
    }

    private function canAccess(User $user, Document $document): bool
    {
        // Role IDs mapping
        $ADMIN = 1;
        $DIRECTOR = 5;
        $MANAGER = 3;

        // Admins have full access
        if ($user->role_id === $ADMIN) {
            return true;
        }

        // Directors or Managers can access documents in their department
        if (
            in_array($user->role_id, [$DIRECTOR, $MANAGER]) &&
            $user->department === $document->department
        ) {
            return true;
        }

        // Employees can only access their own documents
        return $user->id === $document->user_id;
    }

    public function approve(User $user, Document $document)
    {
        $ADMIN = 1;
        $DIRECTOR = 5;
        $MANAGER = 3;

        if ($user->role_id === $ADMIN) {
            return true;
        }

        if (
            in_array($user->role_id, [$DIRECTOR, $MANAGER]) &&
            $user->department === $document->department
        ) {
            return true;
        }

        return false;
    }

    public function reject(User $user, Document $document)
    {
        return $this->approve($user, $document);
    }
}
