import './i18n';

document.addEventListener('DOMContentLoaded', function () {
    const pageTransition = document.getElementById('sharkPageTransition');
    const transitionValue = pageTransition?.querySelector(
        '.shark-page-transition__value'
    );
    let transitionTimer = null;

    function setTransitionProgress(value) {
        if (!pageTransition) {
            return;
        }

        const safeValue = Math.max(0, Math.min(value, 100));
        pageTransition.style.setProperty(
            '--shark-loader-progress',
            `${safeValue}%`
        );

        if (transitionValue) {
            transitionValue.textContent = `${Math.round(safeValue)}%`;
        }
    }

    function revealCurrentPage() {
        if (!pageTransition) {
            document.documentElement.classList.remove('shark-page-loading');
            return;
        }

        setTransitionProgress(100);

        const isTransitionArrival = document.documentElement.classList.contains(
            'shark-transition-arrival'
        );

        if (isTransitionArrival) {
            document.documentElement.classList.add(
                'shark-transition-exiting'
            );

            window.setTimeout(function () {
                document.documentElement.classList.remove(
                    'shark-page-loading',
                    'shark-page-leaving',
                    'shark-transition-arrival'
                );
                sessionStorage.removeItem('sharkPageTransitionPending');

                window.setTimeout(function () {
                    setTransitionProgress(0);
                    document.documentElement.classList.remove(
                        'shark-transition-exiting'
                    );
                }, 900);
            }, 60);

            return;
        }

        window.setTimeout(function () {
            document.documentElement.classList.add(
                'shark-transition-exiting'
            );
        }, 260);

        window.setTimeout(function () {
            document.documentElement.classList.remove(
                'shark-page-loading',
                'shark-page-leaving',
                'shark-transition-arrival'
            );
            sessionStorage.removeItem('sharkPageTransitionPending');

            window.setTimeout(function () {
                setTransitionProgress(0);
                document.documentElement.classList.remove(
                    'shark-transition-exiting'
                );
            }, 900);
        }, 560);
    }

    function startPageTransition(url) {
        if (!pageTransition || document.documentElement.classList.contains('shark-page-leaving')) {
            return;
        }

        document.documentElement.classList.remove('shark-transition-exiting');
        document.documentElement.classList.add('shark-page-leaving');
        setTransitionProgress(8);
        let progress = 8;

        transitionTimer = window.setInterval(function () {
            progress += Math.max(2, (92 - progress) * 0.16);
            setTransitionProgress(Math.min(progress, 92));
        }, 90);

        window.setTimeout(function () {
            window.clearInterval(transitionTimer);
            setTransitionProgress(100);

            window.setTimeout(function () {
                document.documentElement.classList.add(
                    'shark-transition-exiting'
                );
            }, 220);

            window.setTimeout(function () {
                sessionStorage.setItem('sharkPageTransitionPending', 'true');
                window.location.href = url;
            }, 540);
        }, 720);
    }

    function preventTransitionScroll(event) {
        if (document.documentElement.classList.contains('shark-page-leaving')) {
            event.preventDefault();
        }
    }

    window.addEventListener('wheel', preventTransitionScroll, {
        passive: false
    });
    window.addEventListener('touchmove', preventTransitionScroll, {
        passive: false
    });

    document.addEventListener('click', function (event) {
        const link = event.target.closest('a[href]');

        if (
            !link ||
            event.defaultPrevented ||
            event.button !== 0 ||
            event.metaKey ||
            event.ctrlKey ||
            event.shiftKey ||
            event.altKey ||
            link.target === '_blank' ||
            link.hasAttribute('download')
        ) {
            return;
        }

        const destination = new URL(link.href, window.location.href);
        const isSamePageAnchor =
            destination.pathname === window.location.pathname &&
            destination.search === window.location.search &&
            destination.hash;

        if (
            destination.origin !== window.location.origin ||
            isSamePageAnchor ||
            !['http:', 'https:'].includes(destination.protocol)
        ) {
            return;
        }

        event.preventDefault();
        startPageTransition(destination.href);
    });

    if (document.readyState === 'complete') {
        revealCurrentPage();
    } else {
        window.addEventListener('load', revealCurrentPage, { once: true });
    }

    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.clearInterval(transitionTimer);
            revealCurrentPage();
        }
    });

    const menuButton = document.getElementById('sharkMenuButton');
    const navigation = document.getElementById('sharkMainNavigation');
    const themeButton = document.getElementById('sharkThemeButton');

    const languageContainer = document.querySelector('.shark-language');
    const languageButton = document.getElementById('sharkLanguageButton');
    const currentLanguage = document.getElementById('sharkCurrentLanguage');
    const languageOptions = document.querySelectorAll(
        '.shark-language__option'
    );

    function closeMenu() {
        if (!menuButton || !navigation) {
            return;
        }

        menuButton.classList.remove('is-open');
        navigation.classList.remove('is-open');
        menuButton.setAttribute('aria-expanded', 'false');
    }

    function closeLanguageDropdown() {
        if (!languageContainer || !languageButton) {
            return;
        }

        languageContainer.classList.remove('is-open');
        languageButton.setAttribute('aria-expanded', 'false');
    }

    function updateThemeButton() {
        if (!themeButton) {
            return;
        }

        const darkModeIsActive =
            document.documentElement.dataset.theme === 'dark';
        const icon = themeButton.querySelector('i');

        themeButton.setAttribute('aria-pressed', String(darkModeIsActive));
        themeButton.setAttribute(
            'aria-label',
            window.SharkI18n.translate(darkModeIsActive
                ? 'დღის რეჟიმის ჩართვა'
                : 'ღამის რეჟიმის ჩართვა')
        );

        if (icon) {
            icon.className = darkModeIsActive
                ? 'fa-solid fa-sun'
                : 'fa-solid fa-moon';
        }
    }

    if (themeButton) {
        updateThemeButton();

        themeButton.addEventListener('click', function () {
            const nextTheme =
                document.documentElement.dataset.theme === 'dark'
                    ? 'light'
                    : 'dark';

            document.documentElement.dataset.theme = nextTheme;
            localStorage.setItem('sharkTheme', nextTheme);
            updateThemeButton();
        });
    }

    if (menuButton && navigation) {
        menuButton.addEventListener('click', function () {
            const menuIsOpen = navigation.classList.toggle('is-open');

            menuButton.classList.toggle('is-open', menuIsOpen);
            menuButton.setAttribute('aria-expanded', String(menuIsOpen));

            closeLanguageDropdown();
        });

        navigation
            .querySelectorAll('.shark-navigation__link')
            .forEach(function (link) {
                link.addEventListener('click', closeMenu);
            });
    }

    if (languageButton && languageContainer) {
        languageButton.addEventListener('click', function (event) {
            event.stopPropagation();

            const dropdownIsOpen =
                languageContainer.classList.toggle('is-open');

            languageButton.setAttribute(
                'aria-expanded',
                String(dropdownIsOpen)
            );

            closeMenu();
        });
    }

    languageOptions.forEach(function (option) {
        option.addEventListener('click', function () {
            const selectedLanguage = option.dataset.language;
            const selectedCode = option.dataset.code;

            localStorage.setItem(
                'sharkSelectedLanguage',
                selectedLanguage
            );

            document.documentElement.lang = selectedLanguage;

            if (currentLanguage) {
                currentLanguage.textContent = selectedCode;
            }

            languageOptions.forEach(function (item) {
                item.classList.remove('is-selected');
            });

            option.classList.add('is-selected');

            closeLanguageDropdown();
        });
    });

    const savedLanguage =
        localStorage.getItem('sharkSelectedLanguage') || 'ka';

    const savedLanguageOption = document.querySelector(
        `.shark-language__option[data-language="${savedLanguage}"]`
    );

    if (savedLanguageOption) {
        savedLanguageOption.click();
    }

    document.addEventListener('click', function (event) {
        if (
            languageContainer &&
            !languageContainer.contains(event.target)
        ) {
            closeLanguageDropdown();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMenu();
            closeLanguageDropdown();
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 1050) {
            closeMenu();
        }
    });
});
