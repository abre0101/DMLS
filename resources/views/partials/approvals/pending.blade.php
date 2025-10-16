@if ($pendingApprovals->isEmpty())
    <p class="text-muted">No pending approval requests.</p>
@else
    <ul class="list-group mb-4">
        @foreach ($pendingApprovals as $approval)
            <li class="list-group-item">
                <div class="mb-2">
                    <strong>Title:</strong> {{ $approval->document->title ?? 'Untitled Request' }}
                </div>

                <div class="mb-2">
                    <strong>Requester:</strong> {{ Auth::user()->name }} {{-- current logged-in user --}}
                    <br>
                    <strong>Approver:</strong> {{ $approval->approver->name ?? 'N/A' }}
                </div>

                <div class="mb-2">
                    <strong>Status:</strong> {{ ucfirst($approval->status) }}
                </div>

                <div class="mb-2">
                    <strong>Notes:</strong><br>
                    <pre class="small bg-light p-2 rounded">{{ $approval->notes ?? 'None' }}</pre>
                </div>

                <div class="text-muted small">
                    Created at: {{ $approval->created_at->format('M d, Y H:i') }} |
                    Updated at: {{ $approval->updated_at->format('M d, Y H:i') }}
                </div>
            </li>
        @endforeach
    </ul>
@endif
