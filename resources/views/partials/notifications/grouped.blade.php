@if (empty($notificationsGroupedByDocument) || count($notificationsGroupedByDocument) === 0)
    <p class="text-muted">No notifications found.</p>
@else
    @foreach ($notificationsGroupedByDocument as $documentTitle => $notifications)
        <div class="mb-3">
            <h5>{{ $documentTitle }}</h5>
            <ul class="list-group">
                @foreach ($notifications as $notification)
                    <li class="list-group-item">
                        {{ $notification->message ?? 'No message' }}
                        <br>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
@endif
