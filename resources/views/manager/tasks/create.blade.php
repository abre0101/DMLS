@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Create New Task</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('manager.tasks.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" 
                class="form-control @error('title') is-invalid @enderror" 
                value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="4" 
                class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" id="due_date" 
                class="form-control @error('due_date') is-invalid @enderror" 
                value="{{ old('due_date') }}">
            @error('due_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="assigned_to" class="form-label">Assign to Employee <span class="text-danger">*</span></label>
            <select name="assigned_to" id="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror" required>
                <option value="">-- Select Employee --</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('assigned_to') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }} ({{ $employee->email }})
                    </option>
                @endforeach
            </select>
            @error('assigned_to')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Task</button>
   <a href="{{ route('manager.tasks.index') }}" class="btn btn-secondary ms-2">Cancel</a>

    </form>
</div>
@endsection
