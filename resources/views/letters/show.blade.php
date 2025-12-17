@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Letter Details</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <strong>Template:</strong> {{ $letter->template->name }}<br>
            <strong>Status:</strong> 
            @if($letter->status === 'draft')
                <span class="badge bg-secondary">Draft</span>
            @elseif($letter->status === 'sent')
                <span class="badge bg-success">Sent</span>
            @elseif($letter->status === 'archived')
                <span class="badge bg-warning text-dark">Archived</span>
            @endif
        </div>
        <div class="card-body">
            <p><strong>Created At:</strong> {{ $letter->created_at->format('Y-m-d H:i') }}</p>
            @if($letter->sent_at)
                <p><strong>Sent At:</strong> {{ $letter->sent_at->format('Y-m-d H:i') }}</p>
            @endif
            
            <hr>

            <h5>Final Letter Content:</h5>
            <div class="p-3 bg-light" style="white-space: pre-wrap; border-radius: 5px; border: 1px solid #ddd;">
                {!! nl2br(e($letter->final_content)) !!}
            </div>
        </div>
    </div>

    <a href="{{ route('letters.index') }}" class="btn btn-secondary">Back to Letters</a>
</div>
@endsection