@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Pending Approval Requests</h2>

        @if ($pendingApprovals->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingApprovals as $approval)
                        <tr>
                            <td>{{ $approval->id }}</td>
                            <td>{{ $approval->user->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($approval->type) }}</td>
                            <td>{{ $approval->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.approvals.show', $approval->id) }}" class="btn btn-sm btn-info">View</a>
                                <form action="{{ route('admin.approvals.approve', $approval->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                </form>
                                <form action="{{ route('admin.approvals.reject', $approval->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $pendingApprovals->links() }}
        @else
            <p>No pending approvals found.</p>
        @endif
    </div>
@endsection
