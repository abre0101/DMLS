<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Letter PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            line-height: 1.6;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h2>Generated Letter</h2>
    <hr>
    <p>{!! nl2br(e($letter->content)) !!}</p>
</body>
</html>
