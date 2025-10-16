<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Letter #{{ $letter->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        header { text-align: center; margin-bottom: 30px; }
        .branding { font-weight: bold; font-size: 24px; }
        .content { margin-top: 20px; }
    </style>
</head>
<body>
    <header>
        <div class="branding">
            {{ $employee->department->name }} Department
        </div>
        <div>Official Letter</div>
        <hr />
    </header>

    <p><strong>To:</strong> {{ $letter->receiver->name }}</p>
    <p><strong>From:</strong> {{$letter->sender->name }}</p>
 <div class="letter-content" style="white-space: pre-line;">
                {!! nl2br(e($letter->content)) !!}
            </div>   
    <hr />
    <footer>
        <hr />
        <small>Generated on {{ now()->format('Y-m-d H:i') }}</small>
    </footer>
</body>
</html>
