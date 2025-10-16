@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-primary fw-bold">Your Letters</h1>

    {{-- Success & Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($letters->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-envelope-x display-1 text-muted"></i>
            <p class="lead mt-3">No letters found. Start by creating a new letter!</p>
            <a href="{{ route('employee.letters.create') }}" class="btn btn-lg btn-success">
                <i class="bi bi-plus-circle me-2"></i>Create New Letter
            </a>
        </div>
    @else
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle mb-0 bg-white">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Template</th>
                        <th scope="col">Receiver</th>
                        <th scope="col">Status</th>
                        <th scope="col">Sent At</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($letters as $letter)
                        <tr>
                            <td><strong>{{ $letter->letterTemplate->name ?? 'N/A' }}</strong></td>
                            <td>{{ $letter->receiver->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'sent' => 'success',
                                        'failed' => 'danger',
                                        'pending' => 'warning',
                                    ];
                                    $color = $statusColors[$letter->status] ?? 'primary';
                                @endphp
                                <span class="badge bg-{{ $color }} text-uppercase">{{ $letter->status }}</span>
                            </td>
                            <td>{{ $letter->sent_at ? $letter->sent_at->format('M d, Y @ H:i') : '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('letters.show', $letter) }}" class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="tooltip" title="View Letter">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                @if($letter->status === 'draft')
                                    <a href="{{ route('letters.edit', $letter) }}" class="btn btn-outline-secondary btn-sm me-1" data-bs-toggle="tooltip" title="Edit Letter">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('letters.destroy', $letter) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this draft letter?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Delete Letter">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div>
                Showing <strong>{{ $letters->firstItem() }}</strong> to <strong>{{ $letters->lastItem() }}</strong> of <strong>{{ $letters->total() }}</strong> letters
            </div>
            <div>
                {{ $letters->links() }}
            </div>
        </div>
    @endif

    {{-- Create New Letter Button --}}
    <div class="mt-5 text-center">
        <a href="{{ route('employee.letters.create') }}" class="btn btn-lg btn-success shadow-lg">
            <i class="bi bi-plus-circle me-2"></i> Create New Letter
        </a>
    </div>
</div>

{{-- Enable Bootstrap tooltips --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
