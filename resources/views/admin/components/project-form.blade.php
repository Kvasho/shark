{{--
    პროექტის დამატების/რედაქტირების ფორმა.
    პარამეტრები: $action (URL), $project (Project|null), $submitLabel
--}}
@php
    $project ??= null;
    $old = fn ($key, $default = null) => old($key, $default);
    $removed = array_map('intval', (array) old('remove_media', []));
    $mediaError = $errors->first('media') ?: $errors->first('media.*');

    // ველი => [სათაური, სავალდებულოა?, maxlength, textarea?]
    $fields = [
        'title' => ['სათაური', true, 200, false],
        'category' => ['ტიპი', true, 100, false],
        'location' => ['ლოკაცია', false, 150, false],
        'excerpt' => ['მოკლე აღწერა', false, 500, true],
        'description' => ['სრული აღწერა', false, 5000, true],
    ];
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="admin-form admin-form--compact" novalidate>
    @csrf
    @if ($project)
        @method('PUT')
    @endif

    @include('admin.components.i18n-fields', ['model' => $project])
    <p class="admin-form__hint admin-form__hint--block">
        „ტიპი“ — მაგ: საცხოვრებელი, კომერციული. საიტზე პროექტები ამ ტიპით იფილტრება, ამიტომ ერთნაირ ტიპს ერთნაირად წერეთ.
        „მოკლე აღწერა“ ჩანს პროექტების სიაში, „სრული აღწერა“ — პროექტის გვერდზე.
    </p>

    <div class="admin-form__bar admin-form__bar--plain">
        <label class="admin-form__field admin-form__field--small">
            <span class="admin-form__label">წელი</span>
            <input
                type="number"
                name="year"
                min="1900"
                max="2100"
                placeholder="{{ date('Y') }}"
                value="{{ $old('year', $project?->year) }}"
                @class(['admin-form__input', 'is-invalid' => $errors->has('year')])
            >
        </label>

        <label class="admin-form__field admin-form__field--small">
            <span class="admin-form__label">ფართობი, მ²</span>
            <input
                type="number"
                name="area"
                min="1"
                placeholder="18400"
                value="{{ $old('area', $project?->area) }}"
                @class(['admin-form__input', 'is-invalid' => $errors->has('area')])
            >
        </label>

        <div class="admin-form__field admin-form__field--grow">
            <span class="admin-form__label">
                მთავარი სურათი
                @unless ($project) <em class="admin-form__required">*</em> @endunless
                <small class="admin-form__optional">— სიაში და გვერდის თავში</small>
            </span>
            <label @class(['admin-upload', 'admin-upload--compact', 'is-invalid' => $errors->has('cover')])>
                <img
                    src="{{ $project?->coverUrl() }}"
                    alt=""
                    class="admin-upload__preview"
                    data-photo-preview
                    @unless ($project) hidden @endunless
                >
                <span class="admin-upload__text">
                    <strong>{{ $project ? 'შეცვლა' : 'არჩევა' }}</strong>
                    <small>JPG, PNG, WEBP · 8MB</small>
                </span>
                <input type="file" name="cover" accept="image/jpeg,image/png,image/webp" data-photo-input>
            </label>
        </div>
    </div>

    @foreach (['year', 'area', 'cover'] as $field)
        @error($field)
            <span class="admin-form__error">{{ $message }}</span>
        @enderror
    @endforeach

    <div class="admin-form__field">
        <span class="admin-form__label">
            გალერეა — სურათები და ვიდეოები
            <small class="admin-form__optional">(არასავალდ.)</small>
        </span>

        @if ($project && $project->media->isNotEmpty())
            <ul class="admin-gallery">
                @foreach ($project->media as $item)
                    <li>
                        <label class="admin-gallery__item">
                            @if ($item->isVideo())
                                <video src="{{ $item->url() }}#t=0.5" muted preload="metadata"></video>
                                <span class="admin-gallery__badge" aria-hidden="true">▶</span>
                            @else
                                <img src="{{ $item->url() }}" alt="" loading="lazy">
                            @endif
                            <input type="checkbox" name="remove_media[]" value="{{ $item->id }}" @checked(in_array($item->id, $removed, true))>
                            <span class="admin-gallery__remove">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16"/><path d="M5 7l1 13h12l1-13"/><path d="M9 7V4h6v3"/></svg>
                                წაშლა
                            </span>
                        </label>
                    </li>
                @endforeach
            </ul>
        @endif

        <label @class(['admin-upload', 'admin-upload--compact', 'is-invalid' => $mediaError])>
            <span class="admin-upload__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="m10 9 5 3-5 3z"/></svg>
            </span>
            <span class="admin-upload__text">
                <strong>{{ $project ? 'ფაილების დამატება' : 'ფაილების არჩევა' }}</strong>
                <small data-photos-caption>რამდენიმე ერთად · სურათი: JPG, PNG, WEBP (8MB) · ვიდეო: MP4, WEBM, MOV (100MB) · ერთ ჯერზე მაქს. {{ \App\Http\Requests\Admin\ProjectRequest::MAX_UPLOAD }}</small>
            </span>
            <input
                type="file"
                name="media[]"
                accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime"
                multiple
                data-photos-input
            >
        </label>
        <ul class="admin-gallery admin-gallery--new" data-photos-preview hidden></ul>

        @if ($mediaError)
            <span class="admin-form__error">{{ $mediaError }}</span>
        @endif
    </div>

    <div class="admin-form__bar">
        <label class="admin-form__field admin-form__field--order" title="ნაკლები რიცხვი — უფრო წინ გამოჩნდება">
            <span class="admin-form__label">რიგი</span>
            <input
                type="number"
                name="sort_order"
                min="0"
                max="9999"
                value="{{ $old('sort_order', $project?->sort_order ?? 0) }}"
                @class(['admin-form__input', 'is-invalid' => $errors->has('sort_order')])
            >
        </label>

        <label class="admin-form__check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked($old('is_active', $project?->is_active ?? true))>
            <span>საიტზე ჩანს</span>
        </label>

        <div class="admin-form__actions">
            @if ($project)
                <a href="{{ route('admin.projects') }}" class="admin-btn admin-btn--ghost">გაუქმება</a>
            @endif
            <button type="submit" class="admin-btn" data-submit-label="იტვირთება…">{{ $submitLabel }}</button>
        </div>
    </div>

    @error('sort_order')
        <span class="admin-form__error">{{ $message }}</span>
    @enderror
</form>
