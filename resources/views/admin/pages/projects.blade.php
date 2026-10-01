@extends('admin.layouts.app')

@section('title', 'პროექტები')

@section('content')
    <section class="admin-card">
        <div class="admin-card__head">
            <div>
                <h2 class="admin-card__title">შესრულებული პროექტები <span class="admin-count">{{ $projects->count() }}</span></h2>
                <p class="admin-card__text">პროექტები, რომლებიც საიტის „პროექტების“ გვერდზე ჩანს.</p>
            </div>
            <a href="#project-form" class="admin-btn">+ დამატება</a>
        </div>

        @if ($projects->isEmpty())
            <p class="admin-empty-line">პროექტები ჯერ არ არის დამატებული.</p>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>სურათი</th>
                            <th>სათაური</th>
                            <th>ტიპი · წელი</th>
                            <th>გალერეა</th>
                            <th>რიგი</th>
                            <th>სტატუსი</th>
                            <th class="admin-table__actions-head">მოქმედება</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td><img src="{{ $project->coverUrl() }}" alt="" class="admin-table__photo admin-table__photo--wide" loading="lazy"></td>
                                <td>
                                    <strong>{{ $project->translate('title') }}</strong>
                                    <small class="admin-table__sub">{{ $project->translate('location') ?? '—' }}</small>
                                </td>
                                <td>{{ $project->translate('category') }}@if ($project->year) · {{ $project->year }}@endif</td>
                                <td class="admin-table__nowrap">
                                    {{ $project->images_count }} ფოტო@if ($project->videos_count) · {{ $project->videos_count }} ვიდეო@endif
                                </td>
                                <td>{{ $project->sort_order }}</td>
                                <td>
                                    <span @class(['admin-badge', 'admin-badge--on' => $project->is_active])>
                                        {{ $project->is_active ? 'აქტიური' : 'დამალული' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-table__actions">
                                        @if ($project->is_active)
                                            <a href="{{ route('projects.show', $project->slug) }}" target="_blank" rel="noopener" class="admin-icon-btn" title="საიტზე ნახვა" aria-label="საიტზე ნახვა">
                                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4h6v6"/><path d="M20 4 10 14"/><path d="M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/></svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.projects.items.edit', $project) }}" class="admin-icon-btn" title="რედაქტირება" aria-label="რედაქტირება">
                                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route('admin.projects.items.destroy', $project) }}"
                                            data-confirm="ნამდვილად გსურთ „{{ $project->translate('title') }}“ პროექტის წაშლა? მისი სურათები და ვიდეოებიც წაიშლება."
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

    <section class="admin-card" id="project-form">
        <h2 class="admin-card__title">ახალი პროექტის დამატება</h2>
        <p class="admin-card__text">ინფორმაცია სამივე ენაზე, მთავარი სურათი და გალერეა (სურათები და ვიდეოები).</p>

        @include('admin.components.project-form', [
            'action' => route('admin.projects.items.store'),
            'project' => null, // ციკლის $project ფორმაში არ უნდა გადავიდეს
            'submitLabel' => 'დამატება',
        ])
    </section>
@endsection
