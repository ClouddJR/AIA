<x-app>
    <div class="card-body w-75 mx-auto">
        <form method="POST" action="/tournaments" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
                <label for="name"
                       class="col-md-4 col-form-label text-md-right">{{ __('Name*') }}</label>

                <div class="col-md-6">
                    <input id="name" type="text"
                           class="form-control @error('name') is-invalid @enderror" name="name"
                           value="{{ old('name') }}" required>

                    @error('name')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="time"
                       class="col-md-4 col-form-label text-md-right">{{ __('Time*') }}</label>

                <div class="col-md-6">
                    <input id="time" type="datetime-local"
                           class="form-control @error('time') is-invalid @enderror" name="time"
                           value="{{ old('time') }}"
                           required>

                    @error('time')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="location"
                       class="col-md-4 col-form-label text-md-right">{{ __('Location*') }}</label>

                <div class="col-md-6">
                    <input id="location" type="text"
                           class="form-control @error('location') is-invalid @enderror" name="location"
                           value="{{ old('location') }}"
                           required>

                    @error('location')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row d-none">
                <div class="col-md-6">
                    <input id="lat" type="text"
                           name="lat"
                           value="{{ old('lat') }}"
                           required>
                </div>
            </div>

            <div class="form-group row d-none">
                <div class="col-md-6">
                    <input id="lng" type="text"
                           name="lng"
                           value="{{ old('lng') }}"
                           required>
                </div>
            </div>

            <div class="form-group row">
                <label for="max_participants"
                       class="col-md-4 col-form-label text-md-right">{{ __('Max participants*') }}</label>

                <div class="col-md-6">
                    <input id="max_participants" type="number"
                           class="form-control @error('max_participants') is-invalid @enderror" name="max_participants"
                           min="2"
                           max="32"
                           value="{{ old('max_participants') }}"
                           required>

                    @error('max_participants')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="application_deadline"
                       class="col-md-4 col-form-label text-md-right">{{ __('Application deadline*') }}</label>

                <div class="col-md-6">
                    <input id="application_deadline" type="datetime-local"
                           class="form-control @error('application_deadline') is-invalid @enderror"
                           name="application_deadline"
                           value="{{ old('application_deadline') }}"
                           required>

                    @error('application_deadline')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="logos[]"
                       class="col-md-4 col-form-label text-md-right">{{ __('Sponsor logos (optional)') }}</label>

                <div class="col-md-6">
                    <input id="logos[]" type="file"
                           class="form-control-file @error('logos') is-invalid @enderror"
                           name="logos[]"
                           accept="image/*"
                           multiple>

                    @error('logos')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Create') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyY66YvoIgFjKWsAzIepBRk9A0M0dfA9I&libraries=places">
    </script>

    <script type="text/javascript">
        google.maps.event.addDomListener(window, 'load', function () {
            const autocomplete = new google.maps.places.Autocomplete(document.getElementById('location'));

            autocomplete.addListener('place_changed', onPlaceChanged);

            function onPlaceChanged() {
                var place = autocomplete.getPlace();
                document.getElementById('lat').value = place.geometry.location.lat()
                document.getElementById('lng').value = place.geometry.location.lng()
            }
        });

    </script>
</x-app>
