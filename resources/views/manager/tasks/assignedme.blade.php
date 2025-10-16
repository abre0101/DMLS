@extends('layouts.app')

@section('title', 'Tasks Assigned by Me')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Tasks Assigned by Me</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($tasks->count())
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->assignedTo->name ?? 'Unknown' }}</td>
                            <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'N/A' }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($task->status) }}</span></td>
                            <td>
                                <a href="{{ route('manager.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $tasks->links() }}
        </div>
    @else
        <p class="text-muted">You haven't assigned any tasks yet.</p>
    @endif
</div>
@endsection
