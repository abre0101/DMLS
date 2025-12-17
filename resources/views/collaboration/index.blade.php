{{-- resources/views/collaboration/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🤝 Collaboration Area</h2>
        <a href="{{ route('collaboration.create') }}" class="btn btn-primary">📄 Start New Collaboration</a>
    </div>

    {{-- Description --}}
    <p class="text-muted mb-4">Collaborate on documents, share feedback, and track updates in real time with your team.</p>

    {{-- Active Collaborations --}}
    <div class="mb-5">
        <h4>📁 Active Documents</h4>
        <div class="list-group">
            @forelse($documents->filter(fn($doc) => $doc->collaborators->isNotEmpty()) as $document)
            <a href="{{ route('documents.show', $document->id) }}" class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $document->title }}</strong>
                        <div class="small text-muted">
                            Last updated: {{ $document->updated_at->format('M d, Y') }} · Collaborators:
                            {{ $document->collaborators->pluck('name')->join(', ') }}
                        </div>
                    </div>
                    <span class="badge bg-info">{{ ucfirst($document->status) }}</span>
                </div>
            </a>
        @empty
            <div class="text-muted">No active collaborations found.</div>
        @endforelse
        
        </div>
    </div>

    {{-- Collaborators --}}
    <div class="mb-5">
        <h4>👥 Team Members</h4>
        <ul class="list-group list-group-flush">
            @php
                $collaborators = $documents->flatMap->collaborators->unique('id');
            @endphp

            @forelse($collaborators as $collaborator)
                <li class="list-group-item">
                    {{ $collaborator->name }}
                    <span class="badge bg-secondary ms-2">
                        {{-- Example role display, adapt if you store roles --}}
                        Collaborator
                    </span>
                </li>
            @empty
                <li class="list-group-item text-muted">No collaborators found.</li>
            @endforelse
        </ul>
    </div>

 {{-- Recent Activity --}}
<div class="mb-5">
    <h4>🕒 Recent Activity</h4>
    <ul class="list-group list-group-flush">
        @forelse($activities as $activity)
            <li class="list-group-item">
                {{ $activity->user->name }} {{ $activity->action }} on <strong>{{ $activity->document->title }}</strong>
                <div class="small text-muted">{{ $activity->created_at->diffForHumans() }}</div>
            </li>
        @empty
            <li class="list-group-item text-muted">No recent activity found.</li>
        @endforelse
    </ul>
</div>


    <div class="alert alert-info">
        🚧 More collaboration tools (real-time editing, version history, comment threads) coming soon!
    </div>
</div>
@endsection
