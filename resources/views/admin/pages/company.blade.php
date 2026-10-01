@extends('admin.layouts.app')

@section('title', 'კომპანია')

@section('content')
    <section class="admin-card">
        <div class="admin-card__head">
            <div>
                <h2 class="admin-card__title">თანამშრომლები <span class="admin-count">{{ $employees->count() }}</span></h2>
                <p class="admin-card__text">კომპანიის გუნდის წევრები, რომლებიც საიტზე გამოჩნდება.</p>
            </div>
            <a href="#employee-form" class="admin-btn">+ დამატება</a>
        </div>

        @if ($employees->isEmpty())
            <p class="admin-empty-line">თანამშრომლები ჯერ არ არის დამატებული.</p>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>სურათი</th>
                            <th>სახელი, გვარი</th>
                            <th>თანამდებობა</th>
                            <th>რიგი</th>
                            <th>სტატუსი</th>
                            <th class="admin-table__actions-head">მოქმედება</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>
                                    <img src="{{ $employee->photoUrl() }}" alt="" class="admin-table__photo" loading="lazy">
                                </td>
                                <td>
                                    <strong>{{ $employee->fullName() }}</strong>
                                    <small class="admin-table__sub">{{ $employee->fullName('en') }} · {{ $employee->fullName('ru') }}</small>
                                </td>
                                <td>{{ $employee->translate('position') ?? '—' }}</td>
                                <td>{{ $employee->sort_order }}</td>
                                <td>
                                    <span @class(['admin-badge', 'admin-badge--on' => $employee->is_active])>
                                        {{ $employee->is_active ? 'აქტიური' : 'დამალული' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-table__actions">
                                        <a href="{{ route('admin.company.employees.edit', $employee) }}" class="admin-icon-btn" title="რედაქტირება" aria-label="რედაქტირება">
                                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route('admin.company.employees.destroy', $employee) }}"
                                            data-confirm="ნამდვილად გსურთ „{{ $employee->fullName() }}“-ის წაშლა?"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-icon-btn admin-icon-btn--danger" title="წაშლა" aria-label="წაშლა">
                                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16"/><path d="M10 11v6M14 11v6"/><path d="M5 7l1 13h12l1-13"/><path d="M9 7V4h6v3"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="admin-card" id="employee-form">
        <h2 class="admin-card__title">ახალი თანამშრომლის დამატება</h2>
        <p class="admin-card__text">ერთი ადამიანი — თითოეული ველი სამივე ენაზე.</p>

        @include('admin.components.employee-form', [
            'action' => route('admin.company.employees.store'),
            'employee' => null, // ციკლის $employee ფორმაში არ უნდა გადავიდეს
            'submitLabel' => 'დამატება',
        ])
    </section>

    <section class="admin-card" id="partners">
        <div class="admin-card__head">
            <div>
                <h2 class="admin-card__title">პარტნიორები <span class="admin-count">{{ $partners->count() }}</span></h2>
                <p class="admin-card__text">პარტნიორი კომპანიების ლოგოები. ბმულის მითითებისას ლოგოზე დაჭერით გაიხსნება მათი საიტი.</p>
            </div>
        </div>

        @if ($partners->isEmpty())
            <p class="admin-empty-line">პარტნიორები ჯერ არ არის დამატებული.</p>
        @else
            <ul class="admin-partners">
                @foreach ($partners as $partner)
                    <li @class(['admin-partner', 'is-hidden' => ! $partner->is_active])>
                        @if ($partner->url)
                            <a href="{{ $partner->url }}" target="_blank" rel="noopener noreferrer" class="admin-partner__logo" title="{{ $partner->displayUrl() }}">
                                <img src="{{ $partner->logoUrl() }}" alt="{{ $partner->displayUrl() }}" loading="lazy">
                            </a>
                        @else
                            <div class="admin-partner__logo">
                                <img src="{{ $partner->logoUrl() }}" alt="" loading="lazy">
                            </div>
                        @endif

                        <div class="admin-partner__meta">
                            <span @class(['admin-badge', 'admin-badge--on' => $partner->is_active])>
                                {{ $partner->is_active ? 'აქტიური' : 'დამალული' }}
                            </span>
                            <span class="admin-partner__order" title="რიგი">#{{ $partner->sort_order }}</span>

                            <div class="admin-table__actions">
                                <a href="{{ route('admin.company.partners.edit', $partner) }}" class="admin-icon-btn" title="რედაქტირება" aria-label="რედაქტირება">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                </a>
                                <form
                                    method="POST"
                                    action="{{ route('admin.company.partners.destroy', $partner) }}"
                                    data-confirm="ნამდვილად გსურთ ამ პარტნიორის წაშლა?"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-icon-btn admin-icon-btn--danger" title="წაშლა" aria-label="წაშლა">
                                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16"/><path d="M10 11v6M14 11v6"/><path d="M5 7l1 13h12l1-13"/><path d="M9 7V4h6v3"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        <h3 class="admin-card__subtitle">ახალი პარტნიორის დამატება</h3>

        @include('admin.components.partner-form', [
            'action' => route('admin.company.partners.store'),
            'partner' => null, // ციკლის $partner ფორმაში არ უნდა გადავიდეს
            'submitLabel' => 'დამატება',
        ])
    </section>
@endsection
