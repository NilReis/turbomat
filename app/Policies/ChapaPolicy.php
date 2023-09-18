<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Chapa;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChapaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the chapa can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the chapa can view the model.
     */
    public function view(User $user, Chapa $model): bool
    {
        return true;
    }

    /**
     * Determine whether the chapa can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the chapa can update the model.
     */
    public function update(User $user, Chapa $model): bool
    {
        return true;
    }

    /**
     * Determine whether the chapa can delete the model.
     */
    public function delete(User $user, Chapa $model): bool
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
     * Determine whether the chapa can restore the model.
     */
    public function restore(User $user, Chapa $model): bool
    {
        return false;
    }

    /**
     * Determine whether the chapa can permanently delete the model.
     */
    public function forceDelete(User $user, Chapa $model): bool
    {
        return false;
    }
}
