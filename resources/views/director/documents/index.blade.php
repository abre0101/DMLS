@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Documents</h1>


      <form method="GET" action="{{ route('director.documents.index') }}" class="mb-4 d-flex gap-2">
    <div class="col-md-6">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search documents..."
            value="{{ request('search') }}"
        />
    </div>

    <div class="col-md-4">
        <select name="status" class="form-select">
            <option value="">-- All Statuses --</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
    </div>

    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>


    @if($documents->isEmpty())
        <p>No documents found.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documents as $doc)
                    <tr>
                        <td>{{ $doc->title }}</td>
                        <td class="text-capitalize">{{ $doc->status }}</td>
                        <td>{{ $doc->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('director.documents.show', $doc->id) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $documents->withQueryString()->links() }}
    @endif
</div>
@endsection
