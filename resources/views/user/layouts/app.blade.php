<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>@yield('title', 'SHARK')</title>

    <meta
        name="description"
        content="@yield('description', 'SHARK — კომპანიის ოფიციალური ვებგვერდი')"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/user/app.css',
        'resources/js/user/app.js'
    ])

    @stack('styles')
</head>

<body class="@yield('bodyClass', 'shark-site')">

    @include('user.components.header')

    <main class="shark-main">
        @yield('content')
    </main>

    @include('user.components.footer')

    @stack('scripts')
</body>
</html>
