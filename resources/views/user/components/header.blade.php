<header class="shark-header">
    <div class="shark-header__container">

        <a
            href="{{ route('home') }}"
            class="shark-header__logo"
            aria-label="მთავარ გვერდზე გადასვლა"
        >
            SHARK
        </a>

        <div class="shark-header__right">

            <nav
                id="sharkMainNavigation"
                class="shark-navigation"
                aria-label="მთავარი ნავიგაცია"
            >
                <a
                    href="{{ route('home') }}"
                    class="shark-navigation__link {{ request()->routeIs('home') ? 'is-active' : '' }}"
                >
                    მთავარი
                </a>

                <a
                    href="{{ route('company') }}"
                    class="shark-navigation__link {{ request()->routeIs('company') ? 'is-active' : '' }}"
                >
                    კომპანია
                </a>

                <a
                    href="{{ route('services') }}"
                    class="shark-navigation__link {{ request()->routeIs('services') ? 'is-active' : '' }}"
                >
                    სერვისები
                </a>

                <a
                    href="{{ route('projects') }}"
                    class="shark-navigation__link {{ request()->routeIs('projects') ? 'is-active' : '' }}"
                >
                    პროექტები
                </a>

                <a
                    href="{{ route('media') }}"
                    class="shark-navigation__link {{ request()->routeIs('media') ? 'is-active' : '' }}"
                >
                    მედია
                </a>

                <a
                    href="{{ route('contact') }}"
                    class="shark-navigation__link {{ request()->routeIs('contact') ? 'is-active' : '' }}"
                >
                    კონტაქტი
                </a>
            </nav>

            <div class="shark-language">
                <button
                    type="button"
                    id="sharkLanguageButton"
                    class="shark-language__button"
                    aria-label="ენის არჩევა"
                    aria-expanded="false"
                    aria-controls="sharkLanguageDropdown"
                >
                    <i class="fa-solid fa-globe"></i>

                    <span
                        id="sharkCurrentLanguage"
                        class="shark-language__code"
                    >
                        KA
                    </span>

                    <i class="fa-solid fa-chevron-down shark-language__arrow"></i>
                </button>

                <div
                    id="sharkLanguageDropdown"
                    class="shark-language__dropdown"
                >
                    <button
                        type="button"
                        class="shark-language__option is-selected"
                        data-language="ka"
                        data-code="KA"
                    >
                        <span class="shark-language__flag">🇬🇪</span>
                        <span>ქართული</span>
                    </button>

                    <button
                        type="button"
                        class="shark-language__option"
                        data-language="en"
                        data-code="EN"
                    >
                        <span class="shark-language__flag">🇬🇧</span>
                        <span>English</span>
                    </button>

                    <button
                        type="button"
                        class="shark-language__option"
                        data-language="ru"
                        data-code="RU"
                    >
                        <span class="shark-language__flag">🇷🇺</span>
                        <span>Русский</span>
                    </button>
                </div>
            </div>

            <button
                type="button"
                id="sharkMenuButton"
                class="shark-menu-button"
                aria-label="მენიუს გახსნა"
                aria-expanded="false"
                aria-controls="sharkMainNavigation"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>
    </div>
</header>
