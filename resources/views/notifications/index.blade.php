@extends('layouts.app')

@section('content')
    <h1>Notifications</h1>

    @foreach ($notifications as $notification)
        <div>
            {{ $notification->data['message'] ?? 'No message available' }}
            <small>{{ $notification->created_at->diffForHumans() }}</small>
        </div>
    @endforeach
@endsection
