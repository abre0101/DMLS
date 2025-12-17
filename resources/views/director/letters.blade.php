@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-center">Letters Management</h1>

    @if($letters->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th>Sender</th>
                        <th>Recipient</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($letters as $letter)
                        <tr>
                            <td>{{ $letter->subject }}</td>
                            <td>{{ $letter->sender }}</td>
                            <td>{{ $letter->recipient }}</td>
                            <td>{{ $letter->created_at->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge 
                                    @if($letter->status == 'approved') bg-success
                                    @elseif($letter->status == 'pending') bg-warning
                                    @else bg-danger @endif">
                                    {{ ucfirst($letter->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('letters.show', $letter->id) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                                @if($letter->status == 'pending')
                                    <a href="{{ route('letters.approve', $letter->id) }}" class="btn btn-sm btn-success">
                                        Approve
                                    </a>
                                    <a href="{{ route('letters.reject', $letter->id) }}" class="btn btn-sm btn-danger">
                                        Reject
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $letters->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">
            No letters found.
        </div>
    @endif
</div>
@endsection
