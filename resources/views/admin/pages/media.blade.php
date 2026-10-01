@extends('admin.layouts.app')

@section('title', 'მედია')

@section('content')
    {{-- ფოტო გალერეა --}}
    <section class="admin-card" id="photos">
        <div class="admin-card__head">
            <div>
                <h2 class="admin-card__title">ფოტო გალერეა <span class="admin-count">{{ $photos->count() }}</span></h2>
                <p class="admin-card__text">საიტზე ახალი ფოტოები პირველი ჩანს. წასაშლელად მონიშნეთ ფოტოები და დააჭირეთ „წაშლა“.</p>
            </div>
        </div>

        @php $photoErrors = $errors->photos; @endphp
        <form method="POST" action="{{ route('admin.media.photos.store') }}" enctype="multipart/form-data" class="admin-form admin-form--compact admin-form--flush" novalidate>
            @csrf
            <div class="admin-form__bar admin-form__bar--plain">
                <div class="admin-form__field admin-form__field--grow">
                    <label @class(['admin-upload', 'admin-upload--compact', 'is-invalid' => $photoErrors->any()])>
                        <span class="admin-upload__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="m21 16-5-5-9 9"/></svg>
                        </span>
                        <span class="admin-upload__text">
                            <strong>ფოტოების არჩევა</strong>
                            <small data-photos-caption>რამდენიმე ერთად · JPG, PNG, WEBP · თითო 8MB · ერთ ჯერზე მაქს. {{ \App\Http\Requests\Admin\MediaPhotoRequest::MAX_UPLOAD }}</small>
                        </span>
                        <input type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple data-photos-input>
                    </label>
                </div>
                <div class="admin-form__actions">
                    <button type="submit" class="admin-btn" data-submit-label="იტვირთება…">ატვირთვა</button>
                </div>
            </div>
            <ul class="admin-gallery admin-gallery--new" data-photos-preview hidden></ul>
            @if ($photoErrors->any())
                <span class="admin-form__error">{{ $photoErrors->first('photos') ?: $photoErrors->first('photos.*') }}</span>
            @endif
        </form>

        @if ($photos->isEmpty())
            <p class="admin-empty-line">ფოტოები ჯერ არ არის ატვირთული.</p>
        @else
            <form
                method="POST"
                action="{{ route('admin.media.photos.destroy') }}"
                class="admin-photo-manager"
                data-selection-form
                data-confirm="ნამდვილად გსურთ მონიშნული ფოტოების წაშლა?"
            >
                @csrf
                @method('DELETE')

                <div class="admin-photo-manager__bar">
                    <label class="admin-form__check">
                        <input type="checkbox" data-select-all>
                        <span>ყველას მონიშვნა</span>
                    </label>
                    <button type="submit" class="admin-btn admin-btn--danger" data-selection-submit disabled>
                        წაშლა <span data-selection-count></span>
                    </button>
                </div>

                <ul class="admin-gallery admin-gallery--grid">
                    @foreach ($photos as $photo)
                        <li>
                            <label class="admin-gallery__item">
                                <img src="{{ $photo->url() }}" alt="" loading="lazy">
                                <input type="checkbox" name="photos[]" value="{{ $photo->id }}" data-selection-item>
                                <span class="admin-gallery__remove">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16"/><path d="M5 7l1 13h12l1-13"/><path d="M9 7V4h6v3"/></svg>
                                    წაშლა
                                </span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </form>
        @endif
    </section>

    {{-- ვიდეოები --}}
    <section class="admin-card" id="videos">
        <div class="admin-card__head">
            <div>
                <h2 class="admin-card__title">ვიდეოები <span class="admin-count">{{ $videos->count() }}</span></h2>
                <p class="admin-card__text">თითო ვიდეოს აქვს სათაური სამ ენაზე.</p>
            </div>
        </div>

        @if ($videos->isEmpty())
            <p class="admin-empty-line">ვიდეოები ჯერ არ არის დამატებული.</p>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ვიდეო</th>
                            <th>სათაური</th>
                            <th>რიგი</th>
                            <th>სტატუსი</th>
                            <th class="admin-table__actions-head">მოქმედება</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($videos as $video)
                            <tr>
                                <td>
                                    <a href="{{ $video->url() }}" target="_blank" rel="noopener" class="admin-video-thumb" title="ნახვა">
                                        <video src="{{ $video->url() }}#t=0.5" muted preload="metadata"></video>
                                        <span class="admin-gallery__badge" aria-hidden="true">▶</span>
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $video->translate('title') }}</strong>
                                    <small class="admin-table__sub">{{ $video->translate('title', 'en') }} · {{ $video->translate('title', 'ru') }}</small>
                                </td>
                                <td>{{ $video->sort_order }}</td>
                                <td>
                                    <span @class(['admin-badge', 'admin-badge--on' => $video->is_active])>
                                        {{ $video->is_active ? 'აქტიური' : 'დამალული' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-table__actions">
                                        <a href="{{ route('admin.media.videos.edit', $video) }}" class="admin-icon-btn" title="რედაქტირება" aria-label="რედაქტირება">
                                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route('admin.media.videos.destroy', $video) }}"
                                            data-confirm="ნამდვილად გსურთ „{{ $video->translate('title') }}“ ვიდეოს წაშლა?"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-icon-btn admin-icon-btn--danger" title="წაშლა" aria-label="წაშლა">
                                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16"/><path d="M10 11v6M14 11v6"/><path d="M5 7l1 13h12l1-13"/><path d="M9 7V4h6v3"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <h3 class="admin-card__subtitle">ახალი ვიდეოს დამატება</h3>

        @include('admin.components.media-video-form', [
            'action' => route('admin.media.videos.store'),
            'video' => null, // ციკლის $video ფორმაში არ უნდა გადავიდეს
            'submitLabel' => 'დამატება',
        ])
    </section>
@endsection
