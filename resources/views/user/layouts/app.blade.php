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

    <script>
        (function () {
            document.documentElement.classList.add('shark-page-loading');
            if (sessionStorage.getItem('sharkPageTransitionPending') === 'true') {
                document.documentElement.classList.add('shark-transition-arrival');
            }
            const savedTheme = localStorage.getItem('sharkTheme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.dataset.theme = savedTheme || (prefersDark ? 'dark' : 'light');
        })();
    </script>

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

    <div id="sharkPageTransition" class="shark-page-transition" aria-hidden="true">
        <div class="shark-page-transition__inner">
            <div class="shark-page-transition__logo" aria-label="SHARK">
                <span>SHARK</span>
                <span class="shark-page-transition__logo-fill">SHARK</span>
            </div>
            <div class="shark-page-transition__track"><span></span></div>
            <small class="shark-page-transition__value">0%</small>
        </div>
    </div>

    @include('user.components.header')

    <main class="shark-main">
        @yield('content')
    </main>

    @include('user.components.footer')

    @stack('scripts')
</body>
</html>
