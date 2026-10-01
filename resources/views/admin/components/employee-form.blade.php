{{--
    თანამშრომლის დამატების/რედაქტირების ფორმა — ერთი ადამიანი, სამივე ენა ერთ ცხრილში.
    პარამეტრები: $action (URL), $employee (Employee|null), $submitLabel
--}}
@php
    $employee ??= null;

    // old() მხოლოდ ამ ფორმის გაგზავნის შემდეგ — კომპანიის გვერდზე სხვა ფორმაც არის.
    $old = fn ($key, $default = null) => old('_form') === 'employee' ? old($key, $default) : $default;

    // ველი => [სათაური, სავალდებულოა?, maxlength, textarea?]
    $fields = [
        'first_name' => ['სახელი', true, 100, false],
        'last_name' => ['გვარი', true, 100, false],
        'position' => ['თანამდებობა', false, 150, false],
        'bio' => ['მოკლე ისტორია', false, 3000, true],
    ];
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="admin-form admin-form--compact" novalidate>
    @csrf
    <input type="hidden" name="_form" value="employee">
    @if ($employee)
        @method('PUT')
    @endif

    @include('admin.components.i18n-fields', ['model' => $employee])

    <div class="admin-form__bar">
        <div class="admin-form__field">
            <label @class(['admin-upload', 'admin-upload--compact', 'is-invalid' => $errors->has('photo')])>
                <img
                    src="{{ $employee?->photoUrl() }}"
                    alt=""
                    class="admin-upload__preview"
                    data-photo-preview
                    @unless ($employee) hidden @endunless
                >
                <span class="admin-upload__text">
                    <strong>
                        {{ $employee ? 'სურათის შეცვლა' : 'სურათი' }}
                        @unless ($employee) <em class="admin-form__required">*</em> @endunless
                    </strong>
                    <small>JPG, PNG, WEBP · 4MB</small>
                </span>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" data-photo-input>
            </label>
            @error('photo')
                <span class="admin-form__error">{{ $message }}</span>
            @enderror
        </div>

        <label class="admin-form__field admin-form__field--order" title="ნაკლები რიცხვი — უფრო წინ გამოჩნდება">
            <span class="admin-form__label">რიგი</span>
            <input
                type="number"
                name="sort_order"
                min="0"
                max="9999"
                value="{{ $old('sort_order', $employee?->sort_order ?? 0) }}"
                @class(['admin-form__input', 'is-invalid' => $errors->has('sort_order')])
            >
        </label>

        <label class="admin-form__check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked($old('is_active', $employee?->is_active ?? true))>
            <span>საიტზე ჩანს</span>
        </label>

        <div class="admin-form__actions">
            @if ($employee)
                <a href="{{ route('admin.company') }}" class="admin-btn admin-btn--ghost">გაუქმება</a>
            @endif
            <button type="submit" class="admin-btn">{{ $submitLabel }}</button>
        </div>
    </div>

    @error('sort_order')
        <span class="admin-form__error">{{ $message }}</span>
    @enderror
</form>
