@extends('admin.layouts.app')

@section('title', 'ვიდეოს რედაქტირება')

@section('content')
    <a href="{{ route('admin.media') }}#videos" class="admin-back">← მედია</a>

    <section class="admin-card">
        <h2 class="admin-card__title">{{ $video->translate('title') }}</h2>
        <p class="admin-card__text">თუ ვიდეოს ფაილს არ შეცვლით, ძველი დარჩება.</p>

        @include('admin.components.media-video-form', [
            'action' => route('admin.media.videos.update', $video),
            'video' => $video,
            'submitLabel' => 'შენახვა',
        ])
    </section>
@endsection
