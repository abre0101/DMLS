@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Letter Template</h2>

    <form method="POST" action="{{ route('letter-templates.update', $letterTemplate->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $letterTemplate->name) }}" required>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" class="form-control" rows="10" required>{{ old('content', $letterTemplate->content) }}</textarea>
            @error('content') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('letter-templates.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
