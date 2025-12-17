@extends('layouts.app')

@section('content')
<div class="admin-dashboard">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div>
                <h1 class="dashboard-title">Admin Dashboard</h1>
                <p class="dashboard-subtitle">System Overview & Management</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-create-user">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                Create User
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        @php
            $summaryCards = [
                ['title' => 'Total Documents', 'value' => $totalDocuments, 'icon' => '📄', 'color' => '#667eea'],
                ['title' => 'Pending Approvals', 'value' => $pendingApprovals, 'icon' => '⏳', 'color' => '#ffc107'],
                ['title' => 'Active Users', 'value' => $activeUsers, 'icon' => '👥', 'color' => '#28a745'],
                ['title' => 'Letters Sent', 'value' => $lettersSent, 'icon' => '✉️', 'color' => '#17a2b8'],
                ['title' => 'Departments', 'value' => $departmentsCount, 'icon' => '🏢', 'color' => '#6f42c1'],
                ['title' => 'Storage (GB)', 'value' => $storageUsed, 'icon' => '💾', 'color' => '#fd7e14'],
            ];
        @endphp
        @foreach($summaryCards as $card)
            <div class="summary-card" style="border-left-color: {{ $card['color'] }};">
                <div class="summary-icon" style="background-color: {{ $card['color'] }}20;">
                    {{ $card['icon'] }}
                </div>
                <div class="summary-content">
                    <div class="summary-value">{{ $card['value'] }}</div>
                    <div class="summary-label">{{ $card['title'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Charts and Tables -->
    <div class="content-layout">
        <!-- Chart Section -->
        <div class="chart-section">
            <div class="section-card">
                <div class="section-header">
                    <h3>📊 Documents by Status</h3>
                </div>
                <div class="chart-container">
                    <canvas id="documentsStatusChart"></canvas>
                </div>
            </div>

            <!-- Recent Documents -->
            <div class="section-card">
                <div class="section-header">
                    <h3>📋 Recent Documents</h3>
                </div>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Uploader</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentDocuments as $doc)
                                <tr>
                                    <td class="doc-title">{{ $doc->title }}</td>
                                    <td>{{ $doc->uploadedBy->name ?? 'N/A' }}</td>
                                    <td>
                                        @if (is_null($doc->director_approval))
                                            <span class="status-badge status-pending">Pending</span>
                                        @elseif ($doc->director_approval)
                                            <span class="status-badge status-approved">Approved</span>
                                        @else
                                            <span class="status-badge status-rejected">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="doc-date">{{ $doc->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-row">No recent documents found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Users Section -->
        <div class="users-section">
            <div class="section-card">
                <div class="section-header">
                    <h3>👥 User Management</h3>
                </div>
                
                @if($users->count())
                    <div class="users-list">
                        @foreach ($users as $user)
                            <div class="user-card">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="user-info">
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-email">{{ $user->email }}</div>
                                    <div class="user-meta">
                                        <span class="user-role">{{ $user->role->name ?? 'N/A' }}</span>
                                        <span class="user-dept">{{ $user->department->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="user-actions">
                                    @if($user->status === 'active')
                                        <span class="status-badge status-active">Active</span>
                                    @else
                                        <span class="status-badge status-blocked">Blocked</span>
                                    @endif
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-edit">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="pagination-wrapper">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">👥</div>
                        <p>No users found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.admin-dashboard {
    max-width: 1600px;
    margin: 0 auto;
    padding: 2rem;
    background: #f8f9fa;
    min-height: 100vh;
}

.dashboard-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(30, 58, 138, 0.3);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.dashboard-title {
    color: white;
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
}

.dashboard-subtitle {
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
}

.btn-create-user {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-create-user:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    color: white;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.summary-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border-left: 4px solid;
    transition: all 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.summary-icon {
    font-size: 2rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
}

.summary-content {
    flex: 1;
}

.summary-value {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1;
}

.summary-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.content-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.section-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.section-header {
    margin-bottom: 1.5rem;
}

.section-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.chart-container {
    position: relative;
    height: 300px;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead {
    background: #f8f9fa;
}

.modern-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.modern-table td {
    padding: 1rem;
    border-top: 1px solid #e9ecef;
    color: #495057;
}

.modern-table tbody tr:hover {
    background: #f8f9fa;
}

.doc-title {
    font-weight: 600;
    color: #2c3e50;
}

.doc-date {
    color: #6c757d;
    font-size: 0.875rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-approved { background: #d4edda; color: #155724; }
.status-rejected { background: #f8d7da; color: #721c24; }
.status-active { background: #d4edda; color: #155724; }
.status-blocked { background: #e2e3e5; color: #383d41; }

.users-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.user-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.user-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #667eea;
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
}

.user-info {
    flex: 1;
}

.user-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.user-email {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.user-meta {
    display: flex;
    gap: 0.75rem;
    font-size: 0.75rem;
}

.user-role, .user-dept {
    padding: 0.25rem 0.5rem;
    background: #f8f9fa;
    border-radius: 6px;
    color: #495057;
}

.user-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-edit, .btn-delete {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-edit {
    background: #e3f2fd;
    color: #1976d2;
}

.btn-edit:hover {
    background: #1976d2;
    color: white;
}

.btn-delete {
    background: #ffebee;
    color: #c62828;
}

.btn-delete:hover {
    background: #c62828;
    color: white;
}

.empty-row {
    text-align: center;
    color: #6c757d;
    padding: 2rem !important;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    font-size: 4rem;
    opacity: 0.5;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #6c757d;
    margin: 0;
}

@media (max-width: 1200px) {
    .content-layout {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .admin-dashboard {
        padding: 1rem;
    }
    
    .summary-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('documentsStatusChart').getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($documentsByStatus)) !!},
            datasets: [{
                data: {!! json_encode(array_values($documentsByStatus)) !!},
                backgroundColor: ['#ffc107', '#28a745', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: { size: 14 }
                    }
                }
            }
        }
    });
});
</script>
@endsection
