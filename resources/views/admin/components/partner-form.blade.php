{{--
    პარტნიორის დამატების/რედაქტირების ფორმა.
    პარამეტრები: $action (URL), $partner (Partner|null), $submitLabel
--}}
@php
    $partner ??= null;
    $bag = $errors->partner;

    // old() მხოლოდ ამ ფორმის გაგზავნის შემდეგ — კომპანიის გვერდზე სხვა ფორმაც არის.
    $old = fn ($key, $default = null) => old('_form') === 'partner' ? old($key, $default) : $default;
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="admin-form admin-form--compact" novalidate>
    @csrf
    <input type="hidden" name="_form" value="partner">
    @if ($partner)
        @method('PUT')
    @endif

    <div class="admin-partner-form">
        <div class="admin-form__field">
            <span class="admin-form__label">
                ლოგო
                @unless ($partner) <em class="admin-form__required">*</em> @endunless
            </span>
            <label @class(['admin-upload', 'admin-upload--compact', 'admin-upload--logo', 'is-invalid' => $bag->has('logo')])>
                <img
                    src="{{ $partner?->logoUrl() }}"
                    alt=""
                    class="admin-upload__preview"
                    data-photo-preview
                    @unless ($partner) hidden @endunless
                >
                <span class="admin-upload__text">
                    <strong>{{ $partner ? 'შეცვლა' : 'არჩევა' }}</strong>
                    <small>კვადრატული · PNG, JPG, WEBP · 2MB</small>
                </span>
                <input type="file" name="logo" accept="image/png,image/jpeg,image/webp" data-photo-input>
            </label>
            @if ($bag->has('logo'))
                <span class="admin-form__error">{{ $bag->first('logo') }}</span>
            @endif
        </div>

        <label class="admin-form__field">
            <span class="admin-form__label">ბმული <small class="admin-form__optional">(არასავალდ.)</small></span>
            <input
                type="text"
                name="url"
                value="{{ $old('url', $partner?->url) }}"
                maxlength="2048"
                inputmode="url"
                autocomplete="url"
                placeholder="www.facebook.com"
                @class(['admin-form__input', 'is-invalid' => $bag->has('url')])
            >
            @if ($bag->has('url'))
                <span class="admin-form__error">{{ $bag->first('url') }}</span>
            @endif
        </label>

        <label class="admin-form__field admin-form__field--order" title="ნაკლები რიცხვი — უფრო წინ გამოჩნდება">
            <span class="admin-form__label">რიგი</span>
            <input
                type="number"
                name="sort_order"
                min="0"
                max="9999"
                value="{{ $old('sort_order', $partner?->sort_order ?? 0) }}"
                @class(['admin-form__input', 'is-invalid' => $bag->has('sort_order')])
            >
        </label>

        <label class="admin-form__check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked($old('is_active', $partner?->is_active ?? true))>
            <span>საიტზე ჩანს</span>
        </label>

        <div class="admin-form__actions">
            @if ($partner)
                <a href="{{ route('admin.company') }}#partners" class="admin-btn admin-btn--ghost">გაუქმება</a>
            @endif
            <button type="submit" class="admin-btn">{{ $submitLabel }}</button>
        </div>
    </div>

    @if ($bag->has('sort_order'))
        <span class="admin-form__error">{{ $bag->first('sort_order') }}</span>
    @endif
</form>
