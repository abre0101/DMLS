@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center text-primary">📄 Report Details</h2>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body px-4 py-5">

            <h3 class="mb-3 text-dark">{{ $report->title }}</h3>

            <div class="mb-3">
                <span class="fw-semibold">Status:</span>
                <span class="badge 
                    {{ $report->status === 'pending' ? 'bg-warning text-dark' : 
                       ($report->status === 'approved' ? 'bg-success' : 'bg-danger') }}">
                    {{ ucfirst($report->status) }}
                </span>
            </div>

            <ul class="list-unstyled mb-4">
                <li><strong>Department:</strong> {{ $report->department }}</li>
                <li><strong>Created By:</strong> {{ $report->created_by }}</li>
                <li><strong>Date Created:</strong> {{ $report->created_at->format('F j, Y') }}</li>
            </ul>

            <hr>

            <h5 class="mb-2">📝 Description</h5>
            <p class="text-muted">{{ $report->description }}</p>

            <div class="d-flex flex-wrap gap-2 mt-4">
                <a href="{{ route('director.reports.print', $report->id) }}" target="_blank" class="btn btn-outline-secondary">
                    🖨 Print View
                </a>
                <a href="{{ route('director.reports.pdf', $report->id) }}" class="btn btn-outline-danger">
                    📄 Download PDF
                </a>
                @if($report->status === 'pending')
                    <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-warning ms-auto">
                        ✏️ Review & Edit
                    </a>
                @endif
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('director.reportsOverview') }}" class="btn btn-outline-dark">
                    ⬅ Back to Reports Overview
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
