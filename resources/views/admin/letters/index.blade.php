@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-center">Your Letters</h1>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Letters Table --}}
    @if($letters->isEmpty())
        <div class="alert alert-info">No letters found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Template</th>
                        <th>Receiver</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($letters as $letter)
                        <tr>
                            <td>{{ $letter->letterTemplate->name ?? 'N/A' }}</td>
                            <td>{{ $letter->receiver->name ?? 'N/A' }}</td>
                            <td>
                                @if($letter->status === 'sent')
                                    <span class="badge bg-success">Sent</span>
                                @elseif($letter->status === 'draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ ucfirst($letter->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $letter->sent_at ? $letter->sent_at->format('Y-m-d H:i') : '-' }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('letters.show', $letter) }}" class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>

                                    @if($letter->status === 'draft')
                                        <a href="{{ route('letters.edit', $letter) }}" class="btn btn-sm btn-outline-secondary">
                                            Edit
                                        </a>
                                        <form action="{{ route('letters.destroy', $letter) }}" method="POST" onsubmit="return confirm('Delete this draft letter?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('letters.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Create New Letter
        </a>
    </div>
</div>
@endsection
