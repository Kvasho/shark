{{--
    სათარგმნი ველების ცხრილი: თითო ველი ერთ რიგად, ენები სვეტებად (KA / EN / RU).
    პარამეტრები:
      $fields — ველი => [სათაური, სავალდებულოა?, maxlength, textarea?]
      $model  — არსებული ჩანაწერი (რედაქტირებისას) ან null
      $old    — fn ($key, $default) — ფორმის old() მნიშვნელობა
--}}
@php
    $locales = config('admin.locales');
@endphp

<div class="admin-i18n">
    <div class="admin-i18n__head" aria-hidden="true">
        <span></span>
        @foreach ($locales as $code => $language)
            <span><b class="admin-i18n__code">{{ strtoupper($code) }}</b> {{ $language }}</span>
        @endforeach
    </div>

    @foreach ($fields as $field => [$label, $required, $max, $multiline])
        <div class="admin-i18n__row">
            <div class="admin-i18n__label">
                {{ $label }}
                @if ($required)
                    <em class="admin-form__required">*</em>
                @else
                    <small class="admin-form__optional">არასავალდ.</small>
                @endif
            </div>

            @foreach ($locales as $code => $language)
                @php $value = $old("$field.$code", $model?->{$field}[$code] ?? ''); @endphp
                <label class="admin-i18n__cell">
                    <span class="admin-i18n__code admin-i18n__code--inline">{{ strtoupper($code) }}</span>
                    @if ($multiline)
                        <textarea
                            name="{{ $field }}[{{ $code }}]"
                            rows="2"
                            maxlength="{{ $max }}"
                            lang="{{ $code }}"
                            aria-label="{{ $label }} ({{ $language }})"
                            @class(['admin-form__input', 'is-invalid' => $errors->has("$field.$code")])
                        >{{ $value }}</textarea>
                    @else
                        <input
                            type="text"
                            name="{{ $field }}[{{ $code }}]"
                            value="{{ $value }}"
                            maxlength="{{ $max }}"
                            lang="{{ $code }}"
                            aria-label="{{ $label }} ({{ $language }})"
                            @class(['admin-form__input', 'is-invalid' => $errors->has("$field.$code")])
                        >
                    @endif
                    @error("$field.$code")
                        <span class="admin-form__error">{{ $message }}</span>
                    @enderror
                </label>
            @endforeach
        </div>
    @endforeach
</div>
