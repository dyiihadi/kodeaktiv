<?php

namespace App\Policies;

use App\Models\ProjectFile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectFilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectFile  $file
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProjectFile $file)
    {
        return $user->id === $file->user_id || $user->id === $file->project->owner_id;
    }
}
