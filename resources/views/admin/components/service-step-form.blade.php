{{--
    "როგორ ვმუშაობთ" ნაბიჯის დამატების/რედაქტირების ფორმა.
    პარამეტრები: $action (URL), $step (ServiceStep|null), $submitLabel
--}}
@php
    $step ??= null;
    $old = fn ($key, $default = null) => old($key, $default);
    $removed = array_map('intval', (array) old('remove_images', []));
    $imagesError = $errors->first('images') ?: $errors->first('images.*');

    // ველი => [სათაური, სავალდებულოა?, maxlength, textarea?]
    $fields = [
        'title' => ['სათაური', true, 200, false],
        'description' => ['აღწერა', false, 3000, true],
    ];
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="admin-form admin-form--compact" novalidate>
    @csrf
    @if ($step)
        @method('PUT')
    @endif

    @include('admin.components.i18n-fields', ['model' => $step])

    <div class="admin-form__field">
        <span class="admin-form__label">
            სურათები
            @unless ($step) <em class="admin-form__required">*</em> @endunless
            <small class="admin-form__optional">— ერთზე მეტის შემთხვევაში საიტზე სლაიდერად გამოჩნდება</small>
        </span>

        @if ($step && $step->images->isNotEmpty())
            <ul class="admin-gallery">
                @foreach ($step->images as $image)
                    <li>
                        <label class="admin-gallery__item">
                            <img src="{{ $image->url() }}" alt="" loading="lazy">
                            <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" @checked(in_array($image->id, $removed, true))>
                            <span class="admin-gallery__remove">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16"/><path d="M5 7l1 13h12l1-13"/><path d="M9 7V4h6v3"/></svg>
                                წაშლა
                            </span>
                        </label>
                    </li>
                @endforeach
            </ul>
        @endif

        <label @class(['admin-upload', 'admin-upload--compact', 'is-invalid' => $imagesError])>
            <span class="admin-upload__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="m21 16-5-5-9 9"/></svg>
            </span>
            <span class="admin-upload__text">
                <strong>{{ $step ? 'სურათების დამატება' : 'სურათების არჩევა' }}</strong>
                <small data-photos-caption>შეგიძლიათ რამდენიმე ერთად · JPG, PNG, WEBP · თითო 4MB · მაქს. {{ \App\Http\Requests\Admin\ServiceStepRequest::MAX_IMAGES }}</small>
            </span>
            <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple data-photos-input>
        </label>
        <ul class="admin-gallery admin-gallery--new" data-photos-preview hidden></ul>

        @if ($imagesError)
            <span class="admin-form__error">{{ $imagesError }}</span>
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
                value="{{ $old('sort_order', $step?->sort_order ?? 0) }}"
                @class(['admin-form__input', 'is-invalid' => $errors->has('sort_order')])
            >
        </label>

        <label class="admin-form__check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked($old('is_active', $step?->is_active ?? true))>
            <span>საიტზე ჩანს</span>
        </label>

        <div class="admin-form__actions">
            @if ($step)
                <a href="{{ route('admin.services') }}" class="admin-btn admin-btn--ghost">გაუქმება</a>
            @endif
            <button type="submit" class="admin-btn">{{ $submitLabel }}</button>
        </div>
    </div>

    @error('sort_order')
        <span class="admin-form__error">{{ $message }}</span>
    @enderror
</form>
