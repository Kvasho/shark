<header class="admin-header">
    <button type="button" class="admin-header__toggle" data-sidebar-open aria-label="მენიუს გახსნა" aria-controls="admin-sidebar">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <h1 class="admin-header__title">@yield('title', 'ადმინ პანელი')</h1>

    <div class="admin-header__user">
        <span class="admin-header__avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->username, 0, 1)) }}</span>
        <span class="admin-header__name">{{ auth()->user()->username }}</span>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="admin-header__logout">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 4h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-4"/><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/></svg>
                <span>გასვლა</span>
            </button>
        </form>
    </div>
</header>
