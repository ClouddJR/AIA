<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Logo extends Model
{
    protected $guarded = [];

    public static function store($logo, $tournament)
    {
        $logo_uri = $logo->store('logos');
        Logo::create([
            'tournament_id' => $tournament->id,
            'uri' => $logo_uri
        ]);
    }

    public static function deletePreviousLogosFor($tournament)
    {
        $logos = Logo::where('tournament_id', $tournament->id)->get();
        if ($logos->count() > 0) {
            foreach ($logos as $logo) {
                Storage::delete($logo->uri);
            }
            $logos->each->delete();
        }
    }
}
