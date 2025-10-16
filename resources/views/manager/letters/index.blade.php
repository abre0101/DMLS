@extends('layouts.app')

@section('content')
    <h1>Your Letters</h1>

    @if($letters->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sender</th>
                    <th>Receiver</th>
                    <th>Status</th>
                    <th>Sent At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($letters as $letter)
                    <tr>
                        <td>{{ $letter->id }}</td>
                        <td>{{ optional($letter->sender)->name ?? 'N/A' }} ({{ optional($letter->sender)->email ?? '' }})</td>
                        <td>{{ optional($letter->receiver)->name ?? 'N/A' }} ({{ optional($letter->receiver)->email ?? '' }})</td>
                        <td>{{ isset($letter->status) ? ucfirst($letter->status) : 'N/A' }}</td>
                        <td>{{ $letter->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('manager.letters.show', $letter) }}" class="btn btn-sm btn-primary me-1">View</a>
                            <a href="{{ route('letters.exportPdf', $letter->id) }}" class="btn btn-sm btn-secondary">Export PDF</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $letters->links() }}
    @else
        <p>No letters found.</p>
    @endif
@endsection
