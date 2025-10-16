@extends('layouts.app')

@section('content')
@php use Illuminate\Support\Str; @endphp
<div class="container py-4">
    <h1 class="mb-4">📄 Document Details</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Document Details --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $document->title ?? 'Untitled Document' }}</h5>
            <span>Version: {{ $document->version ?? 1 }}</span>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Category:</dt>
                <dd class="col-sm-9">{{ $document->category->name ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Description:</dt>
                <dd class="col-sm-9">{{ $document->description ?? 'No description provided.' }}</dd>

                <dt class="col-sm-3">Watermark:</dt>
                <dd class="col-sm-9">{{ $document->watermark ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Uploaded By:</dt>
                <dd class="col-sm-9">{{ $document->uploadedBy->name ?? 'Unknown' }}</dd>

                <dt class="col-sm-3">Uploaded At:</dt>
                <dd class="col-sm-9">{{ $document->created_at->format('d M Y, H:i') }}</dd>

                <dt class="col-sm-3">Status:</dt>
                <dd class="col-sm-9">
                    <span class="badge 
                        @if($document->status === 'approved') bg-success 
                        @elseif($document->status === 'rejected') bg-danger 
                        @else bg-warning text-dark @endif">
                        {{ ucfirst($document->status) }}
                    </span>
                </dd>

                <dt class="col-sm-3">File:</dt>
                <dd class="col-sm-9">
                    @if($document->file_path)
                        <a href="{{ route('employee.documents.download', $document->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download"></i> Download
                        </a>
                    @else
                        <span class="text-muted">No file available.</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    {{-- Version History --}}
    @if($document->versions->isNotEmpty())
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">📚 Version History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Version</th>
                                <th>Uploaded At</th>
                                <th>Content Snippet</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($document->versions as $version)
                                <tr>
                                    <td>{{ $version->version ?? 'N/A' }}</td>
                                    <td>{{ $version->created_at->format('d M Y, H:i') }}</td>
                                    <td>{{ Str::limit($version->content, 50) }}</td>
                                    <td>
                                        <span class="text-muted">N/A</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('employee.documents.restore-version', $version->id) }}" method="POST" onsubmit="return confirm('Restore version {{ $version->version ?? 'N/A' }}?');">
                                            @csrf
                                            <button class="btn btn-sm btn-warning">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="d-flex gap-2">
        <a href="{{ route('employee.documents.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Documents
        </a>

        <form action="{{ route('employee.documents.destroy', $document->id) }}" method="POST" onsubmit="return confirm('Delete this document permanently?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">
                <i class="bi bi-trash"></i> Delete Document
            </button>
        </form>
    </div>
</div>
@endsection
