@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center display-5">Reports Overview</h1>

        @if($reports->isEmpty())
            <div class="alert alert-info text-center">
                No reports found.
            </div>
        @else
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Department</th>
                                <th scope="col">Created By</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $index => $report)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $report->title ?? 'Untitled' }}</td>
                                    <td>{{ $report->department->name ?? 'N/A' }}</td>
                                    <td>{{ $report->user->name ?? 'Unknown' }}</td>
                                    <td>{{ $report->created_at->format('d M Y') }}</td>
                                    <td>
                                        @php
                                            $status = strtolower($report->status);
                                            $badgeClass = match($status) {
                                                'approved' => 'success',
                                                'pending' => 'warning',
                                                'rejected' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($report->status) }}</span>
                                    </td>
                                    <td class="text-center">
                                      <a href="{{ route('director.reports.show', $report->id) }}" class="btn btn-sm btn-outline-primary"> View</a>

                                        @if($report->status === 'pending')
                                            <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-sm btn-outline-warning">
                                                Review
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
