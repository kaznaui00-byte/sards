@extends('Layout.app')

@section('content')

<style>
    .novels-page {
        direction: rtl;
        color: var(--site-text);
        background: var(--site-bg);
        min-height: 100vh;
        padding: 45px 20px;
    }

    .novels-container {
        max-width: 1150px;
        margin: auto;
    }

    .novels-heading {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 18px;
    }

    .novels-icon {
        width: 65px;
        height: 65px;
        border-radius: 18px;
        background: #e7e2d9;
        color: #94745b;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .novels-page h1 {
        font-size: 36px;
        font-weight: 800;
        margin: 0 0 8px;
    }

    .novels-description {
        color: var(--site-muted);
        font-size: 16px;
        line-height: 2;
        max-width: 650px;
    }

    .novels-categories {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin: 35px 0;
        padding-bottom: 22px;
        border-bottom: 1px solid var(--site-border);
    }

    .novels-category {
        padding: 10px 20px;
        border: 1px solid var(--site-border);
        border-radius: 30px;
        color: var(--site-muted);
        font-size: 14px;
    }

    .novels-category.active {
        background: #24241f;
        color: white;
        border-color: #24241f;
    }

    .novels-section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
        gap: 10px;
    }

    .novels-section-heading h2 {
        font-size: 23px;
        font-weight: 800;
    }

    .novels-section-heading span {
        color: var(--site-muted);
        font-size: 13px;
    }

    .novels-empty {
        background: var(--site-surface);
        border: 1px solid var(--site-border);
        border-radius: 20px;
        padding: 65px 20px;
        text-align: center;
    }

    .novels-empty-icon {
        width: 75px;
        height: 75px;
        background: var(--site-soft);
        color: #94745b;
        border-radius: 22px;
        display: grid;
        place-items: center;
        margin: 0 auto 20px;
    }

    .novels-empty h3 {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .novels-empty p {
        color: var(--site-muted);
        font-size: 14px;
        line-height: 2;
    }

    @media (max-width: 600px) {
        .novels-page {
            padding: 30px 15px;
        }

        .novels-page h1 {
            font-size: 28px;
        }
    }
</style>

<div class="novels-page">
    <div class="novels-container">

        <div class="novels-heading">
            <div class="novels-icon">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.6">
                    <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z"/>
                    <path d="M4 6v16m4-14h8m-8 4h8" stroke-linecap="round"/>
                </svg>
            </div>

            <div>
                <h1>الروايات</h1>
                <div style="color:#94745b; font-size:13px; font-weight:600;">
                    سرد · عالم الأدب
                </div>
            </div>
        </div>

        <p class="novels-description">
            لكل رواية عالم، ولكل كاتب حكاية.
            اكتشف الأعمال الأدبية، وتعرّف على عوالم جديدة
            يشاركها المبدعون معك.
        </p>

        @if (session('success'))
            <p role="status" class="mb-4 rounded-lg bg-green-50 p-3 text-sm font-bold text-green-800">{{ session('success') }}</p>
        @endif

        @php
            $novelFilters = [
                '' => 'جميع الروايات',
                'short' => 'روايات قصيرة',
                'long' => 'روايات طويلة',
                'completed' => 'روايات مكتملة',
                'ongoing' => 'روايات مستمرة',
            ];
        @endphp

        <nav class="novels-categories" aria-label="تصنيف الروايات">
            @foreach ($novelFilters as $filterKey => $filterLabel)
                <a href="{{ route('novels.index', array_filter(['filter' => $filterKey])) }}"
                   class="novels-category {{ (string) request('filter') === (string) $filterKey ? 'active' : '' }}"
                   style="text-decoration:none;">{{ $filterLabel }}</a>
            @endforeach
        </nav>

        @auth
            <details class="mb-8 rounded-2xl border border-[var(--site-border)] bg-white p-5" @if ($errors->any()) open @endif>
                <summary class="cursor-pointer font-bold">＋ أضف روايتك</summary>

                <form action="{{ route('novels.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3">
                    @csrf

                    <div>
                        <label for="novel-title" class="mb-1 block font-bold">عنوان الرواية</label>
                        <input id="novel-title" name="title" value="{{ old('title') }}" maxlength="150" required class="w-full rounded-lg border border-[var(--site-border)] bg-white px-3 py-2.5">
                        @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="novel-description" class="mb-1 block font-bold">نبذة عن الرواية</label>
                        <textarea id="novel-description" name="description" rows="4" maxlength="5000" class="w-full rounded-lg border border-[var(--site-border)] bg-white px-3 py-2.5">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3">
                        <div>
                            <label for="novel-genre" class="mb-1 block font-bold">التصنيف</label>
                            <input id="novel-genre" name="genre" value="{{ old('genre', 'عام') }}" maxlength="50" required class="w-full rounded-lg border border-[var(--site-border)] bg-white px-3 py-2.5">
                            @error('genre') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="novel-length" class="mb-1 block font-bold">الطول</label>
                            <select id="novel-length" name="length_type" class="w-full rounded-lg border border-[var(--site-border)] bg-white px-3 py-2.5">
                                <option value="short" @selected(old('length_type') === 'short')>قصيرة</option>
                                <option value="long" @selected(old('length_type') === 'long')>طويلة</option>
                            </select>
                        </div>

                        <div>
                            <label for="novel-status" class="mb-1 block font-bold">الحالة</label>
                            <select id="novel-status" name="status" class="w-full rounded-lg border border-[var(--site-border)] bg-white px-3 py-2.5">
                                <option value="ongoing" @selected(old('status') === 'ongoing')>مستمرة</option>
                                <option value="completed" @selected(old('status') === 'completed')>مكتملة</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="novel-cover" class="mb-1 block font-bold">صورة الغلاف (اختياري، بحد أقصى 4 ميجابايت)</label>
                        <input id="novel-cover" type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp" class="block w-full rounded-lg border border-[var(--site-border)] bg-white p-2.5">
                        @error('cover_image') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn-primary">نشر الرواية</button>
                </form>
            </details>
        @endauth

        <div class="novels-section-heading">
            <h2>أحدث الروايات</h2>
            <span>اكتشف أحدث الأعمال الأدبية</span>
        </div>

        @if ($novels->isEmpty())
            <div class="novels-empty">
                <div class="novels-empty-icon" aria-hidden="true">📖</div>
                <h3>مساحة تنتظر رواياتكم</h3>
                <p>
                    لا توجد روايات منشورة حتى الآن.
                    ستكون هنا مساحة لاكتشاف الروايات وقراءة أعمال المبدعين.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($novels as $novel)
                    <a href="{{ route('novels.show', $novel) }}" class="block overflow-hidden rounded-2xl border border-[var(--site-border)] bg-white" style="color: var(--site-text); text-decoration:none;">
                        @if ($novel->cover_image_path)
                            <img src="{{ \App\Support\Media::url($novel->cover_image_path) }}" alt="غلاف {{ $novel->title }}" class="h-48 w-full object-cover">
                        @endif

                        <div class="p-4">
                            <p class="text-xs font-bold text-[var(--site-accent)]">{{ $novel->genre }}</p>
                            <h3 class="mt-1 text-lg font-extrabold">{{ $novel->title }}</h3>
                            <p class="mt-1 text-sm text-[var(--site-muted)]">
                                {{ $novel->user->nickname ?: $novel->user->name }} · {{ $novel->chapters_count }} فصل
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">{{ $novels->links() }}</div>
        @endif

    </div>
</div>

@endsection