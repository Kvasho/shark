@php
    // მენიუს პუნქტები: route => [სათაური, SVG path-ები]
    $menu = [
        'admin.dashboard' => ['მთავარი', '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M10 21v-6h4v6"/>'],
        'admin.company'   => ['კომპანია', '<rect x="4" y="3" width="16" height="18" rx="1.5"/><path d="M9 7h2M13 7h2M9 11h2M13 11h2M9 15h2M13 15h2"/>'],
        'admin.services'  => ['სერვისები', '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.5 2.5-2.4-.6-.6-2.4z"/>'],
        'admin.projects'  => ['პროექტები', '<path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>'],
        'admin.media'     => ['მედია', '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="m21 16-5-5-9 9"/>'],
        'admin.contact'   => ['კონტაქტი', '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>'],
    ];
@endphp

<aside class="admin-sidebar" id="admin-sidebar">
    <div class="admin-sidebar__brand">
        <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__logo">
            <span class="admin-sidebar__logo-mark">S</span>
            <span>SHARK <small>Admin</small></span>
        </a>

        <button type="button" class="admin-sidebar__close" data-sidebar-close aria-label="მენიუს დახურვა">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
        </button>
    </div>

    <nav class="admin-sidebar__nav" aria-label="ადმინ მენიუ">
        @foreach ($menu as $route => [$label, $icon])
            <a
                href="{{ route($route) }}"
                @class(['admin-sidebar__link', 'is-active' => request()->routeIs($route, "$route.*")])
                @if (request()->routeIs($route, "$route.*")) aria-current="page" @endif
            >
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icon !!}</svg>
                <span>{{ $label }}</span>
            </a>
        @endforeach
    </nav>

    <div class="admin-sidebar__footer">
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="admin-sidebar__link">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 4h6v6"/><path d="M20 4 10 14"/><path d="M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/></svg>
            <span>საიტის ნახვა</span>
        </a>
    </div>
</aside>
