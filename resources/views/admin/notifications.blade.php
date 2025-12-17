@extends('layouts.app')

@section('content')
    <h2>User Notifications</h2>

    <ul>
        @foreach ($notifications as $notification)
            <li>
                <strong>{{ $notification->created_at->diffForHumans() }}</strong><br>
                {{ $notification->data['message'] ?? 'No message' }}
            </li>
        @endforeach
    </ul>

    {{ $notifications->links() }}
@endsection
