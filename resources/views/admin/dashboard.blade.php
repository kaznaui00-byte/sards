@extends('Layout.app')

@section('title', 'لوحة التحكم | سرد')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
    @include('admin.partials.nav')

    <section aria-label="إحصائيات الموقع" class="grid grid-cols-2 gap-3 md:grid-cols-3">
        @foreach ($stats as $label => $value)
            <div class="rounded-xl border border-[#e9e7e4] bg-white p-4">
                <p class="text-sm text-[#77747a]">{{ $label }}</p>
                <p class="mt-1 text-3xl font-extrabold">{{ number_format($value) }}</p>
            </div>
        @endforeach
    </section>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section class="rounded-xl border border-[#e9e7e4] bg-white p-5">
            <div class="mb-3 flex items-center justify-between gap-3">
                <h2 class="text-lg font-bold">أحدث المستخدمين</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-[#a58c70]">عرض الكل</a>
            </div>

            <ul class="divide-y divide-[#e9e7e4]">
                @forelse ($latestUsers as $latestUser)
                    <li class="py-3">
                        <p class="font-bold">{{ $latestUser->name }}</p>
                        <p class="text-sm break-all text-[#77747a]">{{ $latestUser->email }}</p>
                    </li>
                @empty
                    <li class="py-3 text-[#77747a]">لا يوجد مستخدمون بعد.</li>
                @endforelse
            </ul>
        </section>

        <section class="rounded-xl border border-[#e9e7e4] bg-white p-5">
            <div class="mb-3 flex items-center justify-between gap-3">
                <h2 class="text-lg font-bold">أحدث المنشورات</h2>
                <a href="{{ route('admin.quotes.index') }}" class="text-sm font-bold text-[#a58c70]">عرض الكل</a>
            </div>

            <ul class="divide-y divide-[#e9e7e4]">
                @forelse ($latestQuotes as $latestQuote)
                    <li class="py-3">
                        <p class="font-bold">{{ $latestQuote->text ?: $latestQuote->title }}</p>
                        <p class="text-sm text-[#77747a]">{{ $latestQuote->user?->name ?? 'حساب محذوف' }}</p>
                    </li>
                @empty
                    <li class="py-3 text-[#77747a]">لا توجد منشورات بعد.</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>
@endsection
