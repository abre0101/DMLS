@extends('layouts.app')

@section('content')
    <h1>Letter Templates</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Department</th>
                <th>Content</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($templates as $template)
                <tr>
                    <td>{{ $template->department }}</td>
                    <td>{{ Str::limit($template->content, 50) }}</td>
                    <td>
                        <a href="{{ route('templates.edit', $template->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('templates.destroy', $template->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('templates.create') }}" class="btn btn-success">Create New Template</a>
@endsection