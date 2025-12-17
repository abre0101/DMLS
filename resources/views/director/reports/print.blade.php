<!DOCTYPE html>
<html>
<head>
    <title>Printable Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .container { width: 80%; margin: auto; padding: 20px; }
        h2 { text-align: center; }
        .section { margin-bottom: 20px; }
        .label { font-weight: bold; }
        .value { margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📄 Report #{{ $report->id }}</h2>

        <div class="section">
            <div><span class="label">Title:</span> <span class="value">{{ $report->title }}</span></div>
            <div><span class="label">Status:</span> <span class="value">{{ ucfirst($report->status) }}</span></div>
            <div><span class="label">Department:</span> <span class="value">{{ $report->department }}</span></div>
            <div><span class="label">Created By:</span> <span class="value">{{ $report->created_by }}</span></div>
            <div><span class="label">Date:</span> <span class="value">{{ $report->created_at->format('Y-m-d') }}</span></div>
        </div>

        <div class="section">
            <div class="label">Description:</div>
            <div class="value">{{ $report->description }}</div>
        </div>
    </div>
</body>
</html>
