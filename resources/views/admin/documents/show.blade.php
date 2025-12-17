@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Document Details</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $document->title ?? 'Untitled Document' }}</h5>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Category:</dt>
                <dd class="col-sm-9">{{ $document->category->name ?? 'N/A' }}</dd>


                <dt class="col-sm-3">Description:</dt>
                <dd class="col-sm-9">{{ $document->description ?? 'No description provided.' }}</dd>

                <dt class="col-sm-3">Uploaded By:</dt>
                <dd class="col-sm-9">{{ optional($document->uploadedBy)->name ?? 'Unknown' }}</dd>

                <dt class="col-sm-3">Uploaded At:</dt>
                <dd class="col-sm-9">{{ $document->created_at->format('d M Y, H:i') }}</dd>

                <dt class="col-sm-3">File:</dt>
                <dd class="col-sm-9">
                    @if($document->file_path)
                        <a href="{{ asset('storage/' . $document->file_path) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download"></i> Download
                        </a>
                    @else
                        <span class="text-muted">No file available.</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Documents List
        </a>

        <form action="{{ route('admin.documents.delete', $document->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Delete Document
            </button>
        </form>
    </div>
</div>
@endsection
