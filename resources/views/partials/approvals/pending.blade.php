@if ($pendingApprovals->isEmpty())
    <div class="empty-state-compact">
        <div class="empty-icon-compact">✅</div>
        <p class="empty-text-compact">No pending approvals</p>
    </div>
@else
    <div class="approvals-list">
        @foreach ($pendingApprovals as $approval)
            @php
                $statusConfig = [
                    'pending' => ['color' => '#ffc107', 'icon' => '⏳', 'label' => 'Pending'],
                    'approved' => ['color' => '#28a745', 'icon' => '✅', 'label' => 'Approved'],
                    'rejected' => ['color' => '#dc3545', 'icon' => '❌', 'label' => 'Rejected'],
                ];
                $status = $statusConfig[$approval->status] ?? ['color' => '#6c757d', 'icon' => '❓', 'label' => 'Unknown'];
            @endphp
            
            <div class="approval-card">
                <div class="approval-header">
                    <div class="approval-title">
                        {{ \Illuminate\Support\Str::limit($approval->document->title ?? 'Untitled Request', 40) }}
                    </div>
                    <span class="approval-status" style="background-color: {{ $status['color'] }};">
                        {{ $status['icon'] }} {{ $status['label'] }}
                    </span>
                </div>

                <div class="approval-body">
                    <div class="approval-info">
                        <div class="info-item">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            </svg>
                            <span class="info-label">Approver:</span>
                            <span class="info-value">{{ $approval->approver->name ?? 'N/A' }}</span>
                        </div>
                        
                        @if($approval->notes)
                            <div class="approval-notes">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M2.5 1A1.5 1.5 0 0 0 1 2.5v11A1.5 1.5 0 0 0 2.5 15h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 15 8.586V2.5A1.5 1.5 0 0 0 13.5 1h-11zM2 2.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 .5.5V8H9.5A1.5 1.5 0 0 0 8 9.5V14H2.5a.5.5 0 0 1-.5-.5v-11z"/>
                                </svg>
                                <span>{{ \Illuminate\Support\Str::limit($approval->notes, 60) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="approval-time">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                        </svg>
                        {{ $approval->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<style>
.approvals-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.approval-card {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.approval-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

.approval-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.approval-title {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
    line-height: 1.4;
}

.approval-status {
    padding: 0.25rem 0.625rem;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 600;
    color: white;
    white-space: nowrap;
    flex-shrink: 0;
}

.approval-body {
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
}

.approval-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8rem;
    color: #6c757d;
}

.info-label {
    font-weight: 500;
}

.info-value {
    color: #2c3e50;
    font-weight: 600;
}

.approval-notes {
    display: flex;
    align-items: flex-start;
    gap: 0.375rem;
    font-size: 0.8rem;
    color: #495057;
    background: #f8f9fa;
    padding: 0.5rem;
    border-radius: 6px;
    line-height: 1.4;
}

.approval-time {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.75rem;
    color: #6c757d;
    padding-top: 0.5rem;
    border-top: 1px solid #e9ecef;
}

.empty-state-compact {
    text-align: center;
    padding: 2rem 1rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.empty-icon-compact {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.empty-text-compact {
    color: #6c757d;
    margin: 0;
    font-size: 0.875rem;
}
</style>
