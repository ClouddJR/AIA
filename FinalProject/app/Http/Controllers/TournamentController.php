<?php

namespace App\Http\Controllers;

use App\Game;
use App\Jobs\SeedTournamentLadder;
use App\Logo;
use App\Tournament;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    public function show(Tournament $tournament)
    {
        return view('tournaments.show', compact('tournament'));
    }

    public function create()
    {
        return view('tournaments.create');
    }

    public function edit(Tournament $tournament)
    {
        return view('tournaments.edit', compact('tournament'));
    }

    public function store()
    {
        $attr = request()->validate([
            'name' => ['string', 'required', 'max:255'],
            'time' => ['date', 'required', 'after:today'],
            'location' => ['required'],
            'lat' => ['required'],
            'lng' => ['required'],
            'max_participants' => ['numeric', 'required', 'between:2,32'],
            'application_deadline' => ['date', 'required', 'before_or_equal:time', 'after:yesterday'],
            'logos.*' => ['file', 'mimes:jpeg,bmp,png']
        ]);

        $tournament = Tournament::create([
            'name' => $attr['name'],
            'discipline' => 'Table tennis',
            'organizer_id' => auth()->id(),
            'time' => $attr['time'],
            'location' => $attr['location'],
            'lat' => $attr['lat'],
            'lng' => $attr['lng'],
            'max_participants' => $attr['max_participants'],
            'application_deadline' => $attr['application_deadline'],
        ]);


        if (!empty(request()->file('logos'))) {
            $this->storeLogos(request()->file('logos'), $tournament);
        }

        $job = (new SeedTournamentLadder($tournament))->delay($tournament->time);
        dispatch($job);

        return redirect()->route('home');
    }

    private function storeLogos($logos, $tournament)
    {
        foreach ($logos as $logo) {
            Logo::store($logo, $tournament);
        }
    }

    public function update(Tournament $tournament)
    {
        $attr = request()->validate([
            'name' => ['string', 'required', 'max:255'],
            'time' => ['date', 'required', 'after:today'],
            'location' => ['required'],
            'lat' => ['required'],
            'lng' => ['required'],
            'max_participants' => ['numeric', 'required', 'between:2,32'],
            'application_deadline' => ['date', 'required', 'before_or_equal:time', 'after:yesterday'],
            'logos.*' => ['file', 'mimes:jpeg,bmp,png']
        ]);

        $tournament->update([
            'name' => $attr['name'],
            'time' => $attr['time'],
            'location' => $attr['location'],
            'lat' => $attr['lat'],
            'lng' => $attr['lng'],
            'max_participants' => $attr['max_participants'],
            'application_deadline' => $attr['application_deadline'],
        ]);

        if (!empty(request()->file('logos'))) {
            $this->updateLogos(request()->file('logos'), $tournament);
        }
        return redirect('tournaments/' . $tournament->id);
    }

    private function updateLogos($logos, $tournament)
    {
        Logo::deletePreviousLogosFor($tournament);
        foreach ($logos as $logo) {
            Logo::store($logo, $tournament);
        }
    }

    public function showApplyForm(Tournament $tournament)
    {
        return view('tournaments.apply', compact('tournament'));
    }

    public function apply(Tournament $tournament)
    {
        $attr = request()->validate([
            'license' => ['string', 'unique:participations'],
            'ranking' => ['numeric', 'min:1', 'unique:participations']
        ]);

        if ($tournament->participants->contains(auth()->id())) {
            return redirect('tournaments/' . $tournament->id)
                ->with('apply_error', 'You are already signed up');
        }

        try {
            DB::transaction(function () use ($attr, $tournament) {
                if ($tournament->remainingSlots() > 0) {
                    $tournament->participants()->attach(auth()->id(), [
                        'license' => $attr['license'],
                        'ranking' => $attr['ranking']
                    ]);
                } else {
                    return redirect('tournaments/' . $tournament->id)
                        ->with('apply_error', 'No remaining slots, someone was first :c');
                }
            });

        } catch (\Exception $e) {
            //in case of an error inside the transaction, an exception will be thrown
            return redirect('tournaments/' . $tournament->id)
                ->with('apply_error', 'No remaining slots, someone was first :c');
        }

        //at this point, everything was fine, so return with success
        return redirect('tournaments/' . $tournament->id)
            ->with('apply_success', 'Successfully signed up for the tournament');
    }

    public function showGameResultForm(Tournament $tournament, Game $game)
    {
        return view('tournaments.result', compact('tournament', 'game'));
    }

    public function storeGameResult(Tournament $tournament, Game $game)
    {
        $attr = request()->validate([
            'id' => [
                'required',
                'integer',
                'exists:users',
                function ($attribute, $value, $fail) use ($game) {
                    if (!$game->hasUserWithId($value)) {
                        $fail("This player does not participate in this game");
                    }
                }
            ],
        ]);

        $conflictOccurred = false;

        DB::transaction(function () use ($tournament, $game, $attr, &$conflictOccurred) {
            if ($game->firstPlayer->id == auth()->id()) {
                $game->player_1_result = (int)$attr['id'];
            }

            if ($game->secondPlayer->id == auth()->id()) {
                $game->player_2_result = (int)$attr['id'];
            }

            $game->save();

            if ($game->hasConflictingResults()) {
                $game->resetResults();
                $conflictOccurred = true;
            }

            if ($tournament->allGamesForThisRoundHasBeenPlayed($game->round)) {
                $tournament->seedNextRound($game->round);
            }
        });

        if ($conflictOccurred) {
            return redirect("/tournaments/$tournament->id/games/$game->id/result")
                ->with('result_conflict', 'Players submitted conflicting results, please try again');
        } else {
            return redirect('/tournaments/' . $tournament->id)
                ->with('result_success', 'Successfully submitted a game result');
        }
    }

    public function showUpcomingGames()
    {
        $user = auth()->user();
        return view('tournaments.upcoming-games', compact('user'));
    }

    public function showOwnedTournaments()
    {
        $user = auth()->user();
        $tournaments = $user->ownedTournaments()->paginate(10);
        return view('tournaments.owned', compact('tournaments'));
    }
}
