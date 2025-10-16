@extends('layouts.app')

@section('title', 'Request Document Approval')

@section('content')
<div class="container">
    <h3>Request Approval for Document: {{ $document->title }}</h3>

    <form action="{{ route('manager.approvals.store') }}" method="POST" id="approvalRequestForm">
        @csrf

        <input type="hidden" name="document_id" value="{{ $document->id }}">

        <div class="mb-3">
            <label for="notes" class="form-label">Notes (optional)</label>
            <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Add any notes for the approver...">{{ old('notes') }}</textarea>
        </div>

        {{-- Signature Pad or Input can go here if needed --}}

        <button type="submit" class="btn btn-primary">Submit Approval Request</button>
        <a href="{{ route('manager.documents.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection
