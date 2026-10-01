@extends('user.layouts.app')

@section('title', 'კონტაქტი | SHARK')
@section('description', 'დაუკავშირდით SHARK-ს — ოფისის მისამართი, ტელეფონი და საკონტაქტო ფორმა.')
@section('bodyClass', 'shark-site contact-page')

@push('styles')
    @vite('resources/css/user/contact.css')
@endpush

@push('scripts')
    @vite('resources/js/user/contact.js')
@endpush

@section('content')
    <div class="contact-progress" aria-hidden="true"><span></span></div>

    <section class="contact-details">
        <header class="contact-details__heading contact-reveal">
            <span class="contact-kicker">სად გვიპოვით</span>
            <h2>ყოველთვის ახლოს,<br>როცა გვჭირდებით.</h2>
        </header>
        <div class="contact-details__grid">
            {{-- <a class="contact-detail contact-reveal" href="https://maps.google.com/?q=Vazha-Pshavela+71+Tbilisi" target="_blank" rel="noopener">
                <span>01</span><i class="fa-solid fa-location-dot"></i><small>ოფისის მისამართი</small><strong>თბილისი, ვაჟა-ფშაველას გამზირი 71</strong><em>რუკაზე ნახვა <i class="fa-solid fa-arrow-up-right-from-square"></i></em>
            </a> --}}
            {{-- ტელეფონი და ელფოსტა იმართება ადმინიდან (კონტაქტი). --}}
            @if ($phone)
                <a class="contact-detail contact-reveal contact-delay-1" href="tel:{{ $phoneLink }}">
                    <span>02</span><i class="fa-solid fa-phone"></i><small>ტელეფონი</small><strong data-i18n-skip>{{ $phone }}</strong><em>დარეკვა <i class="fa-solid fa-arrow-right"></i></em>
                </a>
            @endif
            @if ($email)
                <a class="contact-detail contact-detail--accent contact-reveal contact-delay-2" href="mailto:{{ $email }}">
                    <span>03</span><i class="fa-solid fa-envelope"></i><small>ელფოსტა</small><strong data-i18n-skip>{{ $email }}</strong><em>წერილის გაგზავნა <i class="fa-solid fa-arrow-right"></i></em>
                </a>
            @endif
        </div>
    </section>

    <div class="contact-modal" role="dialog" aria-modal="true" aria-labelledby="contactModalTitle" aria-hidden="true">
        <div class="contact-modal__backdrop" data-close-contact></div>
        <div class="contact-modal__panel">
            <button class="contact-modal__close" type="button" data-close-contact aria-label="ფორმის დახურვა"><i class="fa-solid fa-xmark"></i></button>

            <div class="contact-modal__form-view">
                <span class="contact-kicker">მოგვწერე</span>
                <h2 id="contactModalTitle">მოგვიყევი შენი იდეის შესახებ</h2>
                <p>შეავსე ფორმა და ჩვენი გუნდი უახლოეს პერიოდში დაგიკავშირდება.</p>

                <form id="contactForm" action="{{ route('contact.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="contact-form__row">
                        <label><span>სახელი და გვარი *</span><input type="text" name="name" autocomplete="name" required><small data-error="name"></small></label>
                        <label><span>ტელეფონი *</span><input type="tel" name="phone" autocomplete="tel" required><small data-error="phone"></small></label>
                    </div>
                    <div class="contact-form__row">
                        <label><span>ელფოსტა *</span><input type="email" name="email" autocomplete="email" required><small data-error="email"></small></label>
                        <label><span>სერვისი</span><select name="service"><option value="">აირჩიეთ</option><option>არქიტექტურა და პროექტირება</option><option>სამშენებლო სამუშაოები</option><option>რემონტი და ინტერიერი</option><option>პროექტის მართვა</option></select><small data-error="service"></small></label>
                    </div>
                    <label><span>შეტყობინება *</span><textarea name="message" rows="5" required placeholder="მოკლედ აღწერეთ თქვენი პროექტი..."></textarea><small data-error="message"></small></label>
                    <input class="contact-honeypot" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <div class="contact-form__status" role="alert"></div>
                    <button class="contact-form__submit" type="submit"><span>ინფორმაციის გაგზავნა</span><i class="fa-solid fa-paper-plane"></i></button>
                </form>
            </div>

            <div class="contact-modal__success" aria-live="polite">
                <span><i class="fa-solid fa-check"></i></span>
                <h2>მადლობა!</h2>
                <p>თქვენი ინფორმაცია წარმატებით გაიგზავნა. ჩვენი გუნდი მალე დაგიკავშირდებათ.</p>
                <button type="button" data-close-contact>დახურვა</button>
            </div>
        </div>
    </div>
@endsection
