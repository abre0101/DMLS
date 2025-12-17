@extends('layouts.app')

@section('content')

<h1>Pending Approval Requests</h1>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Requestor</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($pendingApprovals as $approval)
            <tr>
                <td>{{ $approval->id }}</td>
                <td>{{ $approval->requestor_name }}</td>
                <td>{{ $approval->status }}</td>
                <td>
                    <a href="{{ route('approval_requests.show', $approval->id) }}" class="btn btn-info">View</a>
                    <form action="{{ route('approval_requests.approve', $approval->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No pending approval requests found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection