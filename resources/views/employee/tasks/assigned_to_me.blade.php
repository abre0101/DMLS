@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>📋 My Assigned Tasks</h2>

    @if ($tasks->count())
        <ul class="list-group">
            @foreach ($tasks as $task)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $task->title }}</strong>
                        
                        <p class="mb-0">{{ $task->description }}</p>
                        <small>
                            Due: {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No date' }}
                        </small><br>
                        <small>
                            Assigned by: {{ $task->assignedBy->name ?? 'Unknown' }}
                        </small>
                    </div>
                    @if ($task->status !== 'completed')
                        <form action="{{ route('tasks.complete', $task->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">No tasks assigned.</p>
    @endif
</div>
@endsection
