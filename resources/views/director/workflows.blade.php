@extends('layouts.app')

@section('title', 'Workflow Management')

@section('content')
<div class="container">
    <h1 class="mb-4 text-primary">📋 Director Workflow Management</h1>

    <!-- Search & Filter Section -->
    <form method="GET" action="{{ route('director.workflows') }}" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" placeholder="Search workflows..." class="form-control" value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="manager_pending" {{ request('status') == 'manager_pending' ? 'selected' : '' }}>Awaiting Manager Review</option>
                <option value="director_pending" {{ request('status') == 'director_pending' ? 'selected' : '' }}>Awaiting Director Review</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">🔍 Search</button>
        </div>
    </form>

    <!-- Workflows Table -->
    @if($workflows->isEmpty())
        <div class="alert alert-warning">🚫 No workflows found for the selected criteria.</div>
    @else
        <table class="table table-bordered align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Workflow Name</th>
                    <th>Status</th>
                    <th>Submitted By</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($workflows as $workflow)
                <tr>
                    <td>{{ $workflow->id }}</td>
                    <td>{{ $workflow->name }}</td>
                    <td>
                        @if($workflow->status === 'manager_pending')
                            <span class="badge bg-secondary">Manager Review</span>
                        @elseif($workflow->status === 'director_pending')
                            <span class="badge bg-warning text-dark">Director Review</span>
                        @elseif($workflow->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($workflow->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td>{{ $workflow->submitted_by->name ?? 'N/A' }}</td>
                    <td>{{ $workflow->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @if($workflow->status === 'director_pending')
                            <form action="{{ route('workflows.director.approve', $workflow->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">✅ Approve</button>
                            </form>
                            <form action="{{ route('workflows.director.reject', $workflow->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">❌ Reject</button>
                            </form>
                        @else
                            <span class="text-muted">No actions available</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $workflows->links() }}
        </div>
    @endif
</div>
@endsection
