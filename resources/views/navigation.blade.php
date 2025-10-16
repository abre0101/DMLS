<!-- resources/views/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Navigation</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        nav {
            background-color: #f8f9fa;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 15px;
        }
        nav ul li a {
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s;
        }
        nav ul li a:hover {
            color: #0056b3; /* Darker shade on hover */
        }
        nav ul li a.active {
            font-weight: bold;
            color: #0056b3; 
        }
        nav ul li button {
            cursor: pointer;
            background: none;
            border: none;
            color: #007bff;
            text-decoration: underline;
            padding: 0;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('documents.index') }}" class="{{ request()->routeIs('documents.index') ? 'active' : '' }}">Documents</a></li>
            <li><a href="{{ route('letters.index') }}" class="{{ request()->routeIs('letters.index') ? 'active' : '' }}">Letters</a></li>
            
            @auth
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </li>
            @endauth

            @guest
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @endguest
        </ul>
    </nav>

    <div class="container">
      
        <h1>Welcome!!!</h1>
        
    </div>
</body>
</html>