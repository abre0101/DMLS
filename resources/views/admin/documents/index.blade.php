@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-center">Document Management</h1>

    {{-- Top Actions --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">
            <i class="bi bi-upload me-1"></i> Upload New Document
        </a>
        <form class="d-flex" method="GET" action="{{ route('admin.documents.index') }}">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by title, metadata..." value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </form>
    </div>

    {{-- Filters --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="department" class="form-select">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-info w-100">Filter</button>
        </div>
    </form>

    {{-- Document Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Version</th>
                    <th>Uploaded By</th>
                    <th>Status</th>
                    <th>Last Modified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $document)
                    <tr>
                        <td>{{ $document->title }}</td>
                        <td>{{ $document->category->name ?? 'N/A' }}</td>
                        <td>{{ $document->version }}</td>
                        <td>{{ optional($document->uploadedBy)->name ?? 'System' }}</td>
                        <td>
                            @if($document->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($document->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td>{{ $document->updated_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.documents.show', $document->id) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                                <form action="{{ route('admin.documents.delete', $document->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No documents found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $documents->withQueryString()->links() }}
    </div>
</div>
@endsection
