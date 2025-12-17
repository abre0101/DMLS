@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">My Letters</h1>
    <a href="{{ route('letters.create') }}" class="btn btn-primary">Create New Letter</a>

 

    @if($letters->isEmpty())
        <p>No letters found.</p>
    @else
        <div class="mb-3">
           
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Template</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Sent At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($letters as $letter)
                <tr>
                    <td>{{ $letter->template->name }}</td>
                    <td>
                        @if($letter->status == 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @elseif($letter->status == 'sent')
                            <span class="badge bg-success">Sent</span>
                        @elseif($letter->status == 'archived')
                            <span class="badge bg-warning text-dark">Archived</span>
                        @endif
                    </td>
                    <td>{{ $letter->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $letter->sent_at ? $letter->sent_at->format('Y-m-d H:i') : '-' }}</td>
                    <td>
                        <a href="{{ route('letters.show', $letter->id) }}" class="btn btn-sm btn-info">View</a>

                        @if($letter->status == 'draft')
                            <form action="{{ route('letters.update', $letter->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" name="send" value="1" class="btn btn-sm btn-success">Send</button>
                            </form>
                        @endif

                        @if($letter->status == 'sent')
                            <form action="{{ route('letters.archive', $letter->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">Archive</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection