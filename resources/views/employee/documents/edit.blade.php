@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Document</h1>

    <form method="POST" action="{{ route('documents.update', $document->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $document->title) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $document->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $document->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="document_file" class="form-label">Replace Document File (optional)</label>
            <input type="file" name="document_file" id="document_file" class="form-control">
        </div>

        <div class="mb-3">
            <label for="watermark" class="form-label">Watermark</label>
            <input type="text" name="watermark" id="watermark" value="{{ old('watermark', $document->watermark) }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Document</button>
    </form>
</div>
@endsection
