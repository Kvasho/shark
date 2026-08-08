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
            <h2>ყველაფერი, რაც წარმატებულ მშენებლობას სჭირდება</h2>
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

    <section class="services-showcase">
        <div class="services-showcase__media" data-services-parallax="0.09">
            <img src="{{ asset('2.png') }}" alt="სამშენებლო პროცესის მართვა" loading="lazy">
        </div>
        <div class="services-showcase__overlay"></div>
        <div class="services-showcase__content services-animate">
            <span class="services-label services-label--light">ერთიანი პასუხისმგებლობა</span>
            <h2>თქვენ ხედავთ შედეგს.<br>ჩვენ ვმართავთ პროცესს.</h2>
            <div class="services-showcase__facts">
                <span><strong>360°</strong> სრული მომსახურება</span>
                <span><strong>100%</strong> ხარისხის კონტროლი</span>
            </div>
        </div>
    </section>

    <section class="services-process services-section">
        <header class="services-heading services-animate">
            <span class="services-label">როგორ ვმუშაობთ</span>
            <h2>გამჭვირვალე პროცესი, ნაბიჯ-ნაბიჯ</h2>
        </header>

        <div class="services-timeline">
            <span class="services-timeline__track" aria-hidden="true"><i></i></span>
            <article class="process-step services-animate" data-step>
                <span class="process-step__number">01</span>
                <div><small>პირველი შეხვედრა</small><h3>კონსულტაცია და საჭიროებების კვლევა</h3><p>ვიგებთ მიზანს, ფუნქციურ მოთხოვნებს, სავარაუდო ბიუჯეტსა და სასურველ ვადებს.</p></div>
                <img src="{{ asset('3.png') }}" alt="სამშენებლო კონსულტაცია" loading="lazy">
            </article>
            <article class="process-step services-animate" data-step>
                <span class="process-step__number">02</span>
                <div><small>ხედვის ფორმირება</small><h3>კონცეფცია და დაგეგმარება</h3><p>ვამზადებთ არქიტექტურულ კონცეფციას, სივრცით გადაწყვეტასა და პირველად ვიზუალიზაციას.</p></div>
                <img src="{{ asset('4.png') }}" alt="არქიტექტურული დაგეგმარება" loading="lazy">
            </article>
            <article class="process-step services-animate" data-step>
                <span class="process-step__number">03</span>
                <div><small>ზუსტი გეგმა</small><h3>ბიუჯეტი, გრაფიკი და დოკუმენტაცია</h3><p>ვადგენთ დეტალურ ხარჯთაღრიცხვას, სამუშაო გრაფიკსა და ტექნიკურ დოკუმენტაციას.</p></div>
                <img src="{{ asset('5.png') }}" alt="პროექტის დაგეგმვა" loading="lazy">
            </article>
            <article class="process-step services-animate" data-step>
                <span class="process-step__number">04</span>
                <div><small>შესრულება</small><h3>მშენებლობა და ყოველდღიური კონტროლი</h3><p>კვალიფიციური გუნდი ასრულებს სამუშაოს, ხოლო მენეჯერი აკონტროლებს ხარისხსა და ვადებს.</p></div>
                <img src="{{ asset('6.png') }}" alt="სამშენებლო სამუშაოები" loading="lazy">
            </article>
            <article class="process-step services-animate" data-step>
                <span class="process-step__number">05</span>
                <div><small>ფინალური ეტაპი</small><h3>შემოწმება და პროექტის ჩაბარება</h3><p>ვატარებთ ხარისხის საბოლოო აუდიტს, ვაბარებთ დოკუმენტაციას და დასრულებულ სივრცეს.</p></div>
                <img src="{{ asset('7.png') }}" alt="დასრულებული პროექტი" loading="lazy">
            </article>
        </div>
    </section>

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
