@extends('layouts.app')

@section('content')
    <h1>Create Letter Template</h1>
    <form action="{{ route('templates.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="department">Department:</label>
            <input type="text" name="department" id="department" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="content">Template Content:</label>
            <textarea name="content" id="content" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Template</button>
    </form>
@endsection