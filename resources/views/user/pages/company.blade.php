@extends('user.layouts.app')

@section('title', 'კომპანია | SHARK')
@section('description', 'გაიცანით SHARK — ჩვენი ისტორია, მიდგომა და ღირებულებები.')
@section('bodyClass', 'shark-site company-page')

@push('styles')
    @vite('resources/css/user/company.css')
@endpush

@push('scripts')
    @vite('resources/js/user/company.js')
@endpush

@section('content')
    <div class="company-scroll-progress" aria-hidden="true"><span></span></div>

    <section class="company-hero">
        <div class="company-hero__media" data-company-parallax="0.1">
            <img src="{{ asset('9.png') }}" alt="SHARK კომპანიის შესახებ">
        </div>
        <div class="company-hero__shade"></div>
        <div class="company-hero__content">
            <span class="company-eyebrow company-reveal">ჩვენ შესახებ</span>
            <h1 class="company-title">
                <span><em>ვქმნით იდეებს,</em></span>
                <span><em>რომლებიც მოძრაობენ</em></span>
                <span><em>წინ.</em></span>
            </h1>
            <p class="company-reveal company-delay-3">SHARK აერთიანებს გამოცდილებას, თანამედროვე ხედვასა და ადამიანებს, რომლებსაც თამამი იდეების რეალობად ქცევა შეუძლიათ.</p>
        </div>
        <a class="company-hero__scroll" href="#companyStory" aria-label="ისტორიაზე გადასვლა">
            <span></span><small>აღმოაჩინე</small>
        </a>
    </section>

    <section id="companyStory" class="company-story company-section">
        <span class="company-orb company-orb--one" aria-hidden="true"></span>
        <div class="company-story__intro company-reveal">
            <span class="company-index">01 / ისტორია</span>
            <h2>ყველაფერი იწყება ცნობისმოყვარეობით</h2>
        </div>
        <div class="company-story__body">
            <div class="company-story__copy company-reveal company-from-left">
                <p class="company-story__lead">ჩვენ დავიწყეთ ერთი მარტივი რწმენით — საუკეთესო შედეგი მაშინ იქმნება, როცა სტრატეგია, დიზაინი და შესრულება ერთ ენაზე საუბრობს.</p>
                <p>დღეს SHARK არის განსხვავებული გამოცდილებისა და საერთო ამბიციის მქონე ადამიანების გუნდი. ვუსმენთ, ვიკვლევთ და თითოეულ გამოწვევას ახალ შესაძლებლობად ვაქცევთ.</p>
                <p>ჩვენთვის წარმატება მხოლოდ დასრულებული პროექტი არ არის. ეს არის ღირებულება, რომელიც მომხმარებელს, პარტნიორსა და გარემოს გრძელვადიანად რჩება.</p>
            </div>
            <figure class="company-image-reveal company-story__image">
                <img src="{{ asset('10.png') }}" alt="SHARK-ის სამუშაო პროცესი" loading="lazy">
                <span class="company-image-reveal__curtain"></span>
            </figure>
        </div>
    </section>

    <section class="company-numbers company-section">
        <span class="company-orb company-orb--two" aria-hidden="true"></span>
        <div class="company-numbers__heading company-reveal">
            <span class="company-index">02 / შედეგები</span>
            <h2>რიცხვები, რომლებიც ჩვენს გზას ასახავს</h2>
        </div>
        <div class="company-numbers__grid">
            <article class="company-stat company-reveal">
                <strong data-counter="12" data-suffix="+">0</strong>
                <span>წლიანი გამოცდილება</span>
            </article>
            <article class="company-stat company-reveal company-delay-1">
                <strong data-counter="180" data-suffix="+">0</strong>
                <span>დასრულებული პროექტი</span>
            </article>
            <article class="company-stat company-reveal company-delay-2">
                <strong data-counter="94" data-suffix="%">0</strong>
                <span>განმეორებითი პარტნიორობა</span>
            </article>
            <article class="company-stat company-reveal company-delay-3">
                <strong data-counter="26" data-suffix="">0</strong>
                <span>გუნდის წევრი</span>
            </article>
        </div>
    </section>

    <section class="company-values company-section">
        <div class="company-values__visual company-image-reveal" data-company-parallax="0.06">
            <img src="{{ asset('11.jpg') }}" alt="SHARK გუნდის ხედვა" loading="lazy">
            <span class="company-image-reveal__curtain"></span>
        </div>
        <div class="company-values__content">
            <span class="company-index company-reveal">03 / ღირებულებები</span>
            <h2 class="company-reveal">პრინციპები, რომლებიც მიმართულებას გვაძლევს</h2>
            <div class="company-values__list">
                <article class="company-value company-reveal">
                    <span>01</span><div><h3>სიმამაცე</h3><p>ვირჩევთ ახალ გზებს და არ გვეშინია რთული კითხვების დასმის.</p></div>
                </article>
                <article class="company-value company-reveal">
                    <span>02</span><div><h3>სიზუსტე</h3><p>დიდ სურათთან ერთად ყველა მნიშვნელოვან დეტალზე ვზრუნავთ.</p></div>
                </article>
                <article class="company-value company-reveal">
                    <span>03</span><div><h3>პარტნიორობა</h3><p>ღიად ვთანამშრომლობთ და შედეგზე პასუხისმგებლობას ერთად ვიღებთ.</p></div>
                </article>
            </div>
        </div>
    </section>

    <section class="company-cta">
        <div class="company-cta__ring" aria-hidden="true"></div>
        <div class="company-cta__content company-reveal">
            <span class="company-eyebrow">შემდეგი ნაბიჯი</span>
            <h2>შევქმნათ რაღაც<br>მნიშვნელოვანი ერთად</h2>
            <a href="{{ route('contact') }}">დაგვიკავშირდი <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </section>
@endsection
