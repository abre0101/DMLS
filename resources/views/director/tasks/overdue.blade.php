@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Overdue Tasks Assigned to Managers</h1>

    <a href="{{ route('director.tasks.create') }}" class="btn btn-success mb-3">Create New Task</a>




    @if($tasks->isEmpty())
        <p class="text-muted">No overdue tasks assigned to managers.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Task ID</th>
                    <th>Title</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->title ?? 'No Title' }}</td>
                        <td>{{ $task->assignedToUser->name ?? 'Unknown' }}</td>
                        <td>{{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}</td>
                        <td>{{ ucfirst($task->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
