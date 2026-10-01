@extends('admin.layouts.app')

@section('title', 'მთავარი')

@section('content')
    @php($user = auth()->user())

    <section class="admin-card admin-welcome">
        <div>
            <h2 class="admin-card__title">გამარჯობა, {{ $user->name ?: $user->username }} 👋</h2>
            <p class="admin-card__text">მარცხენა მენიუდან შეგიძლიათ საიტის სექციების მართვა.</p>
        </div>

        <dl class="admin-welcome__meta">
            <div>
                <dt>მომხმარებელი</dt>
                <dd>{{ $user->username }}</dd>
            </div>
            <div>
                <dt>ბოლო შესვლა</dt>
                <dd>{{ $user->last_login_at?->format('d.m.Y H:i') ?? '—' }}</dd>
            </div>
        </dl>
    </section>

    <section class="admin-card admin-card--narrow">
        <h2 class="admin-card__title">პაროლის შეცვლა</h2>
        <p class="admin-card__text">უსაფრთხოებისთვის გამოიყენეთ მინიმუმ 8 სიმბოლო.</p>

        @if (session('status'))
            <div class="admin-alert admin-alert--success" role="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.password.update') }}" class="admin-form">
            @csrf
            @method('PUT')

            @foreach ([
                'current_password' => ['მიმდინარე პაროლი', 'current-password'],
                'password' => ['ახალი პაროლი', 'new-password'],
                'password_confirmation' => ['გაიმეორეთ ახალი პაროლი', 'new-password'],
            ] as $field => [$label, $autocomplete])
                <label class="admin-form__field">
                    <span class="admin-form__label">{{ $label }}</span>
                    <input
                        type="password"
                        name="{{ $field }}"
                        autocomplete="{{ $autocomplete }}"
                        required
                        @class(['admin-form__input', 'is-invalid' => $errors->updatePassword->has($field)])
                    >
                    @if ($errors->updatePassword->has($field))
                        <span class="admin-form__error">{{ $errors->updatePassword->first($field) }}</span>
                    @endif
                </label>
            @endforeach

            <button type="submit" class="admin-btn">პაროლის შეცვლა</button>
        </form>
    </section>
@endsection
