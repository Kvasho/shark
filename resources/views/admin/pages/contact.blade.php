@extends('admin.layouts.app')

@section('title', 'კონტაქტი')

@section('content')
    <section class="admin-card admin-card--narrow">
        <h2 class="admin-card__title">საკონტაქტო ინფორმაცია</h2>
        <p class="admin-card__text">ეს მონაცემები ჩანს საიტის „კონტაქტის“ გვერდზე. შენახვისთანავე განახლდება.</p>

        <form method="POST" action="{{ route('admin.contact.update') }}" class="admin-form" novalidate>
            @csrf
            @method('PUT')

            <label class="admin-form__field">
                <span class="admin-form__label">ტელეფონის ნომერი</span>
                <input
                    type="tel"
                    name="phone"
                    value="{{ old('phone', $phone) }}"
                    maxlength="30"
                    placeholder="+995 32 200 00 00"
                    autocomplete="off"
                    required
                    @class(['admin-form__input', 'is-invalid' => $errors->has('phone')])
                >
                <small class="admin-form__hint">ჩაწერეთ ისე, როგორც საიტზე უნდა გამოჩნდეს — მაგ. +995 32 200 00 00.</small>
                @error('phone')
                    <span class="admin-form__error">{{ $message }}</span>
                @enderror
            </label>

            <label class="admin-form__field">
                <span class="admin-form__label">ელფოსტა</span>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $email) }}"
                    maxlength="150"
                    placeholder="hello@shark.ge"
                    autocomplete="off"
                    required
                    @class(['admin-form__input', 'is-invalid' => $errors->has('email')])
                >
                @error('email')
                    <span class="admin-form__error">{{ $message }}</span>
                @enderror
            </label>

            <button type="submit" class="admin-btn">შენახვა</button>
        </form>
    </section>
@endsection
