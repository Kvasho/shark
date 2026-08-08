document.addEventListener('DOMContentLoaded', function () {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const reveals = document.querySelectorAll('.media-reveal');
    const progress = document.querySelector('.media-progress span');

    if ('IntersectionObserver' in window && !reduceMotion) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, { threshold: .12, rootMargin: '0px 0px -45px' });
        reveals.forEach(function (item) { observer.observe(item); });
    } else {
        reveals.forEach(function (item) { item.classList.add('is-visible'); });
    }

    function updateProgress() {
        if (!progress) return;
        const height = document.documentElement.scrollHeight - window.innerHeight;
        progress.style.transform = `scaleX(${height > 0 ? Math.min(window.scrollY / height, 1) : 0})`;
    }
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();

    document.querySelectorAll('.video-story video').forEach(function (video) {
        const story = video.closest('.video-story');
        story.addEventListener('mouseenter', function () { video.play().catch(function () {}); });
        story.addEventListener('mouseleave', function () { video.pause(); video.currentTime = 0; });
    });

    const photos = Array.from(document.querySelectorAll('[data-photo]'));
    const videos = Array.from(document.querySelectorAll('[data-video]'));
    const viewer = document.querySelector('.media-viewer');
    if (!viewer) return;

    const viewerImage = viewer.querySelector('img');
    const viewerVideo = viewer.querySelector('video');
    const counter = viewer.querySelector('.media-viewer__counter');
    let activeItems = [];
    let current = 0;
    let type = 'photo';

    function render(index) {
        current = (index + activeItems.length) % activeItems.length;
        viewerVideo.pause();
        viewerImage.classList.remove('is-active');
        viewerVideo.classList.remove('is-active');

        if (type === 'photo') {
            viewerImage.src = activeItems[current].dataset.photo;
            viewerImage.classList.add('is-active');
        } else {
            viewerVideo.src = activeItems[current].dataset.video;
            viewerVideo.classList.add('is-active');
            viewerVideo.play().catch(function () {});
        }
        counter.textContent = `${String(current + 1).padStart(2, '0')} / ${String(activeItems.length).padStart(2, '0')}`;
    }

    function open(items, itemType, index) {
        activeItems = items;
        type = itemType;
        render(index);
        viewer.classList.add('is-open');
        viewer.setAttribute('aria-hidden', 'false');
        document.body.classList.add('has-media-viewer');
        viewer.querySelector('.media-viewer__close').focus();
    }

    function close() {
        viewer.classList.remove('is-open');
        viewer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('has-media-viewer');
        viewerVideo.pause();
    }

    photos.forEach(function (item, index) { item.addEventListener('click', function () { open(photos, 'photo', index); }); });
    videos.forEach(function (item, index) { item.addEventListener('click', function () { open(videos, 'video', index); }); });
    viewer.querySelector('.media-viewer__close').addEventListener('click', close);
    viewer.querySelector('.media-viewer__nav--prev').addEventListener('click', function () { render(current - 1); });
    viewer.querySelector('.media-viewer__nav--next').addEventListener('click', function () { render(current + 1); });
    viewer.addEventListener('click', function (event) { if (event.target === viewer) close(); });
    document.addEventListener('keydown', function (event) {
        if (!viewer.classList.contains('is-open')) return;
        if (event.key === 'Escape') close();
        if (event.key === 'ArrowLeft') render(current - 1);
        if (event.key === 'ArrowRight') render(current + 1);
    });
});
