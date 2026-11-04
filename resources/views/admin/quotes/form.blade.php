@extends('Layout.app')

@section('title', ($quote->exists ? 'تعديل منشور' : 'إضافة اقتباس').' | لوحة التحكم')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
    @include('admin.partials.nav')

    <form method="POST"
          action="{{ $quote->exists ? route('admin.quotes.update', $quote) : route('admin.quotes.store') }}"
          class="max-w-2xl space-y-4 rounded-xl border border-[#e9e7e4] bg-white p-5">
        @csrf
        @if ($quote->exists)
            @method('PUT')
        @endif

        <h2 class="text-xl font-bold">{{ $quote->exists ? 'تعديل المنشور' : 'إضافة اقتباس' }}</h2>

        @if ($quote->post_type === 'audio')
            <div>
                <label for="title" class="mb-1 block font-bold">عنوان الحلقة</label>
                <input id="title" name="title" value="{{ old('title', $quote->title) }}" maxlength="100" required
                       class="w-full rounded-lg border border-[#e9e7e4] bg-white px-3 py-2.5">
                @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        @else
            <div>
                <label for="text" class="mb-1 block font-bold">نص الاقتباس</label>
                <textarea id="text" name="text" rows="4" minlength="3" maxlength="155" required
                          class="w-full rounded-lg border border-[#e9e7e4] bg-white px-3 py-2.5">{{ old('text', $quote->text) }}</textarea>
                @error('text') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="attribution" class="mb-1 block font-bold">قائل الاقتباس</label>
                <input id="attribution" name="attribution" value="{{ old('attribution', $quote->attribution) }}" maxlength="100"
                       class="w-full rounded-lg border border-[#e9e7e4] bg-white px-3 py-2.5">
                @error('attribution') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        @endif

        <div class="flex flex-wrap gap-2">
            <button type="submit" class="btn-primary">حفظ</button>
            <a href="{{ route('admin.quotes.index') }}" class="btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
