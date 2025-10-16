@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Your Reply</h2>

    <div class="mb-4">
        <p><strong>Original Letter from {{ $letter->sender->name }}:</strong></p>
        <blockquote class="blockquote">
            <p>{!! nl2br(e($letter->content)) !!}</p>
            <footer class="blockquote-footer">{{ $letter->created_at->format('d M Y, h:i A') }}</footer>
        </blockquote>
    </div>

    <form action="{{ route('employee.letters.updateReply', [$letter, $reply]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="reply">Edit Your Reply</label>
            <textarea name="reply" id="reply" rows="6" class="form-control" required>{{ old('reply', $reply->content) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Reply</button>
    </form>
</div>
@endsection
