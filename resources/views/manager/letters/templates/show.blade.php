@extends('layouts.app')

@section('title', 'View Template')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">{{ $template->title }}</h1>

    {{-- Template Content --}}
    <div class="mb-4">
        <h5 class="text-muted">Content</h5>
        <div class="border rounded p-3 bg-light" style="white-space: pre-wrap;">
            {{ $template->content }}
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="d-flex gap-2">
        <a href="{{ route('manager.letters.templates.edit', $template) }}" class="btn btn-warning">
            Edit Template
        </a>
        <a href="{{ route('manager.letters.templates.index') }}" class="btn btn-secondary">
            Back to Templates
        </a>
    </div>
</div>
@endsection
