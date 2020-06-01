<x-app>
    <div class="container">
        <a href="{{ route('home') }}">
            <button class="btn btn-link btn-lg mb-4" type="button">List of tournaments</button>
        </a>

        @if(Session::has('apply_success'))
            <div class="alert alert-success" role="alert">
                {{session('apply_success')}}
            </div>
        @endif

        @if(Session::has('apply_error'))
            <div class="alert alert-danger" role="alert">
                {{session('apply_error')}}
            </div>
        @endif

        @if(Session::has('result_success'))
            <div class="alert alert-success" role="alert">
                {{session('result_success')}}
            </div>
        @endif

        <div class="row justify-content-center">

            @if($tournament->games->count() > 0)
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            Game results
                        </div>
                        <div class="card-body">
                            @foreach($tournament->games->groupBy('round') as $round => $games)
                                <h4 class="mb-4">Round {{ $round }}:</h4>

                                @foreach($games as $game)
                                    <p>
                                        {{ $game->firstPlayer->first_name . " "  . $game->firstPlayer->last_name }}
                                        vs.
                                        @if($game->secondPlayer)
                                        {{ $game->secondPlayer->first_name . " " . $game->secondPlayer->last_name}}
                                        @else
                                            None (free pass)
                                        @endif

                                        <span class="font-weight-bold">
                                            {{ $game->winner() ?  "winner: " . $game->winner()->name(): "" }}
                                        </span>
                                        @can('enterResult', $game)
                                            <a href="/tournaments/{{ $tournament->id }}/games/{{ $game->id }}/result">
                                                Enter the result
                                            </a>
                                        @endcan
                                    </p>
                                @endforeach

                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            Tournament's details
                            @can('update', $tournament)
                                <a href="/tournaments/{{ $tournament->id }}/edit" class="ml-2">Edit</a>
                            @endcan
                        </div>
                        @can('apply', $tournament)
                            <a href="/tournaments/{{ $tournament->id }}/apply" class="h5 mb-0">Sign up</a>
                        @endcan
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Title</h5>
                        <p class="card-text">{{ $tournament->name }}</p>

                        <hr/>

                        <h5 class="card-title">Discipline</h5>
                        <p class="card-text">{{ $tournament->discipline }}</p>

                        <hr/>

                        <h5 class="card-title">Organizer</h5>
                        <p class="card-text">{{ $tournament->organizer->name() }}</p>

                        <hr/>

                        <h5 class="card-title">Time</h5>
                        <p class="card-text">{{ $tournament->time->format('Y-m-d, H:i') }}</p>

                        <hr/>

                        <h5 class="card-title">Max participants</h5>
                        <p class="card-text">{{ $tournament->max_participants }}</p>

                        <hr/>

                        <h5 class="card-title">Remaining slots</h5>
                        <p class="card-text">{{ $tournament->remainingSlots() }}</p>

                        <hr/>

                        <h5 class="card-title">Application deadline</h5>
                        <p class="card-text">{{ $tournament->application_deadline->format('Y-m-d, H:i') }}
                            ({{ $tournament->application_deadline->diffForHumans() }})</p>

                        <hr/>

                        @if($tournament->logos()->count() > 0)

                            <h5 class="card-title">Sponsor logos</h5>

                            @foreach($tournament->logos() as $logo)
                                <img width="100" height="100" src="{{ asset("storage/$logo->uri") }}"
                                     alt="logo"
                                     class="rounded-circle">
                            @endforeach

                            <hr/>
                        @endif


                        <h5 class="card-title">Location</h5>
                        <p class="card-text">{{ $tournament->location }}</p>
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let lat = Number({!! json_encode($tournament->lat, JSON_HEX_TAG) !!});
        let lng = Number({!! json_encode($tournament->lng, JSON_HEX_TAG) !!});
        console.log(lat, lng)

        function initMap() {
            const position = {lat: lat, lng: lng};
            const map = new google.maps.Map(
                document.getElementById('map'), {zoom: 10, center: position});
            const marker = new google.maps.Marker({position: position, map: map});
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyY66YvoIgFjKWsAzIepBRk9A0M0dfA9I&callback=initMap">
    </script>
</x-app>
