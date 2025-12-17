@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Letter Templates</h2>
    <a href="{{ route('letter-templates.create') }}" class="btn btn-primary mb-3">Create New Template</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul class="list-group">
        @foreach ($templates as $template)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $template->name }}
                <div>
                    <a href="{{ route('letter-templates.show', $template->id) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('letter-templates.edit', $template->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('letter-templates.destroy', $template->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection
