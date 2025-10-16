<!-- resources/views/director/approvals.blade.php -->

@extends('layouts.app')

@section('title', 'Approval Dashboard')

@section('content')
    <h1>Reports Pending Approval</h1>

    @if($reports->count())
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ $report->title }}</td>
                        <td>{{ $report->status }}</td>
                        <td>
                            <!-- Actions to approve or reject -->
                            <a href="{{ route('director.approveReport', $report->id) }}" class="btn btn-success">Approve</a>
                            <a href="{{ route('director.rejectReport', $report->id) }}" class="btn btn-danger">Reject</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No reports pending approval.</p>
    @endif
@endsection
