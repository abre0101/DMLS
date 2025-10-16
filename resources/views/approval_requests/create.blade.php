@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-primary fw-bold">Start Approval Request</h1>

    {{-- Success or Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>There were some issues:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('approval_requests.store') }}" method="POST">
        @csrf

        {{-- Select Document --}}
        <div class="mb-3">
            <label for="document_id" class="form-label">Select Document</label>
            <select name="document_id" id="document_id" class="form-select" required>
                <option value="" disabled selected>Choose a document...</option>
                @foreach($documents as $document)
                    <option value="{{ $document->id }}" {{ old('document_id') == $document->id ? 'selected' : '' }}>
                        {{ $document->title }} (Uploaded: {{ $document->created_at->format('M d, Y') }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Notes --}}
        <div class="mb-3">
            <label for="notes" class="form-label">Notes (Optional)</label>
            <textarea name="notes" id="notes" rows="4" class="form-control">{{ old('notes') }}</textarea>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-warning">Submit for Approval</button>
        </div>
    </form>
</div>
@endsection
