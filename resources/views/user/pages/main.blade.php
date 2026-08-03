@extends('user.layouts.app')

@section('title', 'მთავარი | SHARK')

@section('description', 'SHARK — უწყვეტი სწრაფვა განვითარებისკენ')

@section('bodyClass', 'shark-site shark-home-page')

@push('styles')
    @vite('resources/css/user/pages/main.css')
@endpush

@push('scripts')
    @vite('resources/js/user/pages/main.js')
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
            <source src="{{ asset('landing.mov') }}" type="video/quicktime">

            თქვენი ბრაუზერი ვიდეოს ვერ ხსნის.
        </video>

        <div class="shark-hero__overlay"></div>

        <div class="shark-hero__glow shark-hero__glow--left"></div>
        <div class="shark-hero__glow shark-hero__glow--right"></div>

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

    <section
        id="sharkNextSection"
        class="shark-introduction"
    >
        <div class="shark-introduction__container">
            <span class="shark-introduction__label">
                ჩვენ შესახებ
            </span>

            <h2>
                ვქმნით სივრცეს ახალი შესაძლებლობებისთვის
            </h2>

            <p>
                ეს არის მთავარი გვერდის მომდევნო სექციის დროებითი ტექსტი.
                შემდეგ ეტაპზე აქ კომპანიის შესახებ სასურველ დიზაინსა და
                რეალურ ინფორმაციას დავამატებთ.
            </p>
        </div>
    </section>
@endsection
