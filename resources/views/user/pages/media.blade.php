@extends('user.layouts.app')

@section('title', 'მედია | SHARK')
@section('description', 'SHARK-ის ფოტო და ვიდეო არქივი — პროექტები, პროცესი და მნიშვნელოვანი მომენტები.')
@section('bodyClass', 'shark-site media-page')

@push('styles')
    @vite('resources/css/user/media.css')
@endpush

@push('scripts')
    @vite('resources/js/user/media.js')
@endpush

@section('content')
    <div class="media-progress" aria-hidden="true"><span></span></div>

    <section class="media-gallery">
        <header class="media-gallery__head media-reveal">
            <div>
                <span class="media-kicker">მედია</span>
                <h1>მომენტები,<br>რომლებიც რჩება</h1>
            </div>
            <p>დეტალები, მასალები, ადამიანები და სივრცეები — თითოეული ფოტო ჩვენი პროცესის ნაწილია.</p>
        </header>

        {{-- ფოტოები / ვიდეოები გადამრთველი --}}
        <div class="media-tabs media-reveal" role="tablist" aria-label="მედიის გალერეა">
            <button type="button" class="media-tabs__tab is-active" role="tab" id="mediaTabPhotos" aria-controls="mediaPanelPhotos" aria-selected="true" data-media-tab="photos">
                <i class="fa-regular fa-image" aria-hidden="true"></i>
                <span>ფოტოები</span>
                <b>{{ $photos->count() }}</b>
            </button>
            <button type="button" class="media-tabs__tab" role="tab" id="mediaTabVideos" aria-controls="mediaPanelVideos" aria-selected="false" tabindex="-1" data-media-tab="videos">
                <i class="fa-solid fa-film" aria-hidden="true"></i>
                <span>ვიდეოები</span>
                <b>{{ $videos->count() }}</b>
            </button>
        </div>

        <div class="media-panel" id="mediaPanelPhotos" role="tabpanel" aria-labelledby="mediaTabPhotos" data-media-panel="photos">
            @if ($photos->isEmpty())
                <p class="media-empty">ფოტოები მალე დაემატება.</p>
            @else
                <ul class="photo-grid">
                    @foreach ($photos as $photo)
                        <li>
                            <button class="photo-tile" type="button" data-photo="{{ $photo->url() }}" aria-label="ფოტოს გადიდება">
                                <img src="{{ $photo->url() }}" alt="" loading="lazy">
                                <i class="fa-solid fa-expand" aria-hidden="true"></i>
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="media-panel" id="mediaPanelVideos" role="tabpanel" aria-labelledby="mediaTabVideos" data-media-panel="videos" hidden>
            @if ($videos->isEmpty())
                <p class="media-empty">ვიდეოები მალე დაემატება.</p>
            @else
                <ul class="video-grid">
                    @foreach ($videos as $video)
                        @php $title = $video->translations('title'); @endphp
                        <li>
                            <button
                                class="video-card"
                                type="button"
                                data-video="{{ $video->url() }}"
                                data-video-title='@json($title)'
                                aria-label="ვიდეოს ჩართვა"
                            >
                                <span class="video-card__media">
                                    {{-- #t=0.5 — ბრაუზერი პირველ კადრებს აჩვენებს, როგორც გარეკანს. --}}
                                    <video src="{{ $video->url() }}#t=0.5" muted playsinline preload="metadata" aria-hidden="true"></video>
                                    <i class="fa-solid fa-play" aria-hidden="true"></i>
                                </span>
                                <span class="video-card__title" data-i18n-skip data-i18n-values='@json($title)'>{{ $title['ka'] }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    <section class="media-cta media-reveal">
        <span class="media-kicker">თვალი ადევნე პროცესს</span>
        <h2>შემდეგი ისტორია<br>უკვე იქმნება.</h2>
        <a href="{{ route('contact') }}">დაგვიკავშირდი <i class="fa-solid fa-arrow-right"></i></a>
    </section>

    <div class="media-viewer" role="dialog" aria-modal="true" aria-hidden="true" aria-label="მედიის გალერეა">
        <button class="media-viewer__close" type="button" aria-label="დახურვა"><i class="fa-solid fa-xmark"></i></button>
        <button class="media-viewer__nav media-viewer__nav--prev" type="button" aria-label="წინა"><i class="fa-solid fa-arrow-left"></i></button>
        <div class="media-viewer__stage">
            <img src="" alt="">
            <video controls playsinline preload="metadata"></video>
        </div>
        <button class="media-viewer__nav media-viewer__nav--next" type="button" aria-label="შემდეგი"><i class="fa-solid fa-arrow-right"></i></button>
        <div class="media-viewer__footer">
            <strong class="media-viewer__title" data-i18n-skip></strong>
            <span class="media-viewer__counter"></span>
        </div>
    </div>
@endsection
