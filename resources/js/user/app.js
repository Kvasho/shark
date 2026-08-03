document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.getElementById('sharkMenuButton');
    const navigation = document.getElementById('sharkMainNavigation');

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

document.addEventListener('DOMContentLoaded', function () {
    const loader = document.getElementById('sharkPageLoader');

    if (!loader) {
        return;
    }

    let pageIsChanging = false;

    function hideLoader() {
        document.body.classList.remove('shark-page-loading');

        loader.classList.add('is-leaving');
        loader.classList.remove('is-visible');

        setTimeout(function () {
            loader.classList.remove('is-leaving');
        }, 950);
    }

    function showLoader(url) {
        if (pageIsChanging) {
            return;
        }

        pageIsChanging = true;

        document.body.classList.add('shark-page-loading');

        loader.classList.remove('is-leaving');

        window.requestAnimationFrame(function () {
            loader.classList.add('is-visible');
        });

        setTimeout(function () {
            window.location.href = url;
        }, 850);
    }

    document.body.classList.add('shark-page-loading');

    window.addEventListener('load', function () {
        setTimeout(hideLoader, 550);
    });

    document.addEventListener('click', function (event) {
        const link = event.target.closest('a');

        if (!link) {
            return;
        }

        const href = link.getAttribute('href');

        if (
            !href ||
            href.startsWith('#') ||
            href.startsWith('mailto:') ||
            href.startsWith('tel:') ||
            link.hasAttribute('download') ||
            link.target === '_blank' ||
            event.ctrlKey ||
            event.metaKey ||
            event.shiftKey ||
            event.altKey
        ) {
            return;
        }

        const destination = new URL(link.href, window.location.href);

        if (destination.origin !== window.location.origin) {
            return;
        }

        if (destination.href === window.location.href) {
            return;
        }

        event.preventDefault();

        showLoader(destination.href);
    });

    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            pageIsChanging = false;
            hideLoader();
        }
    });
});
