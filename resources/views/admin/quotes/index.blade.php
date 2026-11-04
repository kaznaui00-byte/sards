@extends('Layout.app')

@section('title', 'المنشورات | لوحة التحكم')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
    @include('admin.partials.nav')

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.quotes.index') }}" class="flex flex-1 flex-wrap gap-2">
            <input type="search" name="search" value="{{ $search }}" placeholder="ابحث في المنشورات" aria-label="ابحث في المنشورات"
                   class="min-w-0 flex-1 basis-48 rounded-lg border border-[#e9e7e4] bg-white px-3 py-2.5">
            <select name="type" aria-label="نوع المنشور" class="rounded-lg border border-[#e9e7e4] bg-white px-3 py-2.5">
                <option value="">الكل</option>
                <option value="text" @selected($type === 'text')>اقتباسات</option>
                <option value="audio" @selected($type === 'audio')>بودكاست</option>
            </select>
            <button type="submit" class="btn-secondary">بحث</button>
        </form>

        <a href="{{ route('admin.quotes.create') }}" class="btn-primary">إضافة اقتباس</a>
    </div>

    <ul class="space-y-3">
        @forelse ($quotes as $quote)
            <li class="rounded-xl border border-[#e9e7e4] bg-white p-4">
                <p class="text-xs font-bold text-[#a58c70]">{{ $quote->post_type === 'audio' ? 'بودكاست' : 'اقتباس' }}</p>
                <p class="mt-1 font-bold leading-8">{{ $quote->text ?: $quote->title }}</p>

                @if ($quote->attribution)
                    <p class="text-sm text-[#77747a]">{{ $quote->attribution }}</p>
                @endif

                <p class="mt-2 text-sm text-[#77747a]">
                    {{ $quote->user?->name ?? 'حساب محذوف' }} ·
                    {{ $quote->created_at?->format('Y/m/d') }} ·
                    {{ $quote->likes_count }} إعجاب ·
                    {{ $quote->reposts_count }} إعادة نشر
                </p>

                <div class="mt-3 flex flex-wrap gap-2">
                    <a href="{{ route('admin.quotes.edit', $quote) }}" class="btn-secondary">تعديل</a>
                    <form method="POST" action="{{ route('admin.quotes.destroy', $quote) }}" onsubmit="return confirm('هل تريد حذف هذا المنشور نهائيًا؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary !text-red-700">حذف</button>
                    </form>
                </div>
            </li>
        @empty
            <li class="rounded-xl border border-[#e9e7e4] bg-white p-6 text-center text-[#77747a]">لا توجد نتائج.</li>
        @endforelse
    </ul>

    <div class="mt-6">{{ $quotes->links() }}</div>
</div>
@endsection
