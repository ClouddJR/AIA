<?php

namespace App\Policies;

use App\Game;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;

    public function enterResult(User $user, Game $game)
    {
        return $game->winner() == null && $game->hasUser($user) && !$game->didUserEnterResult($user);
    }
}
