<?php

namespace App\Policies;

use App\Tournament;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TournamentPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Tournament $tournament)
    {
        return $user->id == $tournament->organizer_id;
    }

    public function create(User $user)
    {
        return $user->hasVerifiedEmail();
    }

    public function apply(User $user, Tournament $tournament)
    {
        return $tournament->remainingSlots() > 0 && !$tournament->participants->contains($user->id)
            && $tournament->application_deadline > now();
    }
}
