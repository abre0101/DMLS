@extends('layouts.app')

@section('title', 'Edit Letter Template')

@section('content')
<div class="container">
    <h1>Edit Letter Template</h1>

    <form action="{{ route('manager.letters.templates.update', $template) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Template Title</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                   value="{{ old('title', $template->title) }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Template Content</label>
            <textarea name="content" id="content" rows="8" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $template->content) }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Update Template</button>
        <a href="{{ route('manager.letters.templates.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
