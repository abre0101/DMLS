<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DMLS</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #007bff;
            margin-bottom: 20px;
            font-size: 2.5em;
        }
        .nav-links {
            margin: 20px 0;
        }
        .nav-links a {
            margin: 0 15px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            transition: color 0.3s;
        }
        .nav-links a:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        .message {
            margin: 20px 0;
            color: #555;
            font-size: 1.2em;
        }
        .features {
            margin-top: 30px;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 8px;
            text-align: left;
        }
        .features h2 {
            color: #007bff;
            margin-bottom: 15px;
            font-size: 1.8em;
        }
        .features ul {
            list-style-type: none;
            padding: 0;
        }
        .features li {
            margin: 10px 0;
            padding: 10px;
            border-left: 4px solid #007bff;
            background-color: #f8f9fa;
            transition: background-color 0.3s;
        }
        .features li:hover {
            background-color: #e2e6ea;
        }
        footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Document Management and Letter System (DMLS)</h1>

        <div class="message">
            <p>Please log in or register to access the application.</p>
        </div>

        <div class="nav-links">
            @if (Route::has('login'))
                @auth
                    <p>Welcome back, {{ Auth::user()->name }}!</p>
                    <a href="{{ url('/dashboard') }}">Go to Dashboard</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            @endif
        </div>

        <div class="features">
            <h2>Key Features</h2>
            <ul>
                <li>Centralized Document Storage for easy access and management.</li>
                <li>Customizable Workflows for document approvals and tracking.</li>
                <li>Letter Management with template-based generation.</li>
                <li>Real-time Collaboration</li>
                <li>Secure Role-Based Access Control for user management.</li>
            </ul>
        </div>

        <footer>
            <p>Feel free to explore the application!</p>
        </footer>
    </div>
</body>
</html>
