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

    <section class="media-hero">
        <div class="media-hero__line" aria-hidden="true"></div>
        <div class="media-hero__content">
            <span class="media-kicker media-reveal">SHARK არქივი</span>
            <h1><span><em>კადრები.</em></span><span><em>ხმები.</em></span><span><em>ისტორიები.</em></span></h1>
            <p class="media-reveal media-delay-3">ჩვენი პროექტები და ყოველდღიური პროცესი — დანახული ფოტოსა და მოძრავი კადრის ენით.</p>
            <nav class="media-hero__nav media-reveal media-delay-4" aria-label="მედიის სექციები">
                <a href="#photoArchive">ფოტოები <span>10</span></a>
                <a href="#videoArchive">ვიდეოები <span>05</span></a>
            </nav>
        </div>
        <span class="media-hero__word" aria-hidden="true">MEDIA</span>
    </section>

    <section id="photoArchive" class="photo-archive">
        <header class="media-section-heading media-reveal">
            <span class="media-kicker">01 / ფოტო არქივი</span>
            <h2>მომენტები,<br>რომლებიც რჩება</h2>
            <p>დეტალები, მასალები, ადამიანები და სივრცეები — თითოეული ფოტო ჩვენი პროცესის ნაწილია.</p>
        </header>

        <div class="photo-wall">
            @foreach(range(1, 10) as $image)
                <button class="photo-tile media-reveal" type="button" data-photo="{{ asset($image . '.png') }}" aria-label="ფოტო {{ $image }} სრულად ნახვა">
                    <img src="{{ asset($image . '.png') }}" alt="SHARK მედია ფოტო {{ $image }}" loading="lazy">
                    <span>{{ str_pad($image, 2, '0', STR_PAD_LEFT) }}</span>
                    <i class="fa-solid fa-expand" aria-hidden="true"></i>
                </button>
            @endforeach
        </div>
    </section>

    <section id="videoArchive" class="video-archive">
        <header class="media-section-heading media-section-heading--dark media-reveal">
            <span class="media-kicker">02 / ვიდეო არქივი</span>
            <h2>პროცესი<br>მოძრაობაში</h2>
            <p>მშენებლობის დინამიკა, დასრულებული სივრცეები და კადრს მიღმა დარჩენილი ისტორიები.</p>
        </header>

        <div class="video-list">
            @foreach(range(1, 5) as $video)
                <button class="video-story media-reveal" type="button" data-video="{{ asset($video . '.mp4') }}">
                    <span class="video-story__index">{{ str_pad($video, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="video-story__media">
                        <video muted loop playsinline preload="metadata" poster="{{ asset((($video - 1) % 10 + 1) . '.png') }}">
                            <source src="{{ asset($video . '.mp4') }}" type="video/mp4">
                        </video>
                        <i class="fa-solid fa-play"></i>
                    </span>
                    <span class="video-story__copy">
                        <small>{{ ['პროექტის ისტორია', 'სამუშაო პროცესი', 'არქიტექტურული დეტალები', 'ობიექტის ქრონიკა', 'დასრულებული სივრცე'][$video - 1] }}</small>
                        <strong>{{ ['იდეიდან პირველ ხაზამდე', 'როგორ იქმნება ხარისხი', 'მასალა, შუქი და ფორმა', 'მშენებლობა ერთ წუთში', 'სივრცე მზად არის'][$video - 1] }}</strong>
                    </span>
                    <span class="video-story__duration">PLAY <i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                </button>
            @endforeach
        </div>
    </section>

    <section class="media-cta media-reveal">
        <span class="media-kicker">თვალი ადევნე პროცესს</span>
        <h2>შემდეგი ისტორია<br>უკვე იქმნება.</h2>
        <a href="{{ route('contact') }}">დაგვიკავშირდი <i class="fa-solid fa-arrow-right"></i></a>
    </section>

    <div class="media-viewer" role="dialog" aria-modal="true" aria-hidden="true" aria-label="მედიის სრულად ნახვა">
        <button class="media-viewer__close" type="button" aria-label="დახურვა"><i class="fa-solid fa-xmark"></i></button>
        <button class="media-viewer__nav media-viewer__nav--prev" type="button" aria-label="წინა"><i class="fa-solid fa-arrow-left"></i></button>
        <div class="media-viewer__stage"><img src="" alt="SHARK მედია"><video controls playsinline></video></div>
        <button class="media-viewer__nav media-viewer__nav--next" type="button" aria-label="შემდეგი"><i class="fa-solid fa-arrow-right"></i></button>
        <span class="media-viewer__counter"></span>
    </div>
@endsection
