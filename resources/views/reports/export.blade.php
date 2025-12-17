<!DOCTYPE html>
<html>
<head>
    <style>
        /* Add custom styles here */
        body { font-family: DejaVu Sans, sans-serif; margin: 50px; }
        header, footer {
            width: 100%;
            position: fixed;
            left: 0;
            color: #888;
            font-size: 12px;
        }
        header {
            top: -40px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        footer {
            bottom: -40px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
        }
        .logo {
            height: 50px;
        }
    </style>
</head>
<body>

<header>
    <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Company Logo">
    <div>Your Company Name</div>
</header>

<footer>
    &copy; {{ date('Y') }} Your Company. All rights reserved.
</footer>

<main>
    <h1>{{ $reportTitle }}</h1>
    <p>{{ $reportContent }}</p>
    <!-- Add more report details here -->
</main>

</body>
</html>
