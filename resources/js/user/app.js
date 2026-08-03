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
