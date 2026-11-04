@extends('Layout.app')

@section('title', 'المظهر | لوحة التحكم')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
    @include('admin.partials.nav')

    <form method="POST" action="{{ route('admin.appearance.update') }}" class="max-w-2xl space-y-4 rounded-xl border border-[var(--site-border)] bg-white p-5">
        @csrf
        @method('PUT')

        <h2 class="text-xl font-bold">مظهر الموقع</h2>

        <div class="grid gap-4 sm:grid-cols-2">
            @foreach (['primary_color' => 'اللون الأساسي', 'accent_color' => 'لون التمييز', 'background_color' => 'لون الخلفية', 'text_color' => 'لون النص'] as $colorKey => $colorLabel)
                <div>
                    <label for="{{ $colorKey }}" class="mb-1 block font-bold">{{ $colorLabel }}</label>
                    <input id="{{ $colorKey }}" type="color" name="{{ $colorKey }}" value="{{ old($colorKey, $appearance[$colorKey]) }}" class="h-11 w-full rounded-lg border border-[var(--site-border)] bg-white p-1">
                    @error($colorKey) <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
            @endforeach

            <div>
                <label for="font_family" class="mb-1 block font-bold">الخط</label>
                <select id="font_family" name="font_family" class="w-full rounded-lg border border-[var(--site-border)] bg-white px-3 py-2.5">
                    @foreach (['Tahoma', 'Arial', 'Georgia', 'Instrument Sans'] as $fontOption)
                        <option value="{{ $fontOption }}" @selected(old('font_family', $appearance['font_family']) === $fontOption)>{{ $fontOption }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="content_width" class="mb-1 block font-bold">عرض المحتوى</label>
                <select id="content_width" name="content_width" class="w-full rounded-lg border border-[var(--site-border)] bg-white px-3 py-2.5">
                    @foreach (['960', '1100', '1200', '1320'] as $widthOption)
                        <option value="{{ $widthOption }}" @selected(old('content_width', $appearance['content_width']) === $widthOption)>{{ $widthOption }}px</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="card_radius" class="mb-1 block font-bold">استدارة البطاقات</label>
                <select id="card_radius" name="card_radius" class="w-full rounded-lg border border-[var(--site-border)] bg-white px-3 py-2.5">
                    @foreach (['0', '8', '14', '20', '28'] as $radiusOption)
                        <option value="{{ $radiusOption }}" @selected(old('card_radius', $appearance['card_radius']) === $radiusOption)>{{ $radiusOption }}px</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn-primary">حفظ المظهر</button>
    </form>

    <form method="POST" action="{{ route('admin.appearance.reset') }}" class="mt-3" onsubmit="return confirm('هل تريد إعادة المظهر الأصلي؟')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-secondary">استعادة المظهر الأصلي</button>
    </form>
</div>
@endsection
