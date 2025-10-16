@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Director Dashboard</h1>

    <style>
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        h1 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #1a202c;
            letter-spacing: 1px;
        }

        .btn {
            font-weight: 600;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1e40af;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        .btn-success:hover {
            background-color: #15803d;
        }

        .btn-info:hover {
            background-color: #0e7490;
        }

        .btn-warning:hover {
            background-color: #b45309;
            color: #fff;
        }

        .mb-4 {
            margin-bottom: 2rem !important;
        }

        .card {
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            background-color: #ffffff;
            transition: box-shadow 0.35s ease, transform 0.3s ease;
            cursor: default;
        }

        .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .card-header {
            font-weight: 700;
            background-color: #f9fafb;
            border-bottom: 2px solid #3b82f6;
            padding: 1.25rem 1.5rem;
            font-size: 1.25rem;
            color: #1f2937;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            user-select: none;
            letter-spacing: 0.03em;
        }

        .card-body {
            padding: 1.5rem 2rem;
            font-size: 1rem;
            line-height: 1.6;
            color: #374151;
            min-height: 150px;
        }

        .card-body p strong {
            color: #111827;
            font-weight: 600;
        }

        ul {
            list-style: none;
            padding-left: 1.5rem;
            margin-top: 0.75rem;
            margin-bottom: 1rem;
            color: #4b5563;
            font-size: 0.95rem;
            line-height: 1.4;
        }

        ul li {
            margin-bottom: 0.5rem;
            padding-left: 0.25rem;
            position: relative;
        }

        ul li::before {
            content: '•';
            color: #3b82f6;
            position: absolute;
            left: -1rem;
            font-weight: bold;
            font-size: 1.1rem;
            line-height: 1;
            top: 0.2rem;
        }

        .row {
            margin-left: -0.75rem;
            margin-right: -0.75rem;
        }

        .col-md-4, .col-md-6, .col-md-12 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            margin-bottom: 1.5rem;
        }

        #uploadTrendsChart {
            width: 100% !important;
            height: 300px !important;
        }
    </style>

    <div class="mb-4">
        <a href="{{ route('director.documents.index') }}" class="btn btn-secondary">Manage Documents</a>
        <a href="{{ route('director.letters.index') }}" class="btn btn-success">Manage Letters</a>
        <a href="{{ route('director.departments.activity') }}" class="btn btn-info">Department Activity</a>
        <a href="{{ route('director.tasks.overdue') }}" class="btn btn-warning">Tasks</a>
    </div>

    <div class="row">
     <div class="col-md-4">
            <div class="card">
                <div class="card-header">Document & Letter Analytics</div>
                <div class="card-body">
                    <h5>Top Used Letter Templates</h5>
                    <ul>
                        @foreach($topTemplates as $template)
                            <li>{{ $template->template->name }} ({{ $template->usage_count }})</li>
                        @endforeach
                    </ul>
                    <h4>Active Correspondents</h4>
                    <ul>
                        @foreach($activeCorrespondents as $correspondent)
                            <li>
                                Sender: {{ $correspondent->sender->name ?? 'Unknown' }},
                                Receiver: {{ $correspondent->receiver->name ?? 'Unknown' }}
                                ({{ $correspondent->count }})
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Approval Oversight</div>
                <div class="card-body">
                    <p><strong>Pending Approvals:</strong> {{ $executivePendingApprovals }}</p>
                 
                </div>
            </div>
        </div>

    
     

    <div class="col-md-4">
            <div class="card">
                <div class="card-header">User Status</div>
                <div class="card-body">
                    <p><strong>Active Users:</strong> {{ $activeUsersCount }}</p>
                    <p><strong>Inactive Users:</strong> {{ $inactiveUsersCount }}</p>
                </div>
            </div>
        </div>
    </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Departmental Activity Reports</div>
                <div class="card-body">
                    <h5>Department Document Counts</h5>
                    <ul>
                        @foreach($departmentDocsCount as $dept)
                            <li>{{ $dept->department }}: {{ $dept->total_docs }}</li>
                        @endforeach
                    </ul>
                    <h5>Department Letter Counts</h5>
                    <ul>
                        @foreach($departmentLettersCount as $dept)
                            <li>{{ $dept->department ?? $dept->name ?? 'Unknown Department' }}: {{ $dept->total_letters }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

           

    <!-- Document Upload Trends Chart -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Document Upload Trends (Last 6 Months)</div>
                <div class="card-body">
                    <canvas id="uploadTrendsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('uploadTrendsChart').getContext('2d');
    const uploadTrendsData = {
        labels: {!! json_encode($docUploadTrends->pluck('month')) !!},
        datasets: [{
            label: 'Document Uploads',
            data: {!! json_encode($docUploadTrends->pluck('uploads')) !!},
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            tension: 0.4
        }]
    };

    new Chart(ctx, {
        type: 'line',
        data: uploadTrendsData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
