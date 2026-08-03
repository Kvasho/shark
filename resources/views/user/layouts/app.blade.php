<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
        content="@yield(
            'description',
            'SHARK — კომპანიის ოფიციალური ვებგვერდი'
        )"
    >

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('favicon.png') }}"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/user/app.css',
        'resources/js/user/app.js'
    ])

    @stack('styles')
</head>

<body class="@yield('bodyClass', 'shark-site') shark-page-loading">

    <div
        id="sharkPageLoader"
        class="shark-page-loader is-visible"
        role="status"
        aria-label="გვერდი იტვირთება"
    >
        <div class="shark-page-loader__content">

            <div
                class="shark-page-loader__logo"
                aria-label="SHARK"
            >
                SHARK
            </div>

            <div
                class="shark-page-loader__line"
                aria-hidden="true"
            ></div>

        </div>
    </div>

    <div class="shark-site-wrapper">

        @include('user.components.header')

        <main class="shark-main">
            @yield('content')
        </main>

        @include('user.components.footer')

    </div>

    @stack('scripts')
</body>
</html>
