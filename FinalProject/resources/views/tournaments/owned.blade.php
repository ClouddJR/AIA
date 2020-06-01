<x-app>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <span>Created tournaments</span>
                    </div>

                    <div class="card-body">
                        @forelse($tournaments as $tournament)
                            <x-tournament-card :tournament="$tournament" class="mb-3"></x-tournament-card>
                        @empty
                            <p>You don't have any tournaments.</p>
                        @endforelse
                        {{ $tournaments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app>
