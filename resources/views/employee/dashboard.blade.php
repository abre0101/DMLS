@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="welcome-section">
                <h1 class="dashboard-title">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                <p class="dashboard-subtitle">Here's what's happening with your documents today</p>
            </div>
            <a href="{{ route('employee.profile.edit') }}" class="btn-profile">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                </svg>
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="{{ route('employee.documents.create') }}" class="action-card action-primary">
            <div class="action-icon">📤</div>
            <div class="action-content">
                <h3>Upload Document</h3>
                <p>Add new documents</p>
            </div>
        </a>
        <a href="{{ route('approval_requests.create') }}" class="action-card action-warning">
            <div class="action-icon">✅</div>
            <div class="action-content">
                <h3>Approval Request</h3>
                <p>Start new request</p>
            </div>
        </a>
        <a href="{{ route('collaboration.index') }}" class="action-card action-success">
            <div class="action-icon">🤝</div>
            <div class="action-content">
                <h3>Collaborate</h3>
                <p>Work with team</p>
            </div>
        </a>
        <a href="{{ route('employee.tasks.assigned_to_me') }}" class="action-card action-info">
            <div class="action-icon">📋</div>
            <div class="action-content">
                <h3>My Tasks</h3>
                <p>View assignments</p>
            </div>
        </a>
    </div>

    <!-- Status Badges -->
    <div class="status-section">
        @foreach ($integrationStatuses as $integration)
            @php
                $badgeColors = [
                    'connected' => 'status-success',
                    'active' => 'status-info',
                    'pending' => 'status-warning',
                    'disconnected' => 'status-danger',
                ];
                $colorClass = $badgeColors[$integration->status] ?? 'status-secondary';
            @endphp
            <div class="status-badge {{ $colorClass }}">
                <span class="status-icon">{!! $integration->icon !!}</span>
                <span class="status-text">{{ $integration->name }}</span>
            </div>
        @endforeach
        <div class="status-badge status-dark">
            <span class="status-icon">🔐</span>
            <span class="status-text">{{ ucfirst(Auth::user()->role->name) }}</span>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Left Column -->
        <div class="content-main">
            <x-section-title icon="📥" title="Received Correspondence" />
            @include('partials.letters.inbox', ['letters' => $receivedLetters])

            <x-section-title icon="📂" title="My Documents" />
            
            <div class="filter-card">
                <form method="GET" action="{{ route('employee.dashboard') }}">
                    <div class="filter-grid">
                        <input type="text" name="title" placeholder="Search by title..." 
                               value="{{ request('title') }}" class="filter-input" />
                        <input type="text" name="submitter" placeholder="Submitter name..." 
                               value="{{ request('submitter') }}" class="filter-input" />
                        <input type="date" name="upload_date" 
                               value="{{ request('upload_date') }}" class="filter-input" />
                        <input type="text" name="tag" placeholder="Tag or keyword..." 
                               value="{{ request('tag') }}" class="filter-input" />
                        <button class="btn-filter" type="submit">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            @include('partials.documents.list', ['documents' => $documents])

            <x-section-title icon="📄" title="My Letters (Drafts & Sent)" />
            @include('partials.letters.sent', ['letters' => $sentLetters])
        </div>

        <!-- Right Sidebar -->
        <div class="content-sidebar">
            <x-section-title icon="🔔" title="Pending Approvals" />
            @include('partials.approvals.pending', ['pendingApprovals' => $pendingApprovals])

            <x-section-title icon="💬" title="Notifications" />
            @include('partials.notifications.grouped', ['notificationsGroupedByDocument' => $notificationsGroupedByDocument])

            <x-section-title icon="📅" title="Tasks & Reminders" />
            @include('partials.tasks.list', ['tasks' => $tasks])
        </div>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
    background: #f8f9fa;
    min-height: 100vh;
}

/* Header */
.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
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
    font-size: 1rem;
}

.btn-profile {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-profile:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    color: white;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.action-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.action-icon {
    font-size: 2rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    flex-shrink: 0;
}

.action-primary .action-icon { background: #e3f2fd; }
.action-warning .action-icon { background: #fff3e0; }
.action-success .action-icon { background: #e8f5e9; }
.action-info .action-icon { background: #e1f5fe; }

.action-primary:hover { border-color: #2196f3; }
.action-warning:hover { border-color: #ff9800; }
.action-success:hover { border-color: #4caf50; }
.action-info:hover { border-color: #00bcd4; }

.action-content h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

.action-content p {
    margin: 0.25rem 0 0 0;
    font-size: 0.875rem;
    color: #7f8c8d;
}

/* Status Section */
.status-section {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 2rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.status-success { background: #d4edda; color: #155724; }
.status-info { background: #d1ecf1; color: #0c5460; }
.status-warning { background: #fff3cd; color: #856404; }
.status-danger { background: #f8d7da; color: #721c24; }
.status-dark { background: #343a40; color: white; }
.status-secondary { background: #e2e3e5; color: #383d41; }

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
}

.content-main, .content-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Filter Card */
.filter-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 1rem;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 0.75rem;
}

.filter-input {
    padding: 0.75rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.filter-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-filter {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

/* Responsive */
@media (max-width: 1200px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .content-sidebar {
        order: 2;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .dashboard-title {
        font-size: 1.5rem;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
