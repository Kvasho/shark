document.addEventListener('DOMContentLoaded', function () {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const animatedItems = document.querySelectorAll('.services-animate');
    const progress = document.querySelector('.services-progress span');
    const timeline = document.querySelector('.services-timeline');
    const timelineFill = document.querySelector('.services-timeline__track i');

    if ('IntersectionObserver' in window && !reduceMotion) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.14, rootMargin: '0px 0px -45px' });

        animatedItems.forEach(function (item) { observer.observe(item); });
    } else {
        animatedItems.forEach(function (item) { item.classList.add('is-visible'); });
    }

    const parallaxItems = document.querySelectorAll('[data-services-parallax]');
    let ticking = false;

    function renderScrollEffects() {
        const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
        const pageProgress = maxScroll > 0 ? Math.min(window.scrollY / maxScroll, 1) : 0;

        if (progress) {
            progress.style.transform = `scaleX(${pageProgress})`;
        }

        if (timeline && timelineFill) {
            const rect = timeline.getBoundingClientRect();
            const timelineProgress = Math.min(Math.max((window.innerHeight * .62 - rect.top) / rect.height, 0), 1);
            timelineFill.style.transform = `scaleY(${timelineProgress})`;
        }

        if (!reduceMotion && window.innerWidth > 767) {
            parallaxItems.forEach(function (item) {
                const rect = item.getBoundingClientRect();
                const speed = Number(item.dataset.servicesParallax) || 0;
                const offset = (window.innerHeight / 2 - rect.top - rect.height / 2) * speed;
                item.style.transform = `translate3d(0, ${offset}px, 0)`;
            });
        }

        ticking = false;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(renderScrollEffects);
        }
    }, { passive: true });

    renderScrollEffects();

    document.querySelectorAll('[data-step-slider]').forEach(initStepSlider);
});

/*
 * ნაბიჯის სურათების სლაიდერი. გადაფურცვლა ბუნებრივი scroll-snap-ით ხდება
 * (მობილურზე თითით), ისრები და წერტილები უბრალოდ სწორ სლაიდზე გადაახვევენ.
 */
function initStepSlider(slider) {
    const track = slider.querySelector('[data-slider-track]');
    const slides = track.children;
    const prev = slider.querySelector('[data-slider-prev]');
    const next = slider.querySelector('[data-slider-next]');
    const dots = slider.querySelectorAll('[data-slider-dot]');

    function currentIndex() {
        return Math.round(track.scrollLeft / track.clientWidth);
    }

    function goTo(index) {
        const target = Math.max(0, Math.min(index, slides.length - 1));
        track.scrollTo({ left: target * track.clientWidth });
    }

    function update() {
        const index = currentIndex();

        dots.forEach(function (dot, i) {
            dot.classList.toggle('is-active', i === index);
            dot.setAttribute('aria-current', i === index ? 'true' : 'false');
        });
        prev.disabled = index === 0;
        next.disabled = index === slides.length - 1;
    }

    prev.addEventListener('click', function () { goTo(currentIndex() - 1); });
    next.addEventListener('click', function () { goTo(currentIndex() + 1); });
    dots.forEach(function (dot) {
        dot.addEventListener('click', function () { goTo(Number(dot.dataset.sliderDot)); });
    });

    let frame = null;
    track.addEventListener('scroll', function () {
        if (frame === null) {
            frame = requestAnimationFrame(function () {
                update();
                frame = null;
            });
        }
    }, { passive: true });

    update();
}
