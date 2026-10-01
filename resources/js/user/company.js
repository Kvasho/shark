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

    initTeam();
});

/*
 * გუნდის სექცია: სლაიდერი, დეტალური მოდალი და ბაზიდან მოსული ტექსტის ენა.
 * ბაზის ტექსტი i18n ლექსიკონით არ ითარგმნება (data-i18n-skip) — სამივე ენის
 * მნიშვნელობა HTML-შივეა და აქ ვირჩევთ ჰედერში არჩეული ენის მიხედვით.
 */
function initTeam() {
    const track = document.querySelector('[data-team-track]');

    if (!track) {
        return;
    }

    const prevButton = document.querySelector('[data-team-prev]');
    const nextButton = document.querySelector('[data-team-next]');
    const modal = document.querySelector('[data-member-modal]');
    let openMember = null;

    function currentLanguage() {
        return window.SharkI18n?.getLanguage() || localStorage.getItem('sharkSelectedLanguage') || 'ka';
    }

    function pick(values, language) {
        return (values && (values[language] || values.ka)) || '';
    }

    // --- ენა: ბარათების ტექსტს i18n.js ცვლის (data-i18n-values), აქ მხოლოდ ღია მოდალს ვაახლებთ ---

    document.addEventListener('shark:language-change', function (event) {
        if (openMember) {
            fillModal(openMember, event.detail.language);
        }
    });

    // --- სლაიდერი ---

    function step() {
        const card = track.querySelector('.company-member');
        const gap = parseFloat(getComputedStyle(track).columnGap) || 0;

        return card ? card.getBoundingClientRect().width + gap : track.clientWidth;
    }

    function updateButtons() {
        const maxScroll = track.scrollWidth - track.clientWidth - 2;
        const hasOverflow = maxScroll > 0;

        if (prevButton && nextButton) {
            // ყველა თანამშრომელი ეკრანზე ეტევა — ისრები არ გვჭირდება.
            prevButton.parentElement.classList.toggle('is-hidden', !hasOverflow);
            prevButton.disabled = track.scrollLeft <= 2;
            nextButton.disabled = track.scrollLeft >= maxScroll;
        }
    }

    prevButton?.addEventListener('click', function () {
        track.scrollBy({ left: -step(), behavior: 'smooth' });
    });
    nextButton?.addEventListener('click', function () {
        track.scrollBy({ left: step(), behavior: 'smooth' });
    });
    track.addEventListener('scroll', updateButtons, { passive: true });
    window.addEventListener('resize', updateButtons);
    updateButtons();

    // --- მოდალი ---

    if (!modal) {
        return;
    }

    const modalPhoto = modal.querySelector('[data-member-photo]');
    const modalName = modal.querySelector('[data-member-name]');
    const modalPosition = modal.querySelector('[data-member-position]');
    const modalBio = modal.querySelector('[data-member-bio]');

    function fillModal(member, language) {
        modalPhoto.src = member.photo;
        modalPhoto.alt = pick(member.name, language);
        modalName.textContent = pick(member.name, language);
        modalPosition.textContent = pick(member.position, language);
        modalBio.textContent = pick(member.bio, language);
    }

    function closeModal() {
        if (modal.open) {
            modal.close();
        }
    }

    track.addEventListener('click', function (event) {
        const button = event.target.closest('[data-member]');

        if (!button) {
            return;
        }

        openMember = JSON.parse(button.dataset.member);
        fillModal(openMember, currentLanguage());
        modal.showModal();
        document.documentElement.classList.add('company-modal-open');
        modal.querySelector('.company-member-modal__content').scrollTop = 0;
    });

    modal.querySelector('[data-member-close]').addEventListener('click', closeModal);

    // ფონზე დაჭერით დახურვა (dialog-ის შიგთავსი მთელ ფართობს ფარავს, ამიტომ target = dialog მხოლოდ ფონზე).
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    modal.addEventListener('close', function () {
        openMember = null;
        document.documentElement.classList.remove('company-modal-open');
    });
}
