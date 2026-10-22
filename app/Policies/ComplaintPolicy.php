<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Complaint $complaint): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $complaint->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * Students may only edit their own complaints while status is 'Pending'.
     */
    public function update(User $user, Complaint $complaint): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $complaint->user_id === $user->id && $complaint->isPending();
    }

    /**
     * Determine whether the user can delete the model.
     * Students may only delete their own complaints while status is 'Pending'.
     */
    public function delete(User $user, Complaint $complaint): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $complaint->user_id === $user->id && $complaint->isPending();
    }
}
