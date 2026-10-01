@extends('user.layouts.app')

@section('title', 'მთავარი | SHARK')

@section('description', 'SHARK — უწყვეტი სწრაფვა განვითარებისკენ')

@section('bodyClass', 'shark-site shark-home-page')

@push('styles')
    @vite('resources/css/user/main.css')
@endpush

@push('scripts')
    @vite('resources/js/user/main.js')
@endpush

@section('content')
    <section
        id="sharkHero"
        class="shark-hero"
        aria-label="SHARK მთავარი გვერდი"
    >
        <video
            id="sharkHeroVideo"
            class="shark-hero__video"
            autoplay
            muted
            loop
            playsinline
            preload="auto"
            aria-hidden="true"
        >
            <source src="{{ asset('landing.mp4') }}" type="video/mp4">

            თქვენი ბრაუზერი ვიდეოს ვერ ხსნის.
        </video>

        <div class="shark-hero__overlay"></div>

        <div class="shark-hero__glow shark-hero__glow--left"></div>
        <div class="shark-hero__glow shark-hero__glow--right"></div>

        <button
            type="button"
            id="sharkSoundButton"
            class="shark-sound"
            aria-label="Turn video sound on"
            aria-pressed="false"
        >
            <i class="fa-solid fa-volume-xmark" aria-hidden="true"></i>
            <span>ხმის ჩართვა</span>
        </button>

        <div class="shark-hero__content">
            <p class="shark-hero__eyebrow">
                <span>SHARK</span>
            </p>

            <h1 class="shark-hero__title">
                <span class="shark-hero__title-line">
                    <span>უწყვეტი სწრაფვა</span>
                </span>

                <span class="shark-hero__title-line">
                    <span>განვითარებისკენ</span>
                </span>
            </h1>

            <div class="shark-hero__line"></div>

            <p class="shark-hero__description">
                იდეებიდან რეალურ შედეგებამდე
            </p>
        </div>

        <button
            type="button"
            id="sharkScrollButton"
            class="shark-scroll"
            aria-label="ქვემოთ ჩამოსქროლვა"
        >
            <span class="shark-scroll__text">აღმოაჩინე მეტი</span>

            <span class="shark-scroll__mouse">
                <span class="shark-scroll__wheel"></span>
            </span>

            <span class="shark-scroll__arrow">
                <i class="fa-solid fa-chevron-down"></i>
            </span>
        </button>
    </section>

    <section id="sharkNextSection" class="home-about home-section">
        <div class="home-about__image reveal" data-parallax="0.08">
            <img src="{{ asset('1.png') }}" alt="SHARK კომპანიის გუნდი" loading="lazy">
            <span class="home-about__number">01</span>
        </div>
        <div class="home-about__content reveal reveal--right">
            <span class="home-kicker">ჩვენ შესახებ</span>
            <h2>იდეებიდან რეალურ შედეგებამდე</h2>
            <p>ვქმნით სივრცეს ახალი შესაძლებლობებისთვის — გამოცდილებით, პასუხისმგებლობით და თანამედროვე ხედვით.</p>
            <a class="home-link" href="{{ route('company') }}">გაიგე მეტი <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </section>

    <section class="home-services home-section">
        <div class="home-services__background parallax-media" data-parallax="0.12">
            <img src="{{ asset('2.png') }}" alt="SHARK სერვისები" loading="lazy">
        </div>
        <div class="home-services__overlay"></div>
        <div class="home-services__content reveal">
            <span class="home-kicker home-kicker--light">ჩვენი სერვისები</span>
            <h2>სრული მომსახურება ერთი ხედვის გარშემო</h2>
            <p>სტრატეგიიდან შესრულებამდე — თითოეულ დეტალს ერთიან, შედეგზე ორიენტირებულ პროცესად ვაქცევთ.</p>
            <a class="home-button home-button--light" href="{{ route('services') }}">სერვისების ნახვა <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </section>

    <section class="home-projects home-section">
        <div class="home-projects__heading reveal">
            <div><span class="home-kicker">რჩეული ნამუშევრები</span><h2>პროექტები, რომლებიც ისტორიას ქმნიან</h2></div>
            <a class="home-link" href="{{ route('projects') }}">ყველა პროექტი <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="home-projects__grid">
            <a class="project-card project-card--large reveal" href="{{ route('projects') }}">
                <img src="{{ asset('3.png') }}" alt="SHARK პროექტი" loading="lazy" data-parallax="0.06">
                <span><small>რჩეული პროექტი</small>გამორჩეული სივრცე</span>
            </a>
            <a class="project-card reveal reveal--delay" href="{{ route('projects') }}">
                <img src="{{ asset('4.png') }}" alt="SHARK პროექტი" loading="lazy" data-parallax="0.09">
                <span><small>ახალი ხედვა</small>თანამედროვე გადაწყვეტა</span>
            </a>
            <a class="project-card reveal reveal--delay-2" href="{{ route('projects') }}">
                <img src="{{ asset('5.png') }}" alt="SHARK პროექტი" loading="lazy" data-parallax="0.07">
                <span><small>დეტალები</small>ხარისხი ყოველ ნაბიჯზე</span>
            </a>
        </div>
    </section>

    <section class="home-media home-section">
        <div class="home-media__content reveal">
            <span class="home-kicker">მედია</span>
            <h2>ამბები, იდეები და მნიშვნელოვანი მომენტები</h2>
            <p>გაეცანი სიახლეებს, ჩვენს ყოველდღიურობასა და პროექტების მიღმა არსებულ ისტორიებს.</p>
            <a class="home-button" href="{{ route('media') }}">მედიის გვერდი <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="home-media__visual reveal reveal--right">
            <div class="home-media__image home-media__image--main" data-parallax="0.08"><img src="{{ asset('6.png') }}" alt="SHARK მედია" loading="lazy"></div>
            <div class="home-media__image home-media__image--accent" data-parallax="-0.05"><img src="{{ asset('7.png') }}" alt="SHARK სიახლეები" loading="lazy"></div>
        </div>
    </section>

    <section class="home-contact home-section">
        <div class="home-contact__background parallax-media" data-parallax="0.1"><img src="{{ asset('8.png') }}" alt="SHARK კონტაქტი" loading="lazy"></div>
        <div class="home-contact__overlay"></div>
        <div class="home-contact__content reveal">
            <span class="home-kicker home-kicker--light">დავიწყოთ საუბარი</span>
            <h2>გაქვს იდეა?<br>შევქმნათ ერთად.</h2>
            <a class="home-button home-button--light" href="{{ route('contact') }}">დაგვიკავშირდი <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </section>
@endsection
