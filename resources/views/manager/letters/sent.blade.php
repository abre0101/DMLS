@extends('layouts.app')

@section('title', 'Sent Letters')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Sent Letters</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($letters->count())
        <div class="table-responsive shadow-sm">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>To</th>
                        <th>Subject</th>
                        <th>Date Sent</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($letters as $letter)
                        <tr>
                            <td>{{ optional($letter->receiver)->name ?? 'N/A' }}</td>
                       <td>{{ optional($letter->template)->name ?? 'N/A' }}</td>

                            <td>{{ $letter->created_at->format('Y-m-d') }}</td>
                            <td class="text-center">
                                <a href="{{ route('manager.letters.show', $letter) }}" class="btn btn-sm btn-info" title="View Letter">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('manager.letters.export.pdf', $letter) }}" class="btn btn-sm btn-secondary" title="Download PDF">
                                    <i class="bi bi-download"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $letters->links() }}
        </div>
    @else
        <div class="alert alert-info">
            No sent letters found.
        </div>
    @endif
</div>
@endsection
