@extends('layouts.app')

@section('title', 'Create Letter Template')

@section('content')
<div class="container">
    <h1>Create Letter Template</h1>

    <form action="{{ route('manager.letters.templates.store') }}" method="POST">
        @csrf

        <div class="mb-3">
           <label for="name" class="form-label">Template Name</label>
<input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
       value="{{ old('name') }}" required>
@error('name')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Template Content</label>
            <textarea name="content" id="content" rows="8" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Template</button>
        <a href="{{ route('manager.letters.templates.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
