<?php

namespace App\Policies;

use App\Models\Spk;
use App\Models\User;

class SpkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view pending SPKs.
     */
    public function viewPending(User $user): bool
    {
        return $user->isOwner();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Spk $spk): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isOwner() || $user->isOperasional();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Spk $spk): bool
    {
        // Can only update pending or approved SPKs
        if (!in_array($spk->status, ['pending', 'approved'])) {
            return false;
        }

        // Owner can update any
        if ($user->isOwner()) {
            return true;
        }

        // Creator can update their own pending SPKs
        return $user->id === $spk->created_by && $spk->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Spk $spk): bool
    {
        // Can only delete pending SPKs
        if ($spk->status !== 'pending') {
            return false;
        }

        // Owner can delete any
        if ($user->isOwner()) {
            return true;
        }

        // Creator can delete their own
        return $user->id === $spk->created_by;
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Spk $spk): bool
    {
        return $user->isOwner() && $spk->status === 'pending';
    }

    /**
     * Determine whether the user can start production.
     */
    public function start(User $user, Spk $spk): bool
    {
        if ($spk->status !== 'approved') {
            return false;
        }

        return $user->isOwner() || $user->isOperasional();
    }

    /**
     * Determine whether the user can complete production.
     */
    public function complete(User $user, Spk $spk): bool
    {
        if ($spk->status !== 'in_progress') {
            return false;
        }

        return $user->isOwner() || $user->isOperasional();
    }
}
