@extends('layouts.app')

@section('title', 'Workflow Management')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-center">Manage Workflows</h1>

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('admin.workflows') }}" class="row g-2 mb-4">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search workflows..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    {{-- Workflow Table --}}
    @if($workflows->isEmpty())
        <div class="alert alert-info text-center">No workflows found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Workflow Name</th>
                        <th>Status</th>
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
                            @switch($workflow->status)
                                @case('pending')
                                    <span class="badge bg-secondary">Pending</span>
                                    @break
                                @case('approved')
                                    <span class="badge bg-success">Approved</span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $workflow->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <form action="{{ route('workflows.approve', $workflow->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                </form>

                                <form action="{{ route('workflows.reject', $workflow->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this workflow?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $workflows->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
