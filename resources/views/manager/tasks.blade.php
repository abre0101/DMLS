@extends('layouts.app')

@section('content')
    <h1>Assigned Tasks</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Task Name</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->name }}</td>
                    <td>{{ ucfirst($task->status) }}</td>
                    <td>{{ $task->due_date->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('manager.updateTask', $task->id) }}" class="btn btn-warning">Update</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
