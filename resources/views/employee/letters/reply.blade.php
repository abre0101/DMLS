@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">📨 Reply to Letter</h2>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Original Letter --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">
            {{ $letter->subject ?? ($letter->letterTemplate->title ?? 'Letter') }}
        </h5>

        <p class="mb-1">
            <strong>From:</strong> {{ $letter->sender->name ?? 'Unknown Sender' }}
        </p>
        <p class="mb-3">
            <strong>Received:</strong> {{ $letter->created_at->format('d M Y, h:i A') }}
        </p>

        <hr>

        <div class="letter-body" style="white-space: pre-line;">
            {!! nl2br(e($letter->content)) !!}
        </div>
    </div>
</div>
<form action="{{ route('employee.letters.reply', $letter) }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="body" class="form-label">Your Reply</label>
        <textarea 
            name="body" 
            id="body" 
            rows="6" 
            class="form-control @error('body') is-invalid @enderror" 
            placeholder="Type your reply here..." 
            required>{{ old('body') }}</textarea>

        @error('body')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Action Buttons --}}
    <div class="d-flex gap-2">
        <button type="submit" name="action" value="send" class="btn btn-success">
            📤 Send Reply
        </button>

        <button type="submit" name="action" value="draft" class="btn btn-secondary">
            💾 Save as Draft
        </button>

        <button type="submit" name="action" value="archive" class="btn btn-warning">
            🗃️ Archive
        </button>
    </div>
</form>

</div>
@endsection
