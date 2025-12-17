@if (empty($notificationsGroupedByDocument) || count($notificationsGroupedByDocument) === 0)
    <div class="empty-state-compact">
        <div class="empty-icon-compact">🔔</div>
        <p class="empty-text-compact">No notifications</p>
    </div>
@else
    <div class="notifications-container">
        @foreach ($notificationsGroupedByDocument as $documentTitle => $notifications)
            <div class="notification-group">
                <div class="group-header">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
                        <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                    </svg>
                    {{ \Illuminate\Support\Str::limit($documentTitle, 35) }}
                </div>
                
                <div class="notifications-list">
                    @foreach ($notifications as $notification)
                        <div class="notification-item">
                            <div class="notification-dot"></div>
                            <div class="notification-content">
                                <div class="notification-message">
                                    {{ $notification->message ?? 'No message' }}
                                </div>
                                <div class="notification-time">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                    </svg>
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif

<style>
.notifications-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-group {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.group-header {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e9ecef;
}

.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.notification-item {
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
}

.notification-dot {
    width: 8px;
    height: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    margin-top: 0.375rem;
    flex-shrink: 0;
}

.notification-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.notification-message {
    color: #495057;
    font-size: 0.85rem;
    line-height: 1.5;
}

.notification-time {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #6c757d;
}
</style>
