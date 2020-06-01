<x-app>
    <div class="container">
        <div class="col-md-8 mx-auto my-3">
            <x-search :query="$query"></x-search>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <span>List of tournaments</span>
                        @can('create', App\Tournament::class)
                            <a href="/tournaments/create" class="ml-2">Create new tournament</a>
                        @endcan
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @forelse($tournaments as $tournament)
                            <x-tournament-card :tournament="$tournament" class="mb-3"></x-tournament-card>
                        @empty
                            <p>No tournaments yet.</p>
                        @endforelse
                        {{ $tournaments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app>
