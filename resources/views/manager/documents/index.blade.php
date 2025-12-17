@extends('layouts.app')

@section('title', 'Document Review')

@section('content')
<div class="container">
    <h1 class="mb-4">Pending Documents for Approval</h1>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @elseif(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    {{-- Filter/Search Form --}}
    <form method="GET" action="{{ route('manager.documents.index') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="title" placeholder="Title" value="{{ request('title') }}" class="form-control" />
            </div>
            <div class="col-md-3">
                <input type="text" name="submitter" placeholder="Submitter" value="{{ request('submitter') }}" class="form-control" />
            </div>
            <div class="col-md-2">
                <input type="date" name="upload_date" value="{{ request('upload_date') }}" class="form-control" />
            </div>
            <div class="col-md-2">
                <input type="text" name="tag" placeholder="Tag/Keyword" value="{{ request('tag') }}" class="form-control" />
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">Filter</button>
            </div>
        </div>
    </form>

    {{-- Documents Table --}}
    @if($documents->count())
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Submitter</th>
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
                    <td>{{ $document->category->name ?? 'N/A' }}</td>
                    <td>{{ $document->user->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-{{ 
                            $document->status == 'approved' ? 'success' : 
                            ($document->status == 'rejected' ? 'danger' : 'warning') 
                        }}">
                            {{ ucfirst($document->status) }}
                        </span>
                    </td>
                    <td>{{ $document->created_at->format('Y-m-d') }}</td>
                    <td>
                        @foreach($document->tags as $tag)
                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        @if(in_array($document->status, ['approved', 'rejected']))
                            {{-- After approval/rejection: View & Download only --}}
                            <a href="{{ route('manager.documents.show', $document->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('manager.documents.download', $document->id) }}" class="btn btn-sm btn-outline-secondary">Download</a>
                        @else
                            {{-- Pending status --}}
                            <a href="{{ route('manager.documents.show', $document->id) }}" class="btn btn-sm btn-primary">View</a>

                            @if($document->manager_approval === 1)
                                <a href="{{ route('manager.approvals.create', ['document_id' => $document->id]) }}" class="btn btn-sm btn-success">Request Approval</a>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $documents->withQueryString()->links() }}

    @else
        <p>No documents found.</p>
    @endif
</div>
@endsection
