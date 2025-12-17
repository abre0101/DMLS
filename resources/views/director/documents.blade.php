@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-center">Documents Management</h1>

    @if($documents->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Uploaded By</th>
                        <th>Date Uploaded</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $document)
                        <tr>
                           <td>{{ $document->title }}</td>
<td>{{ $document->category->name ?? 'Uncategorized' }}</td>
<td>{{ $document->uploader->name ?? 'N/A' }}</td>

<td>{{ $document->created_at->format('Y-m-d') }}</td>

                            <td>
                                <span class="badge 
                                    @if($document->status === 'approved') bg-success
                                    @elseif($document->status === 'pending') bg-warning text-dark
                                    @else bg-danger @endif">
                                    {{ ucfirst($document->status) }}
                                </span>
                            </td>
                            <td class="d-flex gap-1">
                                <a href="{{ route('documents.show', $document->id) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>

                                @if($document->status === 'pending')
                                    <form action="{{ route('documents.approve', $document->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>

                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $documents->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">
            No documents available.
        </div>
    @endif
</div>
@endsection
