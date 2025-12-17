@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<div class="container py-4">

    <h1 class="mb-1">Manager Dashboard</h1>
    <p class="text-muted mb-4">
        Department: <strong>{{ $department->name ?? 'Not Assigned' }}</strong>
    </p>

    <div class="row mb-4">
        @php
            $summaryCards = [
                ['title' => 'Pending Documents', 'count' => $pendingDocumentsCount ?? 0, 'bg' => 'warning', 'route' => route('manager.documents.index', ['status' => 'pending'])],
                ['title' => 'Total Documents', 'count' => $totalDocumentsCount ?? 0, 'bg' => 'info', 'route' => route('manager.documents.index')],
                ['title' => 'Letters Received', 'count' => $lettersToManagerCount ?? 0, 'bg' => 'primary', 'route' => route('manager.letters.inbox.index')],
                ['title' => 'Pending Approval Requests', 'count' => $PendingApprovalRequestscount ?? 0, 'bg' => 'danger', 'route' => route('manager.approvals.index')],
            ];
        @endphp

        @foreach ($summaryCards as $card)
            <div class="col-md-3">
                <a href="{{ $card['route'] }}" class="text-white text-decoration-none">
                    <div class="card text-white bg-{{ $card['bg'] }} mb-3 shadow-sm">
                        <div class="card-header">{{ $card['title'] }}</div>
                        <div class="card-body text-center">
                            <h3 class="card-title">{{ $card['count'] }}</h3>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

  
    <div class="mb-4">
        <h4>Quick Links</h4>
        <div class="btn-group flex-wrap" role="group" aria-label="Quick links">
            <a href="{{ route('manager.letters.inbox.index') }}" class="btn btn-outline-primary mb-2">Letters Inbox</a>
            <a href="{{ route('letter-templates.index') }}" class="btn btn-outline-secondary mb-2">Letter Templates</a>
            <a href="{{ route('manager.documents.index') }}" class="btn btn-outline-success mb-2">Documents</a>
            <a href="{{ route('manager.letters.send') }}" class="btn btn-outline-info mb-2">Send Letter</a>
           <a href="{{ route('manager.tasks.create') }}"class="btn btn-outline-info mb-2">Create Task</a>

    </div>
        </div>

        <div class="mt-3">
            <h5>📤 Export Report</h5>
            <form action="{{ route('manager.reports.export') }}" method="GET" class="form-inline d-flex flex-wrap align-items-center gap-2">
                <select name="format" class="form-select w-auto">
                    <option value="csv">CSV</option>
                    <option value="pdf">PDF</option>
                </select>
                <button type="submit" class="btn btn-outline-dark">Download</button>
            </form>
        </div>
    </div>

    <section class="mb-5">
        <h3>Tasks Assigned by Director</h3>

        @if($tasks && $tasks->count())
            <ul class="list-group">
                @foreach($tasks as $task)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>
                                <a href="{{ route('manager.tasks.show', $task) }}">
                                    {{ $task->title }}
                                </a>
                            </strong><br>
                    
                            <small class="text-muted">Due: {{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}</small>

                            <p>{{ $task->description }}</p>
                        </div>
                        <span class="badge bg-{{ $task->status == 'pending' ? 'warning' : 'success' }}">
                            {{ ucfirst($task->status) }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No tasks assigned by the director.</p>
        @endif
    </section>


    <section class="mb-5">
        <h3>Recent Activity</h3>
        <ul class="list-group">
            @forelse ($recentActivities as $activity)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $activity->description }}
                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                </li>
            @empty
                <li class="list-group-item">No recent activity.</li>
            @endforelse
        </ul>
    </section>

 
    <section class="mb-5">
        <h3>Document Status Trends</h3>
        <div class="d-flex gap-3 flex-wrap">
            <span class="badge bg-warning text-dark px-3 py-2">
                Pending: <strong>{{ $trends['pending'] ?? 0 }}</strong>
            </span>
            <span class="badge bg-success px-3 py-2">
                Approved: <strong>{{ $trends['approved'] ?? 0 }}</strong>
            </span>
            <span class="badge bg-danger px-3 py-2">
                Rejected: <strong>{{ $trends['rejected'] ?? 0 }}</strong>
            </span>
        </div>
    </section>

    <hr>


    <section>
        <div class="row">
            @foreach (['Pending' => $pendingDocuments ?? collect(), 'Approved' => $approvedDocuments ?? collect(), 'Rejected' => $rejectedDocuments ?? collect()] as $status => $documents)
                <div class="col-md-4 mb-4">
                    <h4>{{ $status }}</h4>

                    @if ($documents->count())
                        <ul class="list-group shadow-sm">
                            @foreach ($documents as $doc)
                                <li class="list-group-item">
                                    <a href="{{ route('manager.documents.show', $doc) }}">{{ $doc->title }}</a>
                                    <br>
                                    <small class="text-muted">
                                        Uploaded by {{ $doc->uploadedBy->name ?? 'Unknown' }} on {{ $doc->created_at->format('Y-m-d') }}
                                    </small>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-2">
                            {{ $documents->links() }}
                        </div>
                    @else
                        <p class="text-muted">No {{ strtolower($status) }} documents.</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

 
    <section class="mb-5">
        <h3>Pending Approval Requests</h3>
        @if($pendingApprovalRequests && $pendingApprovalRequests->count())
            <ul class="list-group">
                @foreach($pendingApprovalRequests as $request)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            📄 <strong>{{ $request->document->title ?? 'Untitled Document' }}</strong><br>

                       <small class="text-muted">
    Requested by {{ $request->user->name ?? 'Unknown' }} 
    {{ $request->created_at->diffForHumans() }}
</small>
 </div>
                 <a href="{{ route('manager.approvals.show', $request->id) }}" class="btn btn-sm btn-primary">
    Review
</a>

                    </li>
                @endforeach 
            </ul>
        @else
            <p class="text-muted">No pending approval requests.</p>
        @endif
    </section>


    <section>
        <h3>Notifications</h3>
        @if($notifications && $notifications->count())
            <ul class="list-group">
                @foreach($notifications as $note)
                    <li class="list-group-item">
                        <strong>{{ $note->title ?? 'Notification' }}</strong>
                        <div class="small text-muted">{{ $note->created_at->diffForHumans() }}</div>
                        <p>{{ $note->message ?? '' }}</p>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No notifications.</p>
        @endif
    </section>

</div>
@endsection
