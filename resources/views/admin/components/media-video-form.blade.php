{{--
    ვიდეოს დამატების/რედაქტირების ფორმა.
    პარამეტრები: $action (URL), $video (MediaVideo|null), $submitLabel
--}}
@php
    $video ??= null;
    // ვალიდაციის შეცდომები "video" ჩანთაშია; i18n-fields-ს ქვემოთ ვაწვდით როგორც ნაგულისხმევს.
    $bag = $errors->video;
    // old() მხოლოდ ამ ფორმის გაგზავნის შემდეგ — მედიის გვერდზე ფოტოების ფორმაც არის.
    $old = fn ($key, $default = null) => old('_form') === 'video' ? old($key, $default) : $default;

    $fields = [
        'title' => ['სათაური', true, 200, false],
    ];
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="admin-form admin-form--compact" novalidate>
    @csrf
    <input type="hidden" name="_form" value="video">
    @if ($video)
        @method('PUT')
    @endif

    @include('admin.components.i18n-fields', ['model' => $video, 'errors' => (new \Illuminate\Support\ViewErrorBag)->put('default', $bag)])

    <div class="admin-form__bar">
        <div class="admin-form__field admin-form__field--grow">
            <label @class(['admin-upload', 'admin-upload--compact', 'is-invalid' => $bag->has('video')])>
                @if ($video)
                    <video src="{{ $video->url() }}#t=0.5" class="admin-upload__preview" muted preload="metadata"></video>
                @else
                    <span class="admin-upload__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="m10 9 5 3-5 3z"/></svg>
                    </span>
                @endif
                <span class="admin-upload__text">
                    <strong>
                        {{ $video ? 'ვიდეოს შეცვლა' : 'ვიდეოს არჩევა' }}
                        @unless ($video) <em class="admin-form__required">*</em> @endunless
                    </strong>
                    <small data-file-caption>MP4, WEBM, MOV · 100MB</small>
                </span>
                <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime" data-file-input>
            </label>
        </div>

        <label class="admin-form__field admin-form__field--order" title="ნაკლები რიცხვი — უფრო წინ გამოჩნდება">
            <span class="admin-form__label">რიგი</span>
            <input
                type="number"
                name="sort_order"
                min="0"
                max="9999"
                value="{{ $old('sort_order', $video?->sort_order ?? 0) }}"
                @class(['admin-form__input', 'is-invalid' => $bag->has('sort_order')])
            >
        </label>

        <label class="admin-form__check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked($old('is_active', $video?->is_active ?? true))>
            <span>საიტზე ჩანს</span>
        </label>

        <div class="admin-form__actions">
            @if ($video)
                <a href="{{ route('admin.media') }}#videos" class="admin-btn admin-btn--ghost">გაუქმება</a>
            @endif
            <button type="submit" class="admin-btn" data-submit-label="იტვირთება…">{{ $submitLabel }}</button>
        </div>
    </div>

    @foreach (['video', 'sort_order'] as $field)
        @if ($bag->has($field))
            <span class="admin-form__error">{{ $bag->first($field) }}</span>
        @endif
    @endforeach
</form>
