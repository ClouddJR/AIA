<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tournament extends Model
{
    protected $dates = ['created_at', 'updated_at', 'time', 'application_deadline'];

    protected $guarded = [];

    public function organizer()
    {
        return $this->belongsTo('App\User', 'organizer_id');
    }

    public function logos()
    {
        return DB::table('logos')->where('tournament_id', $this->id)->get();
    }

    public function remainingSlots()
    {
        $participants = $this->belongsToMany('App\User', 'participations')->count();
        return $this->max_participants - $participants;
    }

    public function participants()
    {
        return $this->belongsToMany('App\User', 'participations')
            ->withPivot('license', 'ranking')->withTimestamps();
    }

    public function games()
    {
        return $this->hasMany('App\Game');
    }

    public function allGamesForThisRoundHasBeenPlayed($round)
    {
        foreach ($this->games->where('round', $round) as $game) {
            if ($game->winner() == null) {
                return false;
            }
        }
        return true;
    }

    public function seedFirstRound()
    {
        $participantsArray = $this->participants->shuffle()->toArray();
        $this->seedRound(1, $participantsArray);
    }

    public function seedNextRound($previousRound)
    {
        $winnersFromPreviousRound = $this->participants->whereIn('id', $this->games->where('round', $previousRound)
            ->pluck('player_1_result'));

        $winnersArray = $winnersFromPreviousRound->shuffle()->toArray();
        if (count($winnersArray) >= 2) {
            $this->seedRound($previousRound + 1, $winnersArray);
        }
    }

    private function seedRound($round, $participantsArray)
    {
        //if the number of players is odd, give the best player a free pass
        if (count($participantsArray) % 2 != 0) {
            usort($participantsArray, function ($a, $b) {
                if ($a['pivot']['ranking'] == $b['pivot']['ranking']) {
                    return 0;
                }
                return ($a['pivot']['ranking'] < $b['pivot']['ranking']) ? -1 : 1;
            });

            $playerWithBiggestRank = $participantsArray[0];

            unset($participantsArray[0]);

            Game::create([
                'tournament_id' => $this->id,
                'round' => $round,
                'player_1_id' => $playerWithBiggestRank['id'],
                'player_2_id' => null,
                'player_1_result' => $playerWithBiggestRank['id'],
                'player_2_result' => $playerWithBiggestRank['id'],
            ]);

            shuffle($participantsArray);
        }

        for ($i = 0; $i < count($participantsArray); $i += 2) {
            Game::create([
                'tournament_id' => $this->id,
                'round' => $round,
                'player_1_id' => $participantsArray[$i]['id'],
                'player_2_id' => $participantsArray[$i + 1]['id'],
                'player_1_result' => 0,
                'player_2_result' => 0,
            ]);
        }
    }
}
