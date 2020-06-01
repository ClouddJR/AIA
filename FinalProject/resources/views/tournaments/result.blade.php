<x-app>
    <div class="card-body w-75 mx-auto">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(Session::has('result_conflict'))
            <div class="alert alert-danger">
                {{ session('result_conflict') }}
            </div>
        @endif

        <form method="POST" action="/tournaments/{{ $tournament->id }}/games/{{ $game->id }}/result">
            @csrf

            <div class="form-group row">
                <label for="id"
                       class="col-md-4 col-form-label text-md-right">{{ __('Winner') }}</label>

                <div class="col-md-6">
                    <select id="id" type="text" class="form-control" name="id" required>
                        <option value="{{ $game->firstPlayer->id }}">{{ $game->firstPlayer->name() }}</option>
                        <option value="{{ $game->secondPlayer->id }}">{{ $game->secondPlayer->name() }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save result') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app>
