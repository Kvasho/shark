document.addEventListener('DOMContentLoaded', function () {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const reveals = document.querySelectorAll('.contact-reveal');
    const progress = document.querySelector('.contact-progress span');
    const modal = document.querySelector('.contact-modal');
    const form = document.getElementById('contactForm');

    if ('IntersectionObserver' in window && !reduceMotion) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, { threshold: .13, rootMargin: '0px 0px -45px' });
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

    if (!modal || !form) return;
    const firstInput = form.querySelector('input[name="name"]');
    const submitButton = form.querySelector('.contact-form__submit');
    const submitLabel = submitButton.querySelector('span');
    const status = form.querySelector('.contact-form__status');

    function openModal() {
        modal.classList.remove('is-success');
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('has-contact-modal');
        setTimeout(function () { firstInput.focus(); }, 350);
    }

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('has-contact-modal');
    }

    document.querySelectorAll('[data-open-contact]').forEach(function (button) {
        button.addEventListener('click', openModal);
    });
    document.querySelectorAll('[data-close-contact]').forEach(function (button) {
        button.addEventListener('click', closeModal);
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
    });

    function clearErrors() {
        form.querySelectorAll('[data-error]').forEach(function (item) { item.textContent = ''; });
        status.textContent = '';
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        clearErrors();
        submitButton.disabled = true;
        submitLabel.textContent = 'იგზავნება...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await response.json();

            if (!response.ok) {
                if (result.errors) {
                    Object.entries(result.errors).forEach(function ([field, messages]) {
                        const error = form.querySelector(`[data-error="${field}"]`);
                        if (error) error.textContent = messages[0];
                    });
                } else {
                    status.textContent = result.message || 'დაფიქსირდა შეცდომა. სცადეთ ხელახლა.';
                }
                return;
            }

            form.reset();
            modal.classList.add('is-success');
        } catch (error) {
            status.textContent = 'ინფორმაციის გაგზავნა ვერ მოხერხდა. შეამოწმეთ კავშირი და სცადეთ ხელახლა.';
        } finally {
            submitButton.disabled = false;
            submitLabel.textContent = 'ინფორმაციის გაგზავნა';
        }
    });
});
