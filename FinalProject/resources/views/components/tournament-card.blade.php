<div class="card {{ $class }}">
    <div class="card-body">

        <a href="/tournaments/{{ $tournament->id }}" class="text-dark card-link"><h5
                class="card-title mb-3">{{ $tournament->name }}</h5></a>

        <h6 class="card-subtitle mb-2 text-muted">
            Organizer: {{ $tournament->organizer->name() }}</h6>

        <h6 class="card-subtitle mb-2 text-muted">Remaining slots: {{ $tournament->remainingSlots() }}</h6>

        <h6 class="card-subtitle mb-2 text-muted">Application
            deadline: {{ $tournament->application_deadline->diffForHumans() }}</h6>

        <a href="/tournaments/{{ $tournament->id }}" class="card-link">Details</a>
    </div>
</div>
