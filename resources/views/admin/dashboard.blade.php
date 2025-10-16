@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Admin Dashboard</h1>

    {{-- Summary Cards --}}
    <div class="row mb-5">
        @php
            $summaryCards = [
                ['title' => 'Total Documents', 'value' => $totalDocuments],
                ['title' => 'Pending Approvals', 'value' => $pendingApprovals],
                ['title' => 'Active Users (30d)', 'value' => $activeUsers],
                ['title' => 'Letters Sent', 'value' => $lettersSent],
                ['title' => 'Departments', 'value' => $departmentsCount],
                ['title' => 'Storage Used (GB)', 'value' => $storageUsed],
            ];
        @endphp
        @foreach($summaryCards as $card)
            <div class="col-md-2">
                <div class="card text-center p-3 shadow-sm">
                    <h5>{{ $card['title'] }}</h5>
                    <p class="display-6">{{ $card['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Documents by Status Chart --}}
    <section class="mb-5">
        <h2>Documents by Status</h2>
        <canvas id="documentsStatusChart" width="400" height="150"></canvas>
    </section>

    {{-- Recent Documents Table --}}
    <section class="mb-5">
        <h2>Recent Documents</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Uploader</th>
                    <th>Status</th>
                    <th>Uploaded At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentDocuments as $doc)
                    <tr>
                        <td>{{ $doc->title }}</td>
                        <td>{{ $doc->uploadedBy->name ?? 'N/A' }}</td>
                        <td>
                            @if (is_null($doc->director_approval ))
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif ($doc->director_approval )
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td>{{ $doc->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No recent documents found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    {{-- User Management Section --}}
    <section>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Users</h2>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Create User</a>
        </div>

        @if($users->count())
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Blocked</span>
                                @endif
                            </td>
                            <td>{{ $user->role->name ?? 'N/A' }}</td>
                            <td>{{ $user->department->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block" 
                                      onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination links --}}
            {{ $users->links() }}

        @else
            <p>No users found.</p>
        @endif
    </section>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('documentsStatusChart').getContext('2d');

    const data = {
        labels: {!! json_encode(array_keys($documentsByStatus)) !!},
        datasets: [{
            label: 'Documents Count',
            data: {!! json_encode(array_values($documentsByStatus)) !!},
            backgroundColor: [
                'rgba(255, 206, 86, 0.6)',   // pending - yellow
                'rgba(75, 192, 192, 0.6)',   // approved - green
                'rgba(255, 99, 132, 0.6)'    // rejected - red
            ],
            borderColor: [
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    };

    const options = {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            },
            tooltip: {
                enabled: true
            }
        }
    };

    new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: options
    });
});
</script>
@endsection
