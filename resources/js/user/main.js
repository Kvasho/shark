document.addEventListener('DOMContentLoaded', function () {
    const hero = document.getElementById('sharkHero');
    const heroVideo = document.getElementById('sharkHeroVideo');
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
    } else {
        startHeroAnimation();
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

            if (heroVideo && scrollPosition < heroHeight) {
                const movement = Math.min(scrollPosition * 0.12, 60);

                heroVideo.style.transform =
                    `translate(-50%, calc(-50% + ${movement}px)) scale(1.08)`;
            }
        },
        { passive: true }
    );

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
