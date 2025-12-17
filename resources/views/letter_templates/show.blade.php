@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $letterTemplate->name }}</h2>

    <div class="card">
        <div class="card-body" style="
            white-space: pre-wrap; 
            border: 1px solid #ccc; 
            padding: 15px; 
            border-radius: 5px; 
            background-color: #f8f9fa;
            font-family: monospace;
            max-height: 400px; 
            overflow-y: auto;
        ">
            {!! $letterTemplate->content !!}
        </div>
    </div>

    <a href="{{ route('letter-templates.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
