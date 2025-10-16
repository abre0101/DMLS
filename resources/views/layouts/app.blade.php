<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>DMLS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- App and Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/log.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}" />

    @livewireStyles

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env("PUSHER_APP_KEY") }}',
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            encrypted: true,
        });

        @if(isset($document))
            // Listen to document updates
            window.Echo.private('documents.{{ $document->id }}')
                .listen('DocumentUpdated', (e) => {
                    alert('Document updated: ' + e.document.title);
                    // You can update the DOM here to show live changes/comments
                });

            // Listen to new comments
            window.Echo.private('documents.{{ $document->id }}')
                .listen('CommentCreated', (e) => {
                    alert('New comment: ' + e.comment.comment);
                    // Append comment to the comments section dynamically
                });
        @endif
    </script>
</head>

@php
    $theme = auth()->check() && auth()->user()->settings ? auth()->user()->settings->theme : 'light';
@endphp

<body class="{{ $theme }}-mode">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Navigation Bar -->
    <header>
        <nav class="navbar navbar-expand-lg shadow-sm {{ $theme === 'dark' ? 'navbar-dark bg-dark' : 'navbar-light bg-light' }}">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ url('/') }}">📁 DMLS</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                        @else
                            @role('Admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                                </li>
                            @endrole
                            @role('Director')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('director.dashboard') }}">Director Dashboard</a>
                                </li>
                            @endrole
                            @role('Manager')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('manager.dashboard') }}">Manager Dashboard</a>
                                </li>
                            @endrole
                            @role('Employee')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employee.dashboard') }}">Employee Dashboard</a>
                                </li>
                            @endrole

                            <li class="nav-item">
                                <a class="nav-link text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mt-4">
        @yield('content')
    </main>

    <!-- Theme Toggle Button -->
    <button id="themeToggle" class="btn btn-outline-secondary position-fixed bottom-0 end-0 m-4"
            title="Toggle Theme">
        <i id="themeIcon" class="bi bi-moon-stars"></i>
    </button>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Theme Switcher Script -->
    <script>
        const themeToggleButton = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const navbar = document.querySelector('nav.navbar');

        function setTheme(theme) {
            document.body.classList.remove('light-mode', 'dark-mode');
            document.body.classList.add(`${theme}-mode`);

            if (theme === 'dark') {
                navbar.classList.remove('navbar-light', 'bg-light');
                navbar.classList.add('navbar-dark', 'bg-dark');
                themeIcon.classList.replace('bi-moon-stars', 'bi-brightness-high');
            } else {
                navbar.classList.remove('navbar-dark', 'bg-dark');
                navbar.classList.add('navbar-light', 'bg-light');
                themeIcon.classList.replace('bi-brightness-high', 'bi-moon-stars');
            }
        }

        themeToggleButton.addEventListener('click', () => {
            const currentTheme = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });

        window.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                setTheme(savedTheme);
            }
        });
    </script>

    <!-- Yield custom page scripts -->
    @yield('scripts')

</body>

</html>
