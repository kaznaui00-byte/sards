@extends('Layout.app')

@section('title', $novel->title.' | سرد')

@section('content')

@php
    $canManageNovel = auth()->check() && (auth()->id() === $novel->user_id || auth()->user()->is_admin);
@endphp

<div dir="rtl" style="background:#faf9f6; min-height:100vh; padding:40px 20px; color:#29252c;">
    <div style="max-width:1000px; margin:auto;">

        <a href="{{ route('novels.index') }}"
           style="display:inline-block; margin-bottom:25px; color:#70518b; text-decoration:none;">
            ← العودة إلى الروايات
        </a>

        @if (session('success'))
            <p role="status" class="mb-4 rounded-lg bg-green-50 p-3 text-sm font-bold text-green-800">{{ session('success') }}</p>
        @endif

        {{-- غلاف الرواية ومعلوماتها --}}
        <div class="novel-summary" style="background:#fff; border:1px solid #eee7f1; border-radius:18px; padding:25px;">

            <div>
                <span style="color:#806a91; font-size:13px; font-weight:bold;">
                    {{ $novel->genre }}
                </span>

                <h1 style="font-size:32px; font-weight:800; margin:12px 0;">
                    {{ $novel->title }}
                </h1>

                <p style="color:#777; margin-bottom:15px;">
                    الكاتب: {{ $novel->user->nickname ?: $novel->user->name }}
                </p>

                <p style="line-height:2; color:#625d66;">
                    {{ $novel->description ?: 'لا توجد نبذة عن هذه الرواية.' }}
                </p>

                <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:20px;">
                    <span style="background:#f1edf5; padding:8px 13px; border-radius:20px; font-size:13px;">
                        {{ $novel->length_type === 'short' ? 'رواية قصيرة' : 'رواية طويلة' }}
                    </span>

                    <span style="background:#f1edf5; padding:8px 13px; border-radius:20px; font-size:13px;">
                        {{ $novel->status === 'completed' ? 'مكتملة' : 'مستمرة' }}
                    </span>

                    <span style="background:#f1edf5; padding:8px 13px; border-radius:20px; font-size:13px;">
                        {{ $novel->chapters->count() }} فصل
                    </span>
                </div>
            </div>

            <div>
                @if ($novel->cover_image_path)
                    <img src="{{ \App\Support\Media::url($novel->cover_image_path) }}"
                         alt="غلاف {{ $novel->title }}"
                         style="width:100%; border-radius:14px; display:block;">
                @else
                    <div style="aspect-ratio:3/4; border-radius:14px; background:#f3eff6; display:grid; place-items:center; font-size:48px;" aria-hidden="true">📖</div>
                @endif
            </div>
        </div>

        {{-- الفصول --}}
        <section style="margin-top:30px;">
            <h2 style="font-size:22px; font-weight:800; margin-bottom:15px;">الفصول</h2>

            <ul style="display:grid; gap:10px; list-style:none; margin:0; padding:0;">
                @forelse ($novel->chapters as $chapter)
                    <li style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:10px; background:#fff; border:1px solid #eee7f1; border-radius:14px; padding:14px 18px;">
                        <a href="{{ route('novels.chapters.show', [$novel, $chapter]) }}" style="font-weight:700; color:#29252c; text-decoration:none;">
                            الفصل {{ $chapter->chapter_number }}: {{ $chapter->title }}
                        </a>

                        @if ($canManageNovel)
                            <form method="POST" action="{{ route('novels.chapters.destroy', [$novel, $chapter]) }}" onsubmit="return confirm('هل تريد حذف هذا الفصل؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-secondary !text-red-700">حذف</button>
                            </form>
                        @endif
                    </li>
                @empty
                    <li style="background:#fff; border:1px solid #eee7f1; border-radius:14px; padding:25px; text-align:center; color:#89828d;">
                        لم تُنشر فصول بعد.
                    </li>
                @endforelse
            </ul>
        </section>

        @if ($canManageNovel)
            <details style="margin-top:25px; background:#fff; border:1px solid #eee7f1; border-radius:14px; padding:18px;" @if ($errors->any()) open @endif>
                <summary style="cursor:pointer; font-weight:800;">＋ إضافة فصل جديد</summary>

                <form method="POST" action="{{ route('novels.chapters.store', $novel) }}" class="mt-4 space-y-3">
                    @csrf

                    <div>
                        <label for="chapter-title" class="mb-1 block font-bold">عنوان الفصل</label>
                        <input id="chapter-title" name="title" value="{{ old('title') }}" maxlength="150" required class="w-full rounded-lg border border-[#e9e7e4] bg-white px-3 py-2.5">
                        @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="chapter-content" class="mb-1 block font-bold">نص الفصل</label>
                        <textarea id="chapter-content" name="content" rows="10" required class="w-full rounded-lg border border-[#e9e7e4] bg-white px-3 py-2.5">{{ old('content') }}</textarea>
                        @error('content') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn-primary">نشر الفصل</button>
                </form>
            </details>

            <form method="POST" action="{{ route('novels.destroy', $novel) }}" class="mt-4" onsubmit="return confirm('سيتم حذف الرواية وكل فصولها نهائيًا. هل تريد المتابعة؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-secondary !text-red-700">حذف الرواية</button>
            </form>
        @endif
    </div>
</div>

<style>
    .novel-summary {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 220px;
        gap: 30px;
        align-items: start;
    }

    @media (max-width: 700px) {
        .novel-summary {
            grid-template-columns: 1fr;
        }
    }
</style>

@endsection
