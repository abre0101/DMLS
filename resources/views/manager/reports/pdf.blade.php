<!DOCTYPE html>
<html>
<head>
    <title>Manager Report PDF</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Manager Dashboard Report</h1>
    <p><strong>Department:</strong> {{ $department }}</p>

    <table>
        <thead>
            <tr>
                <th>Section</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Pending Documents</td><td>{{ $pendingDocuments }}</td></tr>
            <tr><td>Total Documents</td><td>{{ $totalDocuments }}</td></tr>
            <tr><td>Letters Received</td><td>{{ $lettersReceived }}</td></tr>
            <tr><td>Pending Approval Requests</td><td>{{ $pendingApprovals }}</td></tr>
        </tbody>
    </table>
</body>
</html>
