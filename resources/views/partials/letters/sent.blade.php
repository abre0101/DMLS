@if ($letters->isEmpty())
    <p class="text-muted">No sent letters found.</p>
@else
    <ul class="list-group mb-4">
        @foreach ($letters as $letter)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $letter->content ?? 'No Subject' }}</strong><br>
                    <small>To: {{ $letter->receiver->name ?? 'Unknown' }}</small>
                </div>
                <span class="text-muted">{{ $letter->created_at->format('M d, Y') }}</span>
            </li>
        @endforeach
    </ul>
@endif
