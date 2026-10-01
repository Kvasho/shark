@extends('admin.layouts.app')

@section('title', 'ნაბიჯის რედაქტირება')

@section('content')
    <a href="{{ route('admin.services') }}" class="admin-back">← სერვისები</a>

    <section class="admin-card">
        <h2 class="admin-card__title">{{ $step->translate('title') }}</h2>
        <p class="admin-card__text">წასაშლელ სურათზე დააჭირეთ — მოინიშნება და შენახვისას წაიშლება. ახალი სურათები არსებულებს ბოლოში დაემატება.</p>

        @include('admin.components.service-step-form', [
            'action' => route('admin.services.steps.update', $step),
            'step' => $step,
            'submitLabel' => 'შენახვა',
        ])
    </section>
@endsection
