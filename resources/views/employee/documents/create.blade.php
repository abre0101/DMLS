@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">📁 Upload New Document</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
            <label for="title">Title <span class="text-danger">*</span></label>
            <input 
                id="title"
                type="text" 
                name="title" 
                maxlength="255"
                class="form-control @error('title') is-invalid @enderror" 
                value="{{ old('title') }}" 
                required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea 
                id="description"
                name="description" 
                rows="4" 
                class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
            <label for="department">Department <span class="text-danger">*</span></label>
            <select 
                id="department"
                name="department" 
                class="form-control @error('department') is-invalid @enderror" 
                required>
                <option value="">-- Select Department --</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">
                Document will be routed to the selected department manager for approval.
            </small>
            @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
            <label for="category_id">Category <span class="text-danger">*</span></label>
            <select 
                id="category_id"
                name="category_id" 
                class="form-control @error('category_id') is-invalid @enderror" 
                required>
                <option value="">-- Select Category --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
            <label for="document_file">Upload File <span class="text-danger">*</span></label>
            <input 
                id="document_file"
                type="file" 
                name="document_file" 
                class="form-control @error('document_file') is-invalid @enderror" 
                accept=".pdf,.doc,.docx" 
                required>
            <small class="form-text text-muted">Allowed: PDF, DOC, DOCX, JPG, PNG, TIFF | Max size: 5MB</small>
            @error('document_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Tags multi-select --}}
        <div class="form-group mb-3">
            <label for="tags">Tags (optional)</label>
            <select 
                id="tags"
                name="tags[]" 
                multiple 
                class="form-control @error('tags') is-invalid @enderror">
                <option value="1" {{ collect(old('tags'))->contains(1) ? 'selected' : '' }}>Finance</option>
                <option value="2" {{ collect(old('tags'))->contains(2) ? 'selected' : '' }}>Urgent</option>
             
            </select>
            <small class="form-text text-muted">Hold Ctrl (Cmd on Mac) to select multiple tags.</small>
            @error('tags') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
            <label for="watermark">Watermark Text (optional)</label>
            <input 
                id="watermark"
                type="text" 
                name="watermark" 
                maxlength="255"
                class="form-control @error('watermark') is-invalid @enderror" 
                value="{{ old('watermark', 'Confidential') }}">
            @error('watermark') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">📤 Upload Document</button>
            <a href="{{ route('employee.dashboard') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
