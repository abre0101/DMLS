@if ($documents->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">📄</div>
        <p class="empty-text">No documents found</p>
    </div>
@else
    <div class="documents-grid">
        @foreach ($documents as $document)
            <div class="document-card">
                <div class="doc-header">
                    <div class="doc-title">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                        </svg>
                        {{ $document->title ?? 'Untitled' }}
                    </div>
                    @php
                        $statusConfig = [
                            'draft' => ['color' => '#6c757d', 'label' => 'Draft'],
                            'pending' => ['color' => '#ffc107', 'label' => 'Pending'],
                            'approved' => ['color' => '#28a745', 'label' => 'Approved'],
                            'rejected' => ['color' => '#dc3545', 'label' => 'Rejected'],
                            'archived' => ['color' => '#343a40', 'label' => 'Archived'],
                        ];
                        $status = $statusConfig[$document->status] ?? ['color' => '#6c757d', 'label' => 'Unknown'];
                    @endphp
                    <span class="doc-status" style="background-color: {{ $status['color'] }};">
                        {{ $status['label'] }}
                    </span>
                </div>

                <div class="doc-meta">
                    <div class="meta-item">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        </svg>
                        <span>{{ $document->getAuthorFirstName() }}</span>
                    </div>
                    <div class="meta-item">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                        </svg>
                        <span>{{ $document->created_at ? $document->created_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>

                <div class="doc-details">
                    <div class="detail-row">
                        <span class="detail-label">Department:</span>
                        <span class="detail-value">{{ $document->department->name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Category:</span>
                        <span class="detail-value">{{ $document->category->name ?? 'N/A' }}</span>
                    </div>
                </div>

                <a href="{{ route('employee.documents.show', $document) }}" class="btn-view-doc">
                    View Document
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                </a>
            </div>
        @endforeach
    </div>
@endif

<style>
.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.25rem;
}

.document-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.document-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    transform: translateY(-4px);
}

.doc-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
}

.doc-title {
    font-weight: 600;
    color: #2c3e50;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
}

.doc-status {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    white-space: nowrap;
}

.doc-meta {
    display: flex;
    gap: 1.5rem;
    padding: 0.75rem 0;
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.875rem;
    color: #6c757d;
}

.doc-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.875rem;
}

.detail-label {
    color: #6c757d;
    font-weight: 500;
}

.detail-value {
    color: #2c3e50;
    font-weight: 600;
}

.btn-view-doc {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    margin-top: auto;
}

.btn-view-doc:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: white;
}

@media (max-width: 768px) {
    .documents-grid {
        grid-template-columns: 1fr;
    }
}
</style>
