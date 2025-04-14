<?php

namespace App\Policies;

use App\Models\Video;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Video $video)
    {
        // Les vidéos publiées sont visibles par tous
        if ($video->is_published) {
            return true;
        }
        
        // Les vidéos non publiées ne sont visibles que par leur propriétaire ou les admins
        $contestant = $user->contestant;
        return $user->isAdmin() || ($contestant && $video->contestant_id === $contestant->id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Video $video)
    {
        // Les admins peuvent tout modifier
        if ($user->isAdmin()) {
            return true;
        }
        
        // Les utilisateurs ne peuvent modifier que leurs propres vidéos
        $contestant = $user->contestant;
        return $contestant && $video->contestant_id === $contestant->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Video $video)
    {
        // Les admins peuvent tout supprimer
        if ($user->isAdmin()) {
            return true;
        }
        
        // Les utilisateurs ne peuvent supprimer que leurs propres vidéos
        $contestant = $user->contestant;
        return $contestant && $video->contestant_id === $contestant->id;
    }
}
