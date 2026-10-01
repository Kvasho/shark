@extends('user.layouts.app')

@php
    $title = $project->translations('title');
    $category = $project->translations('category');
    $location = $project->location ? $project->translations('location') : null;
    // სრული აღწერა; თუ არ არის — მოკლე.
    $description = $project->description ? $project->translations('description') : ($project->excerpt ? $project->translations('excerpt') : null);
    $area = $project->areaTranslations();
@endphp

@section('title', $title['ka'] . ' | SHARK')
@section('description', $project->translate('excerpt') ?? $title['ka'])
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
        <img src="{{ $project->coverUrl() }}" alt="{{ $title['ka'] }}" data-i18n-skip data-i18n-alt='@json($title)'>
        <div class="project-detail-hero__overlay"></div>
        <div class="project-detail-hero__content">
            <a href="{{ route('projects') }}"><i class="fa-solid fa-arrow-left"></i> ყველა პროექტი</a>
            <span>
                <span data-i18n-skip data-i18n-values='@json($category)'>{{ $category['ka'] }}</span>@if ($project->year) · {{ $project->year }}@endif
            </span>
            <h1 data-i18n-skip data-i18n-values='@json($title)'>{{ $title['ka'] }}</h1>
        </div>
        <span class="project-detail-hero__scroll">Scroll <i></i></span>
    </section>

    <section class="project-detail-intro projects-reveal">
        @if ($description)
            <p data-i18n-skip data-i18n-values='@json($description)'>{{ $description['ka'] }}</p>
        @else
            <p></p>
        @endif
        <dl>
            @if ($location)
                <div><dt>ლოკაცია</dt><dd data-i18n-skip data-i18n-values='@json($location)'>{{ $location['ka'] }}</dd></div>
            @endif
            @if ($area)
                <div><dt>ფართობი</dt><dd data-i18n-skip data-i18n-values='@json($area)'>{{ $area['ka'] }}</dd></div>
            @endif
            @if ($project->year)
                <div><dt>წელი</dt><dd>{{ $project->year }}</dd></div>
            @endif
            <div><dt>ტიპი</dt><dd data-i18n-skip data-i18n-values='@json($category)'>{{ $category['ka'] }}</dd></div>
        </dl>
    </section>

    @if ($project->media->isNotEmpty())
        <section class="project-gallery" aria-label="გალერეა">
            @foreach ($project->media as $item)
                <button
                    @class(['project-gallery__item', 'projects-reveal', 'project-gallery__item--video' => $item->isVideo()])
                    type="button"
                    data-gallery-src="{{ $item->url() }}"
                    data-gallery-type="{{ $item->type }}"
                    aria-label="{{ $item->isVideo() ? 'ვიდეოს ნახვა' : 'სურათის სრულად ნახვა' }}"
                >
                    @if ($item->isVideo())
                        {{-- #t=0.1 — ბრაუზერი პირველ კადრს აჩვენებს, როგორც გარეკანს. --}}
                        <video src="{{ $item->url() }}#t=0.1" muted playsinline preload="metadata" aria-hidden="true"></video>
                        <i class="project-gallery__play fa-solid fa-play" aria-hidden="true"></i>
                    @else
                        <img src="{{ $item->url() }}" alt="{{ $title['ka'] }} — {{ $loop->iteration }}" loading="lazy" data-i18n-skip>
                    @endif
                    <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                </button>
            @endforeach
        </section>
    @endif

    @if ($relatedProjects->isNotEmpty())
        <section class="related-projects">
            <header class="projects-reveal"><span class="projects-kicker">შემდეგი სანახავი</span><h2>სხვა პროექტები</h2></header>
            <div>
                @foreach ($relatedProjects as $related)
                    @php $relatedTitle = $related->translations('title'); @endphp
                    <a class="related-project projects-reveal" href="{{ route('projects.show', $related->slug) }}">
                        <img src="{{ $related->coverUrl() }}" alt="{{ $relatedTitle['ka'] }}" loading="lazy" data-i18n-skip data-i18n-alt='@json($relatedTitle)'>
                        <span>
                            <small data-i18n-skip data-i18n-values='@json($related->translations('category'))'>{{ $related->translate('category') }}</small>
                            <b data-i18n-skip data-i18n-values='@json($relatedTitle)'>{{ $relatedTitle['ka'] }}</b>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <div class="project-lightbox" role="dialog" aria-modal="true" aria-label="პროექტის გალერეა" aria-hidden="true">
        <button class="project-lightbox__close" type="button" aria-label="დახურვა"><i class="fa-solid fa-xmark"></i></button>
        <button class="project-lightbox__nav project-lightbox__nav--prev" type="button" aria-label="წინა ფოტო"><i class="fa-solid fa-arrow-left"></i></button>
        <img src="" alt="გალერეის ფოტო">
        <video class="project-lightbox__video" controls playsinline preload="metadata" hidden></video>
        <span class="project-lightbox__counter"></span>
        <button class="project-lightbox__nav project-lightbox__nav--next" type="button" aria-label="შემდეგი ფოტო"><i class="fa-solid fa-arrow-right"></i></button>
    </div>
@endsection
