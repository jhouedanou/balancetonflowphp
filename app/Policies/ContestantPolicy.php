<?php

namespace App\Policies;

use App\Models\Contestant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContestantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contestant  $contestant
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Contestant $contestant)
    {
        // Allow if user is admin or if the contestant is associated with this user
        return $user->isAdmin() || $contestant->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contestant  $contestant
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Contestant $contestant)
    {
        // Only admins can delete contestants
        return $user->isAdmin();
    }
}
