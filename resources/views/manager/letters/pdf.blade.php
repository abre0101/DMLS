<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Letter #{{ $letter->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 40px 50px;
            color: #333;
        }
        header {
            text-align: center;
            border-bottom: 2px solid #004085;
            padding-bottom: 15px;
            margin-bottom: 30px;
            position: relative;
        }
        .logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 120px;
            height: auto;
        }
        .branding {
            font-weight: 700;
            font-size: 26px;
            color: #004085;
            margin-bottom: 5px;
        }
        .subheader {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .content {
            margin-top: 20px;
            line-height: 1.5;
            font-size: 14px;
        }
        footer {
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 40px;
            padding-top: 10px;
        }
        /* DOCX friendly simple styling */
        h1, h2, h3, strong {
            color: #004085;
        }
    </style>
</head>
<body>

<header>
    {{-- Logo: use absolute path or base64 encode for PDF --}}
    <img src="{{ public_path('images/logo.png') }}" alt="Company Logo" class="logo" />
    
    <div class="branding">{{ $manager->department->name }} Department</div>
    <div class="subheader">Official Letter</div>
</header>

<section>
    <p><strong>To:</strong> {{ $letter->receiver->name }}</p>
    <p><strong>From:</strong> {{ $letter->sender->name }}</p>
    <p><strong>Subject:</strong> {{ $letter->subject }}</p>
</section>

<hr />

<section class="content">
    {!! nl2br(e($letter->content)) !!}
</section>

<footer>
    &copy; {{ date('Y') }} Your Company Name — Generated on {{ now()->format('Y-m-d H:i') }}
</footer>

</body>
</html>
