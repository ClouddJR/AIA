<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $guarded = [];

    public function firstPlayer()
    {
        return $this->hasOne('App\User', 'id', 'player_1_id');
    }

    public function secondPlayer()
    {
        return $this->hasOne('App\User', 'id', 'player_2_id');
    }

    public function hasUser(User $user)
    {
        return $this->firstPlayer->id == $user->id || $this->secondPlayer->id == $user->id;
    }

    public function hasUserWithId($id)
    {
        return $this->firstPlayer->id == $id || $this->secondPlayer->id == $id;
    }

    public function didUserEnterResult(User $user)
    {
        if ($this->firstPlayer->id == $user->id && $this->player_1_result != 0) {
            return true;
        }

        if ($this->secondPlayer->id == $user->id && $this->player_2_result != 0) {
            return true;
        }

        return false;
    }

    public function winner()
    {
        return $this->player_1_result == $this->player_2_result
        && $this->player_1_result != 0
        && $this->player_2_result != 0
            ? User::find($this->player_1_result) : null;
    }

    public function hasConflictingResults()
    {
        return $this->player_1_result != 0 &&
            $this->player_2_result != 0 &&
            $this->player_1_result != $this->player_2_result;
    }

    public function resetResults()
    {
        $this->player_1_result = 0;
        $this->player_2_result = 0;
        $this->save();
    }
}
