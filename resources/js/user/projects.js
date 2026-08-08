document.addEventListener('DOMContentLoaded', function () {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const revealItems = document.querySelectorAll('.projects-reveal');
    const progress = document.querySelector('.projects-progress span');

    if ('IntersectionObserver' in window && !reduceMotion) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: .12, rootMargin: '0px 0px -40px' });
        revealItems.forEach(function (item) { observer.observe(item); });
    } else {
        revealItems.forEach(function (item) { item.classList.add('is-visible'); });
    }

    function updateProgress() {
        if (!progress) return;
        const height = document.documentElement.scrollHeight - window.innerHeight;
        progress.style.transform = `scaleX(${height > 0 ? Math.min(window.scrollY / height, 1) : 0})`;
    }
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();

    const filterButtons = document.querySelectorAll('[data-filter]');
    const articles = document.querySelectorAll('.project-article[data-category]');
    filterButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            filterButtons.forEach(function (item) { item.classList.remove('is-active'); });
            button.classList.add('is-active');
            articles.forEach(function (article) {
                const show = button.dataset.filter === 'all' || article.dataset.category === button.dataset.filter;
                article.classList.toggle('is-filtered', !show);
            });
        });
    });

    const galleryButtons = Array.from(document.querySelectorAll('[data-gallery-image]'));
    const lightbox = document.querySelector('.project-lightbox');
    if (lightbox && galleryButtons.length) {
        const image = lightbox.querySelector('img');
        const counter = lightbox.querySelector('.project-lightbox__counter');
        let currentIndex = 0;

        function showImage(index) {
            currentIndex = (index + galleryButtons.length) % galleryButtons.length;
            image.src = galleryButtons[currentIndex].dataset.galleryImage;
            counter.textContent = `${String(currentIndex + 1).padStart(2, '0')} / ${String(galleryButtons.length).padStart(2, '0')}`;
        }
        function openLightbox(index) {
            showImage(index);
            lightbox.classList.add('is-open');
            lightbox.setAttribute('aria-hidden', 'false');
            document.body.classList.add('has-lightbox');
            lightbox.querySelector('.project-lightbox__close').focus();
        }
        function closeLightbox() {
            lightbox.classList.remove('is-open');
            lightbox.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('has-lightbox');
        }

        galleryButtons.forEach(function (button, index) {
            button.addEventListener('click', function () { openLightbox(index); });
        });
        lightbox.querySelector('.project-lightbox__close').addEventListener('click', closeLightbox);
        lightbox.querySelector('.project-lightbox__nav--prev').addEventListener('click', function () { showImage(currentIndex - 1); });
        lightbox.querySelector('.project-lightbox__nav--next').addEventListener('click', function () { showImage(currentIndex + 1); });
        lightbox.addEventListener('click', function (event) { if (event.target === lightbox) closeLightbox(); });
        document.addEventListener('keydown', function (event) {
            if (!lightbox.classList.contains('is-open')) return;
            if (event.key === 'Escape') closeLightbox();
            if (event.key === 'ArrowLeft') showImage(currentIndex - 1);
            if (event.key === 'ArrowRight') showImage(currentIndex + 1);
        });
    }
});
