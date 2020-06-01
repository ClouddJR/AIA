<x-app>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <span>Upcoming games</span>
                    </div>

                    <div class="card-body">
                        @forelse($user->upcomingGames()->groupBy('tournament_id') as $tournamentId => $games)
                            <h5 class="mb-3">Tournament: {{ App\Tournament::find($tournamentId)->name }}</h5>

                            @foreach($games as $game)
                                <p>
                                    {{ $game->firstPlayer->first_name . " "  . $game->firstPlayer->last_name }}
                                    vs.
                                    {{ $game->secondPlayer->first_name . " " . $game->secondPlayer->last_name}}

                                    @can('enterResult', $game)
                                        <a href="/tournaments/{{ $game->tournament_id }}/games/{{ $game->id }}/result">
                                            Enter the result
                                        </a>
                                    @endcan
                                </p>
                            @endforeach

                            <hr>
                        @empty
                            <p>No upcoming games.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app>
