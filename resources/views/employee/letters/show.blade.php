@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Received Letter</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>  {{ $letter->template->name ?? 'unntitled letter' }}</strong>
        </div>
        <div class="card-body">
            <p><strong>From:</strong> {{ $letter->sender->name ?? 'Unknown Sender' }}</p>
            <p><strong>Received At:</strong> {{ $letter->created_at->format('d M Y, h:i A') }}</p>
            <hr>
            <div class="letter-content" style="white-space: pre-line;">
                {!! nl2br(e($letter->content)) !!}
            </div>
          
        </div>
    </div>

 <a href="{{ route('employee.letters.replyform', $letter) }}" class="btn btn-primary">
    Reply to Letter
</a>

</div>
@endsection
