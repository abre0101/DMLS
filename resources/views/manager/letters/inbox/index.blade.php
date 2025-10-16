@extends('layouts.app')

@section('title', 'Letters Inbox')

@section('content')
<div class="container">
    <h1>Inbox</h1>

    @if($letters->count())
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Date Received</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($letters as $letter)
                    <tr>
                        <td>{{ $letter->sender->name }}</td>
                        <td>
                            <a href="{{ route('manager.letters.show', $letter) }}">
                                {{ Str::limit($letter->content ?? 'No Subject', 50) }}
                            </a>
                        </td>
                        <td>{{ $letter->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @if($letter->is_read)
                                <span class="badge bg-success">Read</span>
                            @else
                                <span class="badge bg-warning">Unread</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('manager.letters.show', $letter) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $letters->links() }}

    @else
        <p>No letters found in your inbox.</p>
    @endif

  <a href="{{ route('manager.letters.send.form') }}" class="btn btn-success mt-3">Compose New Letter</a>

</div>
@endsection
