<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        @import url('https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css');

        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f9fafb; /* Light gray background */
        }

        .hero-section {
            background: linear-gradient(to bottom, #4f46e5, #6366f1); /* Purple gradient */
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }

        .action-btn {
            background-color: #ffffff;
            color: #4f46e5;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .action-btn:hover {
            background-color: #f3f4f6;
        }

        footer {
            background-color: #4f46e5;
            color: white;
            padding: 20px;
            text-align: center;
        }

        footer p {
            margin: 0;
        }

        @media (min-width: 1024px) {
            .hero-section h1 {
                font-size: 4rem;
            }

            .hero-section p {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 dark:text-white">

<!-- Hero Section -->
<div class="hero-section">
    <h1>Welcome to task management</h1>

    @if (Route::has('login'))
        <div class="mt-6">
            @auth
                <a href="{{ url('/dashboard') }}" class="action-btn">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="action-btn mr-3">
                    Log in
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="action-btn ">
                        Register
                    </a>
                @endif
            @endauth
        </div>
    @endif
</div>

<!-- Footer -->
<footer>
    <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
</footer>

</body>
</html>
