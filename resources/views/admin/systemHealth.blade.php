@extends('layouts.admin')

@section('content')
    <h2>System Health</h2>

    <ul>
        @foreach ($result->checks as $check)
            <li>
                {{ $check->label }}:
                <span style="color: {{ $check->status === 'ok' ? 'green' : 'red' }}">
                    {{ $check->status }}
                </span>
            </li>
        @endforeach
    </ul>
@endsection
