document.addEventListener('DOMContentLoaded', function () {
    const hero = document.getElementById('sharkHero');
    const heroVideo = document.getElementById('sharkHeroVideo');
    const soundButton = document.getElementById('sharkSoundButton');
    const scrollIndicator = document.querySelector(
        '.shark-scroll-indicator'
    );

    function startHeroAnimation() {
        if (!hero) {
            return;
        }

        window.requestAnimationFrame(function () {
            hero.classList.add('is-loaded');
        });
    }

    function playHeroVideo() {
        if (!heroVideo) {
            startHeroAnimation();
            return;
        }

        heroVideo.muted = true;
        heroVideo.loop = true;
        heroVideo.playsInline = true;

        const playPromise = heroVideo.play();

        if (playPromise !== undefined) {
            playPromise
                .then(function () {
                    hero.classList.add('has-video');
                })
                .catch(function () {
                    hero.classList.add('video-blocked');
                })
                .finally(function () {
                    startHeroAnimation();
                });
        } else {
            startHeroAnimation();
        }
    }

    function updateSoundButton() {
        if (!heroVideo || !soundButton) {
            return;
        }

        const soundIsOn = !heroVideo.muted;
        const icon = soundButton.querySelector('i');
        const label = soundButton.querySelector('span');

        soundButton.setAttribute('aria-pressed', String(soundIsOn));
        soundButton.setAttribute(
            'aria-label',
            window.SharkI18n.translate(
                soundIsOn ? 'ხმის გამორთვა' : 'ხმის ჩართვა'
            )
        );

        if (icon) {
            icon.className = soundIsOn
                ? 'fa-solid fa-volume-high'
                : 'fa-solid fa-volume-xmark';
        }

        if (label) {
            label.textContent = window.SharkI18n.translate(
                soundIsOn ? 'ხმის გამორთვა' : 'ხმის ჩართვა'
            );
        }
    }

    function enableVideoSound() {
        if (!heroVideo) {
            return;
        }

        heroVideo.muted = false;
        heroVideo.volume = 1;
        heroVideo.play().catch(function () {});
        updateSoundButton();
    }

    if (heroVideo) {
        if (heroVideo.readyState >= 3) {
            playHeroVideo();
        } else {
            heroVideo.addEventListener(
                'canplay',
                playHeroVideo,
                { once: true }
            );

            setTimeout(startHeroAnimation, 1000);
        }

        heroVideo.addEventListener('ended', function () {
            heroVideo.currentTime = 0;
            heroVideo.play().catch(function () {});
        });

        updateSoundButton();
    } else {
        startHeroAnimation();
    }

    if (soundButton && heroVideo) {
        soundButton.addEventListener('click', function () {
            heroVideo.muted = !heroVideo.muted;
            heroVideo.volume = 1;
            heroVideo.play().catch(function () {});
            updateSoundButton();
        });

        document.addEventListener('pointerdown', enableVideoSound, {
            once: true
        });
    }

    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', function (event) {
            const targetId = scrollIndicator.getAttribute('href');

            if (!targetId || !targetId.startsWith('#')) {
                return;
            }

            const targetSection = document.querySelector(targetId);

            if (!targetSection) {
                return;
            }

            event.preventDefault();

            targetSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    }

    window.addEventListener(
        'scroll',
        function () {
            if (!hero || !scrollIndicator) {
                return;
            }

            const scrollPosition = window.scrollY;
            const heroHeight = hero.offsetHeight;

            if (scrollPosition > heroHeight * 0.15) {
                scrollIndicator.classList.add('is-hidden');
            } else {
                scrollIndicator.classList.remove('is-hidden');
            }

        },
        { passive: true }
    );

    const revealElements = document.querySelectorAll('.reveal');
    const reduceMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    ).matches;

    if ('IntersectionObserver' in window && !reduceMotion) {
        const revealObserver = new IntersectionObserver(
            function (entries, observer) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            },
            { threshold: 0.14, rootMargin: '0px 0px -45px' }
        );

        revealElements.forEach(function (element) {
            revealObserver.observe(element);
        });
    } else {
        revealElements.forEach(function (element) {
            element.classList.add('is-visible');
        });
    }

    const parallaxElements = document.querySelectorAll('[data-parallax]');
    let parallaxFrame = null;

    function updateParallax() {
        parallaxElements.forEach(function (element) {
            const rect = element.getBoundingClientRect();

            if (rect.bottom < 0 || rect.top > window.innerHeight) {
                return;
            }

            const speed = Number(element.dataset.parallax) || 0;
            const offset = (window.innerHeight / 2 - rect.top - rect.height / 2) * speed;

            element.style.setProperty('--parallax-y', `${offset}px`);
        });

        parallaxFrame = null;
    }

    if (!reduceMotion && window.innerWidth > 767) {
        window.addEventListener(
            'scroll',
            function () {
                if (parallaxFrame === null) {
                    parallaxFrame = window.requestAnimationFrame(updateParallax);
                }
            },
            { passive: true }
        );

        updateParallax();
    }

    document.addEventListener('visibilitychange', function () {
        if (!heroVideo) {
            return;
        }

        if (document.hidden) {
            heroVideo.pause();
        } else {
            heroVideo.play().catch(function () {});
        }
    });
});
