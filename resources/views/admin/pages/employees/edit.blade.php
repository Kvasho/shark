@extends('admin.layouts.app')

@section('title', 'თანამშრომლის რედაქტირება')

@section('content')
    <a href="{{ route('admin.company') }}" class="admin-back">← კომპანია</a>

    <section class="admin-card">
        <h2 class="admin-card__title">{{ $employee->fullName() }}</h2>
        <p class="admin-card__text">თუ სურათს არ შეცვლით, ძველი დარჩება.</p>

        @include('admin.components.employee-form', [
            'action' => route('admin.company.employees.update', $employee),
            'employee' => $employee,
            'submitLabel' => 'შენახვა',
        ])
    </section>
@endsection
