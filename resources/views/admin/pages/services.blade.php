@extends('admin.layouts.app')

@section('title', 'სერვისები')

@section('content')
    <section class="admin-card">
        <div class="admin-card__head">
            <div>
                <h2 class="admin-card__title">როგორ ვმუშაობთ — ნაბიჯები <span class="admin-count">{{ $steps->count() }}</span></h2>
                <p class="admin-card__text">სერვისების გვერდის პროცესის ნაბიჯები. ნომერი რიგის მიხედვით ავტომატურად ენიჭება.</p>
            </div>
            <a href="#step-form" class="admin-btn">+ დამატება</a>
        </div>

        @if ($steps->isEmpty())
            <p class="admin-empty-line">ნაბიჯები ჯერ არ არის დამატებული.</p>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>სურათები</th>
                            <th>სათაური</th>
                            <th>რიგი</th>
                            <th>სტატუსი</th>
                            <th class="admin-table__actions-head">მოქმედება</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($steps as $step)
                            <tr>
                                <td><span class="admin-step-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span></td>
                                <td>
                                    <div class="admin-thumbs">
                                        @foreach ($step->images->take(3) as $image)
                                            <img src="{{ $image->url() }}" alt="" class="admin-table__photo" loading="lazy">
                                        @endforeach
                                        @if ($step->images->count() > 3)
                                            <span class="admin-thumbs__more">+{{ $step->images->count() - 3 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $step->translate('title') }}</strong>
                                    <small class="admin-table__sub">{{ $step->translate('title', 'en') }} · {{ $step->translate('title', 'ru') }}</small>
                                </td>
                                <td>{{ $step->sort_order }}</td>
                                <td>
                                    <span @class(['admin-badge', 'admin-badge--on' => $step->is_active])>
                                        {{ $step->is_active ? 'აქტიური' : 'დამალული' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-table__actions">
                                        <a href="{{ route('admin.services.steps.edit', $step) }}" class="admin-icon-btn" title="რედაქტირება" aria-label="რედაქტირება">
                                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route('admin.services.steps.destroy', $step) }}"
                                            data-confirm="ნამდვილად გსურთ „{{ $step->translate('title') }}“ ნაბიჯის წაშლა? მისი სურათებიც წაიშლება."
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

    <section class="admin-card" id="step-form">
        <h2 class="admin-card__title">ახალი ნაბიჯის დამატება</h2>
        <p class="admin-card__text">სათაური, აღწერა და ერთი ან რამდენიმე სურათი — სამივე ენაზე.</p>

        @include('admin.components.service-step-form', [
            'action' => route('admin.services.steps.store'),
            'step' => null, // ციკლის $step ფორმაში არ უნდა გადავიდეს
            'submitLabel' => 'დამატება',
        ])
    </section>
@endsection
