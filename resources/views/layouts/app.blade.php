<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GDGOC EventFlow</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --gd-blue: #4285F4;
            --gd-red: #EA4335;
            --gd-yellow: #FBBC05;
            --gd-green: #34A853;
            --bg-light: #F8F9FA;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--bg-light);
            color: #1f2937;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: white;
            border-right: 1px solid #e5e7eb;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        /* Content Wrapper */
        .content-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}

        .btn-primary { background-color: var(--gd-blue); border: none; border-radius: 12px; font-weight: 600; }
        
        .form-control-search {
            border-radius: 12px;
            background-color: #f3f4f6;
            border: none;
            padding: 10px 20px;
            width: 300px;
        }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.active { transform: translateX(0); }
            .content-wrapper { margin-left: 0; }
        }
        .tab-btn {
    background: none;
    border: none;
    padding: 10px 14px;
    font-weight: 500;
    color: #6c757d;
    border-bottom: 2px solid transparent;
}

.tab-btn.active {
    color: #0d6efd;
    border-bottom: 2px solid #0d6efd;
}

.tab-btn:hover {
    color: #0d6efd;
}

.tab-content {
    animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}
    </style>
    @stack('styles')
</head>
<body class="antialiased">
    <div class="d-flex">
        @include('layouts.sidebar')

        <div class="content-wrapper flex-grow-1">
            @include('layouts.navigation')

            <main class="py-4 px-4 px-lg-5 flex-grow-1">
                @yield('content')
            </main>

            <footer class="py-4 bg-white border-top text-center mt-auto">
                <p class="text-muted small mb-0">© {{ date('Y') }} GDGOC EventFlow • Built for ISTTS</p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>