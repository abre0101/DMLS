@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-primary fw-bold">Create New Letter</h1>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employee.letters.store') }}" method="POST">
        @csrf

        {{-- Letter Template --}}
        <div class="mb-3">
            <label for="letter_template_id" class="form-label">Letter Template</label>
            <select name="letter_template_id" id="letter_template_id" class="form-select" required>
                <option value="" disabled selected>Select a template</option>
                @foreach($templates as $template)
                    <option value="{{ $template->id }}" {{ old('letter_template_id') == $template->id ? 'selected' : '' }}>
                        {{ $template->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Receiver --}}
        <div class="mb-3">
            <label for="receiver_id" class="form-label">Receiver</label>
            <select name="receiver_id" id="receiver_id" class="form-select" required>
                <option value="" disabled selected>Select a receiver</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Content --}}
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea name="content" id="content" rows="8" class="form-control" required>{{ old('content') }}</textarea>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label">Save as:</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="status_draft" value="draft" {{ old('status', 'draft') == 'draft' ? 'checked' : '' }}>
                    <label class="form-check-label" for="status_draft">Draft</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="status_sent" value="sent" {{ old('status') == 'sent' ? 'checked' : '' }}>
                    <label class="form-check-label" for="status_sent">Send Now</label>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="mt-4">
            <a href="{{ route('employee.letters.index') }}" class="btn btn-secondary me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Letter</button>
        </div>
    </form>
</div>
@endsection
