@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Letters</h1>

    {{-- Incoming Letters --}}
    <h3>Incoming Letters</h3>
    @if($incomingLetters->isEmpty())
        <p class="text-muted">No incoming letters found.</p>
    @else
        <ul class="list-group mb-4">
            @foreach ($incomingLetters as $letter)
                <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-bold">{{ $letter->subject ?? 'No Subject' }}</div>
                            <small class="text-muted">From: {{ $letter->sender->name ?? 'Unknown' }}</small><br>
                            <small>{{ \Illuminate\Support\Str::limit($letter->content, 100, '...') }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $letter->is_read ? 'success' : 'warning' }}">
                                {{ $letter->is_read ? 'Read' : 'Unseen' }}
                            </span>
                            <form action="{{ route('director.letters.read', $letter->id) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">View Message</button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

 
    <h3 class="mt-5">Outgoing Letters</h3>
    <div class="mb-3">
        <a href="{{ route('director.letters.create') }}" class="btn btn-primary">Create Outgoing Letter</a>
    </div>

    @if($outgoingLetters->isEmpty())
        <p class="text-muted">No outgoing letters found.</p>
    @else
        <ul class="list-group">
            @foreach ($outgoingLetters as $letter)
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                        <a href="{{ route('director.letters.show', $letter->id) }}" class="fw-bold">
                            To: {{ $letter->receiver->name ?? 'Unknown' }}
                        </a>
                        <div class="small text-muted">
                            {{ \Illuminate\Support\Str::limit($letter->content ?? 'No content', 100, '...') }}
                        </div>
                    </div>
                    <span class="badge bg-secondary text-capitalize">
                        {{ $letter->status }}
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
