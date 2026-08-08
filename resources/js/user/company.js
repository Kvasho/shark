document.addEventListener('DOMContentLoaded', function () {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const revealItems = document.querySelectorAll(
        '.company-reveal, .company-image-reveal'
    );
    const progressBar = document.querySelector('.company-scroll-progress span');

    function animateCounter(element) {
        if (element.dataset.animated === 'true') {
            return;
        }

        element.dataset.animated = 'true';
        const target = Number(element.dataset.counter);
        const suffix = element.dataset.suffix || '';
        const duration = 1500;
        const startedAt = performance.now();

        function tick(now) {
            const progress = Math.min((now - startedAt) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            element.textContent = `${Math.round(target * eased)}${suffix}`;

            if (progress < 1) {
                requestAnimationFrame(tick);
            }
        }

        requestAnimationFrame(tick);
    }

    if ('IntersectionObserver' in window && !reduceMotion) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                const counter = entry.target.querySelector('[data-counter]');

                if (counter) {
                    animateCounter(counter);
                }

                observer.unobserve(entry.target);
            });
        }, { threshold: 0.16, rootMargin: '0px 0px -50px' });

        revealItems.forEach(function (item) {
            observer.observe(item);
        });
    } else {
        revealItems.forEach(function (item) {
            item.classList.add('is-visible');
        });
        document.querySelectorAll('[data-counter]').forEach(function (counter) {
            counter.textContent = `${counter.dataset.counter}${counter.dataset.suffix || ''}`;
        });
    }

    const parallaxItems = document.querySelectorAll('[data-company-parallax]');
    let frame = null;

    function renderParallax() {
        parallaxItems.forEach(function (item) {
            const rect = item.getBoundingClientRect();

            if (rect.bottom < 0 || rect.top > window.innerHeight) {
                return;
            }

            const speed = Number(item.dataset.companyParallax) || 0;
            const offset = (window.innerHeight / 2 - rect.top - rect.height / 2) * speed;
            item.style.transform = `translate3d(0, ${offset}px, 0)`;
        });
        frame = null;
    }

    function updateScrollProgress() {
        if (!progressBar) {
            return;
        }

        const scrollableHeight =
            document.documentElement.scrollHeight - window.innerHeight;
        const progress = scrollableHeight > 0
            ? Math.min(window.scrollY / scrollableHeight, 1)
            : 0;

        progressBar.style.transform = `scaleX(${progress})`;
    }

    if (!reduceMotion && window.innerWidth > 767) {
        window.addEventListener('scroll', function () {
            if (frame === null) {
                frame = requestAnimationFrame(renderParallax);
            }
        }, { passive: true });
        renderParallax();
    }

    window.addEventListener('scroll', updateScrollProgress, { passive: true });
    updateScrollProgress();
});
