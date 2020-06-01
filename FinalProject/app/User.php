<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function name()
    {
        return "$this->first_name $this->last_name";
    }

    public function upcomingGames()
    {
        return $this->gamesForWhichWeAreFirstPlayers->merge($this->gamesForWhichWeAreSecondPlayers);
    }

    public function ownedTournaments()
    {
        return $this->hasMany('App\Tournament', 'organizer_id');
    }

    public function gamesForWhichWeAreFirstPlayers()
    {
        return $this->hasMany('App\Game', 'player_1_id')
            ->where('player_1_result', 0);
    }

    public function gamesForWhichWeAreSecondPlayers()
    {
        return $this->hasMany('App\Game', 'player_2_id')
            ->where('player_2_result', 0);
    }
}
