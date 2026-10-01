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

    initTabs();
    initViewer();
});

/*
 * ფოტოები / ვიდეოები გადამრთველი. არჩეული ჩანართი მისამართშიც აისახება (#videos),
 * რომ ბმულით პირდაპირ ვიდეოებზე გადასვლა შეიძლებოდეს.
 */
function initTabs() {
    const tabs = Array.from(document.querySelectorAll('[data-media-tab]'));
    const panels = document.querySelectorAll('[data-media-panel]');

    if (!tabs.length) return;

    function select(name, updateHash) {
        tabs.forEach(function (tab) {
            const active = tab.dataset.mediaTab === name;
            tab.classList.toggle('is-active', active);
            tab.setAttribute('aria-selected', String(active));
            tab.tabIndex = active ? 0 : -1;
        });
        panels.forEach(function (panel) {
            panel.hidden = panel.dataset.mediaPanel !== name;
        });

        if (updateHash) {
            history.replaceState(null, '', name === 'videos' ? '#videos' : location.pathname + location.search);
        }
    }

    tabs.forEach(function (tab, index) {
        tab.addEventListener('click', function () { select(tab.dataset.mediaTab, true); });

        // ისრებით გადართვა ჩანართებს შორის (ხელმისაწვდომობა).
        tab.addEventListener('keydown', function (event) {
            if (event.key !== 'ArrowRight' && event.key !== 'ArrowLeft') return;
            const next = tabs[(index + (event.key === 'ArrowRight' ? 1 : -1) + tabs.length) % tabs.length];
            next.focus();
            select(next.dataset.mediaTab, true);
        });
    });

    if (location.hash === '#videos') {
        select('videos', false);
    }
}

/*
 * გადიდებული ნახვა: ფოტოები და ვიდეოები ცალ-ცალკე სიად — ისრებით გადასვლა
 * მხოლოდ იმავე ტიპის ელემენტებს შორის ხდება.
 */
function initViewer() {
    const viewer = document.querySelector('.media-viewer');
    if (!viewer) return;

    const photos = Array.from(document.querySelectorAll('[data-photo]'));
    const videos = Array.from(document.querySelectorAll('[data-video]'));
    const image = viewer.querySelector('.media-viewer__stage img');
    const video = viewer.querySelector('.media-viewer__stage video');
    const title = viewer.querySelector('.media-viewer__title');
    const counter = viewer.querySelector('.media-viewer__counter');
    const navButtons = viewer.querySelectorAll('.media-viewer__nav');
    const closeButton = viewer.querySelector('.media-viewer__close');
    let items = [];
    let type = 'photo';
    let current = 0;
    let opener = null;

    function language() {
        return window.SharkI18n?.getLanguage() || 'ka';
    }

    function videoTitle(item) {
        try {
            const values = JSON.parse(item.dataset.videoTitle || '{}');
            return values[language()] || values.ka || '';
        } catch {
            return '';
        }
    }

    function stopVideo() {
        video.pause();
        video.removeAttribute('src');
        video.load();
    }

    function render(index) {
        current = (index + items.length) % items.length;
        const item = items[current];

        stopVideo();
        image.classList.toggle('is-active', type === 'photo');
        video.classList.toggle('is-active', type === 'video');

        if (type === 'photo') {
            image.src = item.dataset.photo;
            image.alt = '';
            title.textContent = '';
        } else {
            image.removeAttribute('src');
            video.src = item.dataset.video;
            title.textContent = videoTitle(item);
            video.play().catch(function () { /* ავტომატური ჩართვა შეიძლება დაბლოკილი იყოს — controls ხელმისაწვდომია */ });
        }

        counter.textContent = `${String(current + 1).padStart(2, '0')} / ${String(items.length).padStart(2, '0')}`;
    }

    function open(list, listType, index, trigger) {
        items = list;
        type = listType;
        opener = trigger;
        navButtons.forEach(function (button) { button.classList.toggle('is-hidden', items.length < 2); });
        render(index);
        viewer.classList.add('is-open');
        viewer.setAttribute('aria-hidden', 'false');
        document.body.classList.add('has-media-viewer');
        closeButton.focus();
    }

    function close() {
        stopVideo();
        viewer.classList.remove('is-open');
        viewer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('has-media-viewer');
        opener?.focus();
    }

    photos.forEach(function (item, index) {
        item.addEventListener('click', function () { open(photos, 'photo', index, item); });
    });
    videos.forEach(function (item, index) {
        item.addEventListener('click', function () { open(videos, 'video', index, item); });
    });

    closeButton.addEventListener('click', close);
    viewer.querySelector('.media-viewer__nav--prev').addEventListener('click', function () { render(current - 1); });
    viewer.querySelector('.media-viewer__nav--next').addEventListener('click', function () { render(current + 1); });
    viewer.addEventListener('click', function (event) {
        if (event.target === viewer || event.target.classList.contains('media-viewer__stage')) close();
    });

    // თითით გადასმა მობილურზე.
    let touchStartX = null;
    viewer.addEventListener('touchstart', function (event) { touchStartX = event.touches[0].clientX; }, { passive: true });
    viewer.addEventListener('touchend', function (event) {
        if (touchStartX === null || items.length < 2) return;
        const distance = event.changedTouches[0].clientX - touchStartX;
        touchStartX = null;
        if (Math.abs(distance) > 50 && event.target !== video) render(current + (distance < 0 ? 1 : -1));
    });

    document.addEventListener('keydown', function (event) {
        if (!viewer.classList.contains('is-open')) return;
        if (event.key === 'Escape') close();
        // ვიდეოზე ფოკუსისას ისრები ვიდეოს გადახვევას ემსახურება.
        if (event.target === video || items.length < 2) return;
        if (event.key === 'ArrowLeft') render(current - 1);
        if (event.key === 'ArrowRight') render(current + 1);
    });

    // ენის შეცვლისას ღია ვიდეოს სათაურიც იცვლება.
    document.addEventListener('shark:language-change', function () {
        if (viewer.classList.contains('is-open') && type === 'video') {
            title.textContent = videoTitle(items[current]);
        }
    });
}
