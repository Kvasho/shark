@extends('user.layouts.app')

@section('title', 'სერვისები | SHARK')
@section('description', 'SHARK-ის სამშენებლო მომსახურებები — იდეიდან პროექტის სრულ ჩაბარებამდე.')
@section('bodyClass', 'shark-site services-page')

@push('styles')
    @vite('resources/css/user/services.css')
@endpush

@push('scripts')
    @vite('resources/js/user/services.js')
@endpush

@section('content')
    <div class="services-progress" aria-hidden="true"><span></span></div>

    <section class="services-hero">
        <div class="services-hero__image" aria-hidden="true">
            <img src="{{ asset('1.png') }}" alt="">
        </div>
        <div class="services-hero__overlay"></div>
        <div class="services-hero__content">
            <span class="services-kicker services-animate">ჩვენი სერვისები</span>
            <h1 class="services-hero__title">
                <span><em>ვაშენებთ</em></span>
                <span><em>იდეიდან</em></span>
                <span><em>რეალობამდე.</em></span>
            </h1>
            <p class="services-animate services-delay-3">სრული სამშენებლო მომსახურება ერთი პასუხისმგებელი გუნდისგან — დაგეგმვა, დიზაინი, მშენებლობა და ხარისხიანი ჩაბარება.</p>
            <a class="services-hero__button services-animate services-delay-4" href="#servicesList">აღმოაჩინე სერვისები <i class="fa-solid fa-arrow-down"></i></a>
        </div>
        <span class="services-hero__word" aria-hidden="true">BUILD</span>
    </section>

    <section id="servicesList" class="services-list services-section">
        <header class="services-heading services-animate">
            <span class="services-label">რას გთავაზობთ</span>
        
            <p>ერთიანი პროცესი ამცირებს რისკებს, ზოგავს დროს და უზრუნველყოფს შედეგს, რომელიც ზუსტად პასუხობს თქვენს მიზანს.</p>
        </header>

        <div class="services-cards">
            <article class="service-card services-animate">
                <span class="service-card__number">01</span><i class="fa-solid fa-compass-drafting"></i>
                <h3>არქიტექტურა და პროექტირება</h3>
                <p>კონცეფცია, სამუშაო ნახაზები, საინჟინრო დაგეგმვა და პროექტის სრული დოკუმენტაცია.</p>
            </article>
            <article class="service-card service-card--accent services-animate services-delay-1">
                <span class="service-card__number">02</span><i class="fa-solid fa-building"></i>
                <h3>სამშენებლო სამუშაოები</h3>
                <p>საცხოვრებელი, კომერციული და ინდუსტრიული ობიექტების მშენებლობა სრული ციკლით.</p>
            </article>
            <article class="service-card services-animate services-delay-2">
                <span class="service-card__number">03</span><i class="fa-solid fa-screwdriver-wrench"></i>
                <h3>რემონტი და ინტერიერი</h3>
                <p>შიდა სივრცეების დაგეგმვა, საინჟინრო სისტემები, მოპირკეთება და ავეჯით მოწყობა.</p>
            </article>
            <article class="service-card service-card--dark services-animate services-delay-3">
                <span class="service-card__number">04</span><i class="fa-solid fa-helmet-safety"></i>
                <h3>პროექტის მართვა</h3>
                <p>ბიუჯეტის, ვადების, მომწოდებლებისა და ხარისხის ყოველდღიური პროფესიონალური კონტროლი.</p>
            </article>
        </div>
    </section>

    @if ($steps->isNotEmpty())
        <section class="services-process services-section">
            <header class="services-heading services-animate">
                <span class="services-label">როგორ ვმუშაობთ</span>
                <h2>გამჭვირვალე პროცესი</h2>
            </header>

            <div class="services-timeline">
                <span class="services-timeline__track" aria-hidden="true"><i></i></span>
                @foreach ($steps as $step)
                    @php
                        $title = $step->translations('title');
                        $description = $step->translations('description');
                        $hasSlider = $step->images->count() > 1;
                    @endphp
                    <article class="process-step services-animate" data-step>
                        <span class="process-step__number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h3 data-i18n-skip data-i18n-values='@json($title)'>{{ $title['ka'] }}</h3>
                            @if (filled($description['ka']))
                                <p data-i18n-skip data-i18n-values='@json($description)'>{{ $description['ka'] }}</p>
                            @endif
                        </div>

                        <div @class(['process-step__media', 'step-slider' => $hasSlider]) @if ($hasSlider) data-step-slider @endif>
                            <div class="step-slider__track" @if ($hasSlider) data-slider-track @endif>
                                @foreach ($step->images as $image)
                                    <img
                                        src="{{ $image->url() }}"
                                        alt="{{ $title['ka'] }}"
                                        loading="lazy"
                                        class="step-slider__slide"
                                        data-i18n-skip
                                        data-i18n-alt='@json($title)'
                                    >
                                @endforeach
                            </div>

                            @if ($hasSlider)
                                <button type="button" class="step-slider__arrow step-slider__arrow--prev" data-slider-prev aria-label="წინა">
                                    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="step-slider__arrow step-slider__arrow--next" data-slider-next aria-label="შემდეგი">
                                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                                </button>
                                <div class="step-slider__dots">
                                    @foreach ($step->images as $image)
                                        <button type="button" @class(['step-slider__dot', 'is-active' => $loop->first]) data-slider-dot="{{ $loop->index }}" aria-label="{{ $loop->iteration }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="services-cta">
        <div class="services-cta__shape" aria-hidden="true"></div>
        <div class="services-cta__content services-animate">
            <span class="services-label services-label--light">დაგეგმე ჩვენთან</span>
            <h2>მზად ხარ მშენებლობის დასაწყებად?</h2>
            <p>მოგვიყევი შენი იდეის შესახებ და ერთად შევქმნით მოქმედების ზუსტ გეგმას.</p>
            <a href="{{ route('contact') }}">კონსულტაციის დაჯავშნა <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </section>
@endsection
