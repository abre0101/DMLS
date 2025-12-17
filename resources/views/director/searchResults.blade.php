@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Search Results</h1>

    @if ($documents->count() == 0)
        <p>No documents found matching your search.</p>
    @else
        <ul>
            @foreach ($documents as $doc)
                <li>
                    <a href="{{ route('director.document.show', $doc->id) }}">{{ $doc->title }}</a>
                    — Status: {{ $doc->status }}
                </li>
            @endforeach
        </ul>

        {{ $documents->links() }}
    @endif
</div>
@endsection
