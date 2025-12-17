@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Letter Template</h1>

        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form to edit the letter template -->
        <form action="{{ route('letter-templates.update', $letterTemplate->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Use PUT method for updating -->

            <div class="form-group">
                <label for="name">Template Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $letterTemplate->name) }}" required>
            </div>

            <div class="form-group">
                <label for="content">Template Content</label>
                <textarea class="form-control" id="content" name="content" rows="10" required>{{ old('content', $letterTemplate->content) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Template</button>
            <a href="{{ route('letter-templates.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection