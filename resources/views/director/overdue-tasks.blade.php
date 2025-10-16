@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Overdue Tasks</h1>

    @if(empty($tasks))
        <p>No overdue tasks found.</p>
    @else
        <ul class="list-group">
            @foreach ($tasks as $task)
                <li class="list-group-item">
                    <strong>{{ $task->title ?? 'Untitled Task' }}</strong><br>
                    Due: {{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d H:i') }}<br>
                    Status: {{ ucfirst($task->status) }}
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
