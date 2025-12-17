@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Letter Details</h1>

    <div class="card mb-3">
        <div class="card-header">
            <strong>Subject:</strong> {{ $letter->subject }}
        </div>
        <div class="card-body">
            <p><strong>From:</strong> {{ $letter->sender->name ?? 'Unknown'}}</p>
            <p><strong>To:</strong> {{ $letter->receiver->name?? 'Unknown' }}</p>
            <p><strong>Date:</strong> {{ $letter->created_at->format('M d, Y') }}</p>
            <p><strong>Status:</strong> <span class="text-capitalize">{{ $letter->status }}</span></p>
            <hr>
            <p>{!! nl2br(e($letter->content)) !!}</p>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('director.letters.print', $letter->id) }}" target="_blank" class="btn btn-secondary btn-sm">
                Print / Export PDF
            </a>

            @if($letter->status === 'pending')
                <div>
                    <form method="POST" action="{{ route('director.letters.approve', $letter->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this letter?');">
                            Approve
                        </button>
                    </form>

                  <form action="{{ route('director.document.reject', $document->id) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-danger">Reject</button>
</form>

                </div>
            @else
                <span class="text-muted">No actions available.</span>
            @endif
        </div>
    </div>

    <a href="{{ route('director.letters.index') }}" class="btn btn-link">Back to Letters</a>
</div>
@endsection
