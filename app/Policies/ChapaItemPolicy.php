<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ChapaItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChapaItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the chapaItem can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the chapaItem can view the model.
     */
    public function view(User $user, ChapaItem $model): bool
    {
        return true;
    }

    /**
     * Determine whether the chapaItem can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the chapaItem can update the model.
     */
    public function update(User $user, ChapaItem $model): bool
    {
        return true;
    }

    /**
     * Determine whether the chapaItem can delete the model.
     */
    public function delete(User $user, ChapaItem $model): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the chapaItem can restore the model.
     */
    public function restore(User $user, ChapaItem $model): bool
    {
        return false;
    }

    /**
     * Determine whether the chapaItem can permanently delete the model.
     */
    public function forceDelete(User $user, ChapaItem $model): bool
    {
        return false;
    }
}
