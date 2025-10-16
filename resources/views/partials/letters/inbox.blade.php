@if ($letters->isEmpty())
    <p class="text-muted">No received letters found.</p>
@else
    <ul class="list-group mb-4">
        @foreach ($letters as $letter)
            <li class="list-group-item d-flex justify-content-between align-items-center
                {{ is_null($letter->seen_at) ? 'bg-light' : '' }}">
                
                <div>
                    <strong>
                        {{ \Illuminate\Support\Str::limit(strip_tags($letter->content), 80) }}
                    </strong><br>
                    <small>From: {{ $letter->sender->name ?? 'Unknown' }}</small><br>

                    {{-- ✅ Show Seen Info --}}
                  @if ($letter->seen_at)
    <small class="text-success">
        👁 Seen at {{ \Carbon\Carbon::parse($letter->seen_at)->format('d M Y, h:i A') }}
    </small>
@else
    <small class="text-danger fw-bold">📩 Unread</small>
@endif

                </div>

                <div class="d-flex flex-column align-items-end">
                    <span class="text-muted mb-2">{{ $letter->created_at->format('M d, Y') }}</span>
                    <a href="{{ route('employee.letters.show', $letter) }}" class="btn btn-sm btn-outline-primary">
                        View Letter
                    </a>
                </div>
            </li>
        @endforeach
    </ul>
@endif
