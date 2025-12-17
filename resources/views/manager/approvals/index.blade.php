@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Manager Approval Requests</h1>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($documents->count())
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Document Title</th>
                    <th>Requestor</th>
                    <th>Status</th>
                    <th>Uploaded At</th>
                    <th>Tags</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                    <tr>
                        <td>{{ $document->id }}</td>
                        <td>{{ $document->title }}</td>
                        <td>{{ $document->user->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $document->status === 'approved' ? 'success' : 
                                ($document->status === 'rejected' ? 'danger' : 'warning') 
                            }}">
                                {{ ucfirst($document->status) }}
                            </span>
                        </td>
                        <td>{{ optional($document->created_at)->format('Y-m-d') ?? 'N/A' }}</td>
                        <td>
                            @forelse($document->tags as $tag)
                                <span class="badge bg-secondary">{{ $tag->name }}</span>
                            @empty
                                <span class="text-muted">No tags</span>
                            @endforelse
                        </td>
                        <td>
                            <a href="{{ route('manager.documents.show', $document->id) }}" class="btn btn-sm btn-{{ in_array($document->status, ['approved', 'rejected']) ? 'info' : 'primary' }}">
                                View
                            </a>

                            @if(in_array($document->status, ['approved', 'rejected']))
                                <a href="{{ route('manager.documents.download', $document->id) }}" class="btn btn-sm btn-outline-secondary">
                                    Download
                                </a>
                            @elseif($document->manager_approval === 1)
                                <a href="{{ route('manager.approvals.create', ['document_id' => $document->id]) }}" class="btn btn-sm btn-success">
                                    Request Approval
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        {{ $documents->withQueryString()->links() }}

    @else
        <p>No documents found.</p>
    @endif
</div>
@endsection
