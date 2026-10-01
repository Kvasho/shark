@extends('admin.layouts.app')

@section('title', 'პროექტის რედაქტირება')

@section('content')
    <a href="{{ route('admin.projects') }}" class="admin-back">← პროექტები</a>

    <section class="admin-card">
        <h2 class="admin-card__title">{{ $project->translate('title') }}</h2>
        <p class="admin-card__text">
            გალერეიდან წასაშლელ ფაილზე დააჭირეთ — მოინიშნება და შენახვისას წაიშლება.
            თუ მთავარ სურათს არ შეცვლით, ძველი დარჩება.
        </p>

        @include('admin.components.project-form', [
            'action' => route('admin.projects.items.update', $project),
            'project' => $project,
            'submitLabel' => 'შენახვა',
        ])
    </section>
@endsection
