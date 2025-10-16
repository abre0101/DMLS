@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📄 Start New Collaboration</h2>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following issues:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Collaboration form --}}
    <form action="{{ route('collaboration.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Document Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Enter title..." required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Describe the document..." required></textarea>
        </div>

        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <select name="department" id="department" class="form-select" required>
                <option value="" disabled selected>Select department...</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="" disabled selected>Select category...</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Upload File</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="collaborators" class="form-label">Add Collaborators (Optional)</label>
            <select name="collaborators[]" id="collaborators" class="form-select" multiple aria-describedby="collaboratorsHelp">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <small id="collaboratorsHelp" class="form-text text-muted">
                Hold Ctrl (Windows) or Command (Mac) to select multiple collaborators.
            </small>
        </div>

        <button type="submit" class="btn btn-primary">🚀 Create Collaboration</button>
    </form>
</div>
@endsection
