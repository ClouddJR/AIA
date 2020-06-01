<x-app>
    <div class="card-body w-75 mx-auto">
        <form method="POST" action="/tournaments/{{ $tournament->id }}/apply" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
                <label for="license"
                       class="col-md-4 col-form-label text-md-right">{{ __('License number*') }}</label>

                <div class="col-md-6">
                    <input id="license" type="text"
                           class="form-control @error('license') is-invalid @enderror" name="license"
                           value="{{ old('license') }}" required>

                    @error('license')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="ranking"
                       class="col-md-4 col-form-label text-md-right">{{ __('Ranking*') }}</label>

                <div class="col-md-6">
                    <input id="ranking" type="number"
                           class="form-control @error('ranking') is-invalid @enderror" name="ranking"
                           value="{{ old('ranking') }}"
                           min="1"
                           required>

                    @error('ranking')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Sign up') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app>
