// მობილურზე sidebar-ის გახსნა/დახურვა.
const body = document.body;

const setSidebar = (open) => {
    body.classList.toggle('sidebar-open', open);
    document.querySelector('[data-sidebar-open]')?.setAttribute('aria-expanded', String(open));
};

document.querySelectorAll('[data-sidebar-open]').forEach((button) => {
    button.addEventListener('click', () => setSidebar(true));
});

document.querySelectorAll('[data-sidebar-close]').forEach((element) => {
    element.addEventListener('click', () => setSidebar(false));
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && body.classList.contains('sidebar-open')) {
        setSidebar(false);
    }
});

// ასატვირთი სურათის წინასწარი ნახვა.
document.querySelectorAll('[data-photo-input]').forEach((input) => {
    const upload = input.closest('.admin-upload');
    const preview = upload?.querySelector('[data-photo-preview]');
    const caption = upload?.querySelector('.admin-upload__text small');

    input.addEventListener('change', () => {
        const file = input.files?.[0];

        if (!file || !preview) {
            return;
        }

        preview.src = URL.createObjectURL(file);
        preview.hidden = false;

        if (caption) {
            caption.textContent = file.name;
        }
    });
});

// რამდენიმე სურათის ერთად არჩევა: არჩეულების მინიატურები.
document.querySelectorAll('[data-photos-input]').forEach((input) => {
    const form = input.closest('form');
    const preview = form?.querySelector('[data-photos-preview]');
    const caption = form?.querySelector('[data-photos-caption]');
    const defaultCaption = caption?.textContent;

    input.addEventListener('change', () => {
        const files = Array.from(input.files || []);

        if (!preview) {
            return;
        }

        preview.querySelectorAll('img, video').forEach((media) => URL.revokeObjectURL(media.src));
        preview.replaceChildren(...files.map((file) => {
            const item = document.createElement('li');
            const isVideo = file.type.startsWith('video/');
            const media = document.createElement(isVideo ? 'video' : 'img');
            media.src = URL.createObjectURL(file);

            if (isVideo) {
                media.muted = true;
                media.preload = 'metadata';
                const badge = document.createElement('span');
                badge.className = 'admin-gallery__badge';
                badge.textContent = '▶';
                item.append(media, badge);
            } else {
                media.alt = '';
                item.append(media);
            }

            return item;
        }));
        preview.hidden = files.length === 0;

        if (caption) {
            caption.textContent = files.length ? `არჩეულია ${files.length} ფაილი` : defaultCaption;
        }
    });
});

// ერთი ფაილის (ვიდეოს) არჩევისას — ფაილის სახელი წარწერაში.
document.querySelectorAll('[data-file-input]').forEach((input) => {
    const caption = input.closest('label')?.querySelector('[data-file-caption]');
    const defaultCaption = caption?.textContent;

    input.addEventListener('change', () => {
        const file = input.files?.[0];

        if (caption) {
            caption.textContent = file ? `${file.name} · ${(file.size / 1048576).toFixed(1)}MB` : defaultCaption;
        }
    });
});

// რამდენიმე ელემენტის მონიშვნა და ერთად წაშლა (მაგ. მედიის ფოტოები).
document.querySelectorAll('[data-selection-form]').forEach((form) => {
    const items = form.querySelectorAll('[data-selection-item]');
    const selectAll = form.querySelector('[data-select-all]');
    const submit = form.querySelector('[data-selection-submit]');
    const count = form.querySelector('[data-selection-count]');

    const update = () => {
        const selected = Array.from(items).filter((item) => item.checked).length;

        submit.disabled = selected === 0;
        count.textContent = selected ? `(${selected})` : '';

        if (selectAll) {
            selectAll.checked = selected > 0 && selected === items.length;
            selectAll.indeterminate = selected > 0 && selected < items.length;
        }
    };

    items.forEach((item) => item.addEventListener('change', update));
    selectAll?.addEventListener('change', () => {
        items.forEach((item) => { item.checked = selectAll.checked; });
        update();
    });
    update();
});

// დიდი ფაილების (ვიდეო) ატვირთვას დრო სჭირდება — ღილაკი ითიშება, რომ ორჯერ არ გაიგზავნოს.
document.querySelectorAll('[data-submit-label]').forEach((button) => {
    button.form?.addEventListener('submit', () => {
        button.disabled = true;
        button.textContent = button.dataset.submitLabel;
    });
});

// წაშლის დადასტურება ფორმებზე, რომლებსაც data-confirm ატრიბუტი აქვთ.
const confirmForms = document.querySelectorAll('form[data-confirm]');

if (confirmForms.length) {
    const dialog = document.createElement('dialog');
    dialog.className = 'admin-dialog';
    dialog.innerHTML = `
        <p data-confirm-text></p>
        <div class="admin-dialog__actions">
            <button type="button" class="admin-btn admin-btn--ghost" data-confirm-cancel>გაუქმება</button>
            <button type="button" class="admin-btn admin-btn--danger" data-confirm-ok>წაშლა</button>
        </div>
    `;
    body.append(dialog);

    let pendingForm = null;

    confirmForms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            pendingForm = form;
            dialog.querySelector('[data-confirm-text]').textContent = form.dataset.confirm;
            dialog.showModal();
        });
    });

    dialog.querySelector('[data-confirm-cancel]').addEventListener('click', () => {
        pendingForm = null;
        dialog.close();
    });

    dialog.querySelector('[data-confirm-ok]').addEventListener('click', () => {
        dialog.close();
        pendingForm?.submit();
    });
}
