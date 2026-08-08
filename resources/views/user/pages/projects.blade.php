@extends('user.layouts.app')

@section('title', 'პროექტები | SHARK')
@section('description', 'SHARK-ის განხორციელებული სამშენებლო და არქიტექტურული პროექტები.')
@section('bodyClass', 'shark-site projects-page')

@push('styles')
    @vite('resources/css/user/projects.css')
@endpush

@push('scripts')
    @vite('resources/js/user/projects.js')
@endpush

@section('content')
    <div class="projects-progress" aria-hidden="true"><span></span></div>

    <section class="projects-hero">
        <div class="projects-hero__orb" aria-hidden="true"></div>
        <div class="projects-hero__content">
            <span class="projects-kicker projects-reveal">ჩვენი ნამუშევრები</span>
            <h1><span>სივრცეები,</span><span>რომლებიც <em>რჩება.</em></span></h1>
            <p class="projects-reveal projects-delay-2">გაეცანი პროექტებს, სადაც ფუნქცია, კონტექსტი და თამამი არქიტექტურული ხედვა ერთიანდება.</p>
        </div>
        <span class="projects-hero__count">{{ str_pad(count($projects), 2, '0', STR_PAD_LEFT) }} პროექტი</span>
    </section>

    <section class="projects-index">
        <div class="projects-filter projects-reveal" aria-label="პროექტების ფილტრი">
            <button class="is-active" type="button" data-filter="all">ყველა</button>
            @foreach(collect($projects)->pluck('category')->unique() as $category)
                <button type="button" data-filter="{{ $category }}">{{ $category }}</button>
            @endforeach
        </div>

        <div class="projects-grid">
            @foreach($projects as $project)
                <article class="project-article projects-reveal {{ $loop->odd ? 'project-article--wide' : '' }}" data-category="{{ $project['category'] }}">
                    <a class="project-article__image" href="{{ route('projects.show', $project['slug']) }}">
                        <img src="{{ asset($project['cover']) }}" alt="{{ $project['title'] }}" loading="lazy">
                        <span class="project-article__open"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                    </a>
                    <div class="project-article__meta"><span>{{ $project['category'] }}</span><span>{{ $project['year'] }}</span></div>
                    <h2><a href="{{ route('projects.show', $project['slug']) }}">{{ $project['title'] }}</a></h2>
                    <p>{{ $project['excerpt'] }}</p>
                    <a class="project-article__link" href="{{ route('projects.show', $project['slug']) }}">პროექტის ნახვა <i class="fa-solid fa-arrow-right"></i></a>
                </article>
            @endforeach
        </div>
    </section>

    <section class="projects-cta projects-reveal">
        <span>შემდეგი პროექტი შეიძლება შენი იყოს</span>
        <h2>გაქვს იდეა?<br>მოდი, ავაშენოთ.</h2>
        <a href="{{ route('contact') }}">დაგვიკავშირდი <i class="fa-solid fa-arrow-right"></i></a>
    </section>
@endsection
