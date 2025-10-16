@extends('layouts.app')

@section('title', 'Letter Details')

@section('content')
<div class="container my-4">
    <h1 class="mb-4">Letter Details</h1>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong>Subject:</strong> {{ $letter->subject }}
        </div>
        <div class="card-body">
            <p><strong>From:</strong> {{ $letter->sender->name }} <span class="text-muted">({{ $letter->sender->role->name ?? 'No role' }})</span></p>
            <p><strong>To:</strong> {{ $letter->receiver->name }} <span class="text-muted">({{ $letter->receiver->role->name ?? 'No role' }})</span></p>
            <p><strong>Date:</strong> {{ $letter->created_at->format('Y-m-d H:i') }}</p>

            <hr>

            <p style="white-space: pre-wrap;">{!! e($letter->content) !!}</p>
        </div>
    </div>

    <a href="{{ route('manager.letters.inbox.index') }}" class="btn btn-secondary mt-3">Back to Inbox</a>
</div>
@endsection
