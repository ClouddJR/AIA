<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

Route::get('/', 'HomeController@index')
    ->name('home');

Route::get('/tournaments/create', 'TournamentController@create')
    ->middleware('verified');

Route::get('/tournaments/owned', 'TournamentController@showOwnedTournaments')
    ->middleware('verified');

Route::get("/tournaments/{tournament}/games/{game}/result", "TournamentController@showGameResultForm")
    ->middleware(['can:enterResult,game', 'verified']);

Route::post("/tournaments/{tournament}/games/{game}/result", "TournamentController@storeGameResult")
    ->middleware(['can:enterResult,game', 'verified']);

Route::get('/tournaments/{tournament}/edit', 'TournamentController@edit')
    ->middleware(['can:update,tournament', 'verified']);

Route::get('/tournaments/{tournament}/apply', 'TournamentController@showApplyForm')
    ->middleware(['can:apply,tournament', 'verified']);

Route::post('/tournaments/{tournament}/apply', 'TournamentController@apply')
    ->middleware(['can:apply,tournament', 'verified']);

Route::get('/tournaments/{tournament}', 'TournamentController@show');

Route::post('/tournaments', 'TournamentController@store')
    ->middleware(['verified']);

Route::put('/tournaments/{tournament}', 'TournamentController@update')
    ->middleware(['can:update,tournament', 'verified']);

Route::get('/games/upcoming', 'TournamentController@showUpcomingGames')
    ->middleware('verified');

