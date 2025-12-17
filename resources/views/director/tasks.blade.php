<!-- resources/views/director/tasks.blade.php -->

@extends('layouts.app')

@section('title', 'Director Tasks')

@section('content')
<div class="container">
    <h1 class="my-4">Director's Task List</h1>

    <!-- Check if there are tasks -->
    @if($tasks->isEmpty())
        <div class="alert alert-warning">
            <strong>No tasks available at the moment.</strong>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Task Name</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->name }}</td>
                            <td>
                                <!-- Display the status with proper badge -->
                                @if($task->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($task->status == 'completed')
                                    <span class="badge bg-success text-light">Completed</span>
                                @elseif($task->status == 'overdue')
                                    <span class="badge bg-danger text-light">Overdue</span>
                                @else
                                    <span class="badge bg-secondary text-light">In Progress</span>
                                @endif
                            </td>
                            <td>{{ $task->assigned_to ? $task->assigned_to->name : 'Unassigned' }}</td>
                            <td>{{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}</td>
                            <td>
                                <!-- Edit & View Buttons -->
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info btn-sm" title="View Task"><i class="fas fa-eye"></i> View</a>
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm" title="Edit Task"><i class="fas fa-pencil-alt"></i> Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $tasks->links() }}
        </div>
    @endif
</div>
@endsection
