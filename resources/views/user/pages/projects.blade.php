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
        <span class="projects-hero__count">{{ str_pad($projects->count(), 2, '0', STR_PAD_LEFT) }} პროექტი</span>
    </section>

    <section class="projects-index">
        @php
            // ფილტრის ღილაკები: ტიპები ქართული მნიშვნელობით ჯგუფდება, ტექსტი კი არჩეულ ენაზე ჩანს.
            $categories = $projects->unique(fn ($project) => $project->translate("category"));
        @endphp
        @if ($categories->count() > 1)
            <div class="projects-filter projects-reveal" aria-label="პროექტების ფილტრი">
                <button class="is-active" type="button" data-filter="all">ყველა</button>
                @foreach ($categories as $project)
                    <button type="button" data-filter="{{ $project->translate("category") }}" data-i18n-skip data-i18n-values='@json($project->translations("category"))'>{{ $project->translate("category") }}</button>
                @endforeach
            </div>
        @endif

        <div class="projects-grid">
            @forelse ($projects as $project)
                @php
                    $title = $project->translations("title");
                    $excerpt = $project->translations("excerpt");
                @endphp
                <article class="project-article projects-reveal {{ $loop->odd ? 'project-article--wide' : '' }}" data-category="{{ $project->translate("category") }}">
                    <a class="project-article__image" href="{{ route('projects.show', $project->slug) }}">
                        <img src="{{ $project->coverUrl() }}" alt="{{ $title['ka'] }}" loading="lazy" data-i18n-skip data-i18n-alt='@json($title)'>
                        <span class="project-article__open"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                    </a>
                    <div class="project-article__meta">
                        <span data-i18n-skip data-i18n-values='@json($project->translations("category"))'>{{ $project->translate("category") }}</span>
                        @if ($project->year)<span>{{ $project->year }}</span>@endif
                    </div>
                    <h2><a href="{{ route('projects.show', $project->slug) }}" data-i18n-skip data-i18n-values='@json($title)'>{{ $title['ka'] }}</a></h2>
                    @if (filled($excerpt['ka']))
                        <p data-i18n-skip data-i18n-values='@json($excerpt)'>{{ $excerpt['ka'] }}</p>
                    @endif
                    <a class="project-article__link" href="{{ route('projects.show', $project->slug) }}">პროექტის ნახვა <i class="fa-solid fa-arrow-right"></i></a>
                </article>
            @empty
                <p class="projects-empty projects-reveal">პროექტები მალე დაემატება.</p>
            @endforelse
        </div>
    </section>

    <section class="projects-cta projects-reveal">
        <span>შემდეგი პროექტი შეიძლება შენი იყოს</span>
        <h2>გაქვს იდეა?<br>მოდი, ავაშენოთ.</h2>
        <a href="{{ route('contact') }}">დაგვიკავშირდი <i class="fa-solid fa-arrow-right"></i></a>
    </section>
@endsection
