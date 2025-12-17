@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Preview Letter</h1>

    <div class="card p-3 mb-3" style="white-space: pre-wrap; background: #f9f9f9; border: 1px solid #ccc;">
        {!! nl2br(e($finalContent)) !!}
    </div>

    <form action="{{ route('letters.store') }}" method="POST">
        @csrf
        <input type="hidden" name="template_id" value="{{ $template->id }}">
        @foreach($data as $key => $value)
            <input type="hidden" name="data[{{ $key }}]" value="{{ $value }}">
        @endforeach

        <button type="submit" name="save" class="btn btn-primary">Save Letter</button>
        <!-- Optionally add send button if you want -->
        <!-- <button type="submit" name="send" value="1" class="btn btn-success">Send Letter</button> -->
    </form>

    <a href="{{ route('letters.create') }}" class="btn btn-secondary mt-3">Back to Create</a>
</div>
@endsection