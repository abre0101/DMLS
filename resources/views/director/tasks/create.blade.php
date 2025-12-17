@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Task</h1>

    <form action="{{ route('director.tasks.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Title *</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control" required>
            @error('title') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
            @error('description') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="assigned_to" class="form-label">Assign to Manager *</label>
            <select id="assigned_to" name="assigned_to" class="form-select" required>
                <option value="">Select Manager</option>
                @foreach ($managers as $manager)
                    <option value="{{ $manager->id }}" {{ old('assigned_to') == $manager->id ? 'selected' : '' }}>
                        {{ $manager->name }}
                    </option>
                @endforeach
            </select>
            @error('assigned_to') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date *</label>
            <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" class="form-control" required>
            @error('due_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Task</button>
    </form>
</div>
@endsection
