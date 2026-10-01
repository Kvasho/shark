{{-- დროებითი ბლოკი გვერდებისთვის, რომელთა მართვაც ჯერ არ არის აწყობილი. --}}
<section class="admin-card admin-empty">
    <div class="admin-empty__icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
    </div>
    <h2 class="admin-card__title">{{ $title }}</h2>
    <p class="admin-card__text">{{ $text ?? 'ამ სექციის მართვა მალე დაემატება.' }}</p>
</section>
