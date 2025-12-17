@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Task Details</h1>

    <div class="card shadow-sm">
        <div class="card-header">
            <strong>{{ $task->title }}</strong>
            <span class="badge bg-{{ $task->status === 'pending' ? 'warning' : 'success' }} float-end">
                {{ ucfirst($task->status) }}
            </span>
        </div>

        <div class="card-body">
            <p><strong>Description:</strong></p>
            <p>{{ $task->description ?? 'No description provided.' }}</p>

            <p>
                <strong>Due Date:</strong> 
                {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'No due date set' }}
            </p>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to Tasks</a>

            <form action="{{ route('manager.tasks.updateStatus', $task) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                    <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
        </div>
    </div>
</div>
@endsection
