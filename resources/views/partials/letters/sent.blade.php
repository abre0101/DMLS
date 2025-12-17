@if ($letters->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">📤</div>
        <p class="empty-text">No sent letters found</p>
    </div>
@else
    <div class="sent-letters-list">
        @foreach ($letters as $letter)
            <div class="sent-letter-card">
                <div class="sent-header">
                    <div class="sent-to">
                        <span class="to-label">To:</span>
                        <span class="to-name">{{ $letter->receiver->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="sent-date">{{ $letter->created_at->format('M d, Y') }}</div>
                </div>
                
                <div class="sent-content">
                    {{ \Illuminate\Support\Str::limit(strip_tags($letter->content), 100) }}
                </div>

                <div class="sent-footer">
                    <span class="sent-badge">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                        </svg>
                        Sent
                    </span>
                    
                    <a href="{{ route('employee.letters.show', $letter) }}" class="btn-view-sent">
                        View Letter
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                        </svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif

<style>
.sent-letters-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.sent-letter-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border-left: 4px solid #28a745;
}

.sent-letter-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    transform: translateX(4px);
}

.sent-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e9ecef;
}

.sent-to {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.to-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
}

.to-name {
    font-weight: 600;
    color: #2c3e50;
}

.sent-date {
    font-size: 0.875rem;
    color: #6c757d;
}

.sent-content {
    color: #495057;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.sent-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sent-badge {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #28a745;
}

.btn-view-sent {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.375rem;
    transition: all 0.3s ease;
}

.btn-view-sent:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    color: white;
}
</style>
