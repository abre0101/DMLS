@extends('layouts.app')

@section('content')
<div class="container">
    <h2>📋 My Tasks</h2>

    @foreach ($tasks as $task)
        <div class="card mb-2 shadow-sm">
            <div class="card-body">
                <h5>{{ $task->title }}</h5>
                <p>{{ $task->description }}</p>
                <small>{{ $task->due_date ? $task->due_date->format('Y-m-d') : 'N/A' }}
</small>
            </div>
        </div>
    @endforeach

    {{ $tasks->links() }}
</div>
@endsection
