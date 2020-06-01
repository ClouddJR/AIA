<?php

namespace App\Http\Controllers;

use App\Tournament;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('query');
        $tournaments = Tournament::where('name', 'like', "%$query%")
            ->orderBy('time')
            ->paginate(10);

        return view('home', [
            'query' => $query,
            'tournaments' => $tournaments
        ]);
    }
}
