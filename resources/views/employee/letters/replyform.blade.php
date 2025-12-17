@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">📨 Reply to Letter</h2>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <p><strong>Original Message from {{ $letter->sender->name }}:</strong></p>
            <blockquote class="blockquote">
                <p>{!! nl2br(e($letter->body)) !!}</p>
                <footer class="blockquote-footer">
                    Received at {{ $letter->created_at->format('d M Y, h:i A') }}
                </footer>
            </blockquote>
        </div>
    </div>

    <form action="{{ route('employee.letters.reply', $letter) }}" method="POST" class="needs-validation" novalidate>
        @csrf

        <div class="mb-3">
            <label for="body" class="form-label">Your Reply</label>
            <textarea name="body" id="body" rows="6" class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
            @error('body')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">📤 Send Reply</button>
    </form>
</div>
@endsection
