@extends('admin.layouts.app')

@section('title', 'პარტნიორის რედაქტირება')

@section('content')
    <a href="{{ route('admin.company') }}#partners" class="admin-back">← კომპანია</a>

    <section class="admin-card">
        <h2 class="admin-card__title">ლოგო და ბმული</h2>
        <p class="admin-card__text">თუ ლოგოს არ შეცვლით, ძველი დარჩება.</p>

        @include('admin.components.partner-form', [
            'action' => route('admin.company.partners.update', $partner),
            'partner' => $partner,
            'submitLabel' => 'შენახვა',
        ])
    </section>
@endsection
