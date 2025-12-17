@if ($letters->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">📭</div>
        <p class="empty-text">No received letters found</p>
    </div>
@else
    <div class="card-list">
        @foreach ($letters as $letter)
            <div class="letter-card {{ is_null($letter->seen_at) ? 'unread' : '' }}">
                <div class="letter-header">
                    <div class="letter-from">
                        <span class="from-label">From:</span>
                        <span class="from-name">{{ $letter->sender->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="letter-date">{{ $letter->created_at->format('M d, Y') }}</div>
                </div>
                
                <div class="letter-content">
                    {{ \Illuminate\Support\Str::limit(strip_tags($letter->content), 100) }}
                </div>

                <div class="letter-footer">
                    @if ($letter->seen_at)
                        <span class="status-read">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                            Seen {{ \Carbon\Carbon::parse($letter->seen_at)->diffForHumans() }}
                        </span>
                    @else
                        <span class="status-unread">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                            </svg>
                            Unread
                        </span>
                    @endif
                    
                    <a href="{{ route('employee.letters.show', $letter) }}" class="btn-view">
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
.card-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.letter-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.letter-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    transform: translateX(4px);
}

.letter-card.unread {
    background: linear-gradient(to right, #fff9e6 0%, white 100%);
    border-left-color: #ffc107;
}

.letter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e9ecef;
}

.letter-from {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.from-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
}

.from-name {
    font-weight: 600;
    color: #2c3e50;
}

.letter-date {
    font-size: 0.875rem;
    color: #6c757d;
}

.letter-content {
    color: #495057;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.letter-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.status-read, .status-unread {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-read {
    color: #28a745;
}

.status-unread {
    color: #dc3545;
}

.btn-view {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: white;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-text {
    color: #6c757d;
    margin: 0;
    font-size: 1rem;
}
</style>
