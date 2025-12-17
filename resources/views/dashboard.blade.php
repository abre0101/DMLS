@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="container py-5">
    <h1 class="display-4 mb-4">📊 Employee Dashboard</h1>

    <!-- Quick Overview Cards -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow rounded">
                <div class="card-body">
                    <h5 class="card-title">🗂 My Documents</h5>
                    <p class="card-text fs-4 fw-bold text-primary">{{ $documentCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow rounded">
                <div class="card-body">
                    <h5 class="card-title">📄 Letters</h5>
                    <p class="card-text fs-4 fw-bold text-success">{{ $letterCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow rounded">
                <div class="card-body">
                    <h5 class="card-title">🔄 Pending Workflows</h5>
                    <p class="card-text fs-4 fw-bold text-warning">{{ $pendingWorkflowCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approval Requests -->
    <div class="mt-5">
        <h3 class="mb-3">🔔 My Pending Approvals</h3>
        @if(isset($pendingApprovals) && $pendingApprovals->isNotEmpty())
            <ul class="list-group">
                @foreach($pendingApprovals as $approval)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $approval->document->title ?? 'Untitled Document' }}</span>
                        <a href="{{ route('approval-requests.show', $approval->id) }}" class="btn btn-info btn-sm">
                            Review
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No pending approvals at the moment. Keep up the great work! 🎉</p>
        @endif
    </div>

    <!-- Assigned Tasks Section -->
    <div class="mt-5">
        <h3 class="mb-3">📝 Assigned Tasks</h3>
        @if(isset($tasks) && $tasks->isNotEmpty())
            <ul class="list-group">
                @foreach($tasks as $task)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $task->title }}</span>
                        <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-warning btn-sm">View Task</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No assigned tasks at the moment.</p>
        @endif
    </div>

    <!-- Quick Links -->
    <div class="mt-5">
        <h3 class="mb-3">📄 Document & Letter Management</h3>
        <a href="{{ route('documents.index') }}" class="btn btn-primary">Manage Documents</a>
        <a href="{{ route('letters.index') }}" class="btn btn-success">View Letters</a>
    </div>

    <div class="mt-5 text-center">
        <p class="text-muted">Keep going! You’re making a difference. 🚀</p>
    </div>
</div>
@endsection
