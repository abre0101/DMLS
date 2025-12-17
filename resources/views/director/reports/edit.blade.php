@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center">📝 Review Report</h2>

    <div class="card shadow-lg">
        <div class="card-body">
            <form action="{{ route('reports.update', $report->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Title</label>
                    <input type="text" class="form-control" value="{{ $report->title }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea class="form-control" rows="5" readonly>{{ $report->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Change Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $report->status === 'approved' ? 'selected' : '' }}>Approve</option>
                        <option value="rejected" {{ $report->status === 'rejected' ? 'selected' : '' }}>Reject</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('director.reportsOverview') }}" class="btn btn-outline-secondary">
                        ⬅ Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        ✅ Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
