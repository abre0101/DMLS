@extends('layouts.app')

@section('title', 'Approval Queue')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📄 Approval Queue</h2>

    <form method="GET" class="row mb-3">
        <div class="col-md-3">
            <select name="priority" class="form-select">
                <option value="">All Priorities</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="department" class="form-select">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}">{{ $dept }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="submitted_at" class="form-control" placeholder="Submission Date">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Document</th>
                <th>Department</th>
                <th>Priority</th>
                <th>Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($approvals as $approval)
            <tr>
                <td>{{ $approval->document->title }}</td>
                <td>{{ $approval->document->department }}</td>
                <td>{{ ucfirst($approval->priority) }}</td>
                <td>{{ $approval->created_at->diffForHumans() }}</td>
                <td>
                    <form action="{{ route('director.document.approve', $approval->document->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('director.document.reject', $approval->document->id) }}" method="POST" class="d-inline ms-2">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </td>
            </tr>
            @empty
                <tr><td colspan="5" class="text-muted">No documents awaiting approval</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
