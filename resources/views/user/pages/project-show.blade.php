@extends('user.layouts.app')

@section('title', $project['title'] . ' | SHARK')
@section('description', $project['excerpt'])
@section('bodyClass', 'shark-site project-detail-page')

@push('styles')
    @vite('resources/css/user/projects.css')
@endpush

@push('scripts')
    @vite('resources/js/user/projects.js')
@endpush

@section('content')
    <div class="projects-progress" aria-hidden="true"><span></span></div>

    <section class="project-detail-hero">
        <img src="{{ asset($project['cover']) }}" alt="{{ $project['title'] }}">
        <div class="project-detail-hero__overlay"></div>
        <div class="project-detail-hero__content">
            <a href="{{ route('projects') }}"><i class="fa-solid fa-arrow-left"></i> ყველა პროექტი</a>
            <span>{{ $project['category'] }} · {{ $project['year'] }}</span>
            <h1>{{ $project['title'] }}</h1>
        </div>
        <span class="project-detail-hero__scroll">Scroll <i></i></span>
    </section>

    <section class="project-detail-intro projects-reveal">
        <p>{{ $project['description'] }}</p>
        <dl>
            <div><dt>ლოკაცია</dt><dd>{{ $project['location'] }}</dd></div>
            <div><dt>ფართობი</dt><dd>{{ $project['area'] }}</dd></div>
            <div><dt>წელი</dt><dd>{{ $project['year'] }}</dd></div>
            <div><dt>ტიპი</dt><dd>{{ $project['category'] }}</dd></div>
        </dl>
    </section>

    <section class="project-gallery" aria-label="{{ $project['title'] }} გალერეა">
        @foreach($project['gallery'] as $image)
            <button class="project-gallery__item projects-reveal" type="button" data-gallery-image="{{ asset($image) }}" aria-label="სურათის სრულად ნახვა">
                <img src="{{ asset($image) }}" alt="{{ $project['title'] }} — ფოტო {{ $loop->iteration }}" loading="lazy">
                <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
            </button>
        @endforeach
    </section>

    <section class="project-detail-story">
        <span class="projects-kicker projects-reveal">გამოწვევა და გადაწყვეტა</span>
        <div class="projects-reveal"><h2>კონტექსტიდან დაბადებული არქიტექტურა</h2><p>{{ $project['challenge'] }}</p></div>
    </section>

    <section class="related-projects">
        <header class="projects-reveal"><span class="projects-kicker">შემდეგი სანახავი</span><h2>სხვა პროექტები</h2></header>
        <div>
            @foreach($relatedProjects as $related)
                <a class="related-project projects-reveal" href="{{ route('projects.show', $related['slug']) }}">
                    <img src="{{ asset($related['cover']) }}" alt="{{ $related['title'] }}" loading="lazy">
                    <span><small>{{ $related['category'] }}</small>{{ $related['title'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <div class="project-lightbox" role="dialog" aria-modal="true" aria-label="პროექტის გალერეა" aria-hidden="true">
        <button class="project-lightbox__close" type="button" aria-label="დახურვა"><i class="fa-solid fa-xmark"></i></button>
        <button class="project-lightbox__nav project-lightbox__nav--prev" type="button" aria-label="წინა ფოტო"><i class="fa-solid fa-arrow-left"></i></button>
        <img src="" alt="გალერეის ფოტო">
        <span class="project-lightbox__counter"></span>
        <button class="project-lightbox__nav project-lightbox__nav--next" type="button" aria-label="შემდეგი ფოტო"><i class="fa-solid fa-arrow-right"></i></button>
    </div>
@endsection
