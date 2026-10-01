<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('title', 'ადმინ პანელი') | SHARK Admin</title>

    @vite([
        'resources/css/admin/app.css',
        'resources/js/admin/app.js',
    ])

    @stack('styles')
</head>
<body class="admin">
    @include('admin.components.sidebar')

    <div class="admin-overlay" data-sidebar-close></div>

    <div class="admin-main">
        @include('admin.components.header')

        <main class="admin-content">
            @if (session('success'))
                <div class="admin-alert admin-alert--success admin-alert--flash" role="status">{{ session('success') }}</div>
            @endif

            @yield('content')
        </main>

        @include('admin.components.footer')
    </div>

    @stack('scripts')
</body>
</html>
