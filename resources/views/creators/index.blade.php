@extends('Layout.app')

@section('title', 'Discover Creators | سرد')

@section('content')

<div class="container mx-auto max-w-6xl px-4 py-10">

    <header class="mb-10">
        <p class="text-sm font-semibold text-[var(--site-accent)]">
            SARD COMMUNITY
        </p>

        <h1 class="mt-3 text-3xl font-bold">
            اكتشف المبدعين
        </h1>

        <p class="mt-3 text-gray-500">
            تعرّف على أصحاب الكلمات والأصوات والمواهب المختلفة.
        </p>
    </header>

    <form action="{{ route('creators.index') }}"
          method="GET"
          class="mb-8 flex gap-2">

        <input
            type="search"
            name="search"
            value="{{ $search }}"
            placeholder="ابحث عن مبدع أو تخصص..."
            class="input w-full"
        >

        <button type="submit" class="btn-primary">
            بحث
        </button>

    </form>

    @php
        $specialties = [
            'writer' => 'كاتب ومؤلف',
            'novelist' => 'كاتب روايات',
            'storyteller' => 'راوي قصص',
            'podcaster' => 'صانع بودكاست',
            'quote_creator' => 'كاتب اقتباسات',
            'sign_language_creator' => 'صانع محتوى بلغة الإشارة',
            'accessible_content_creator' => 'صانع محتوى ميسّر',
            'artist' => 'فنان ومبدع',
        ];
    @endphp

    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-xl font-bold">المبدعون</h2>

        <span class="text-sm text-gray-500">
            {{ $creators->total() }} مبدع
        </span>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

        @forelse ($creators as $creator)

            <article class="rounded-2xl border p-5">

                <div class="flex items-center gap-4">

                    <img
                        src="{{ $creator->avatar
                            ? \App\Support\Media::url($creator->avatar)
                            : asset('images/default-avatar.svg') }}"
                        alt="Creator profile"
                        class="h-16 w-16 rounded-full object-cover"
                    >

                    <div>
                        <h3 class="font-bold">
                            {{ $creator->nickname ?: $creator->name }}
                        </h3>

                        <span class="text-sm text-gray-500">
                            مبدع في سرد
                        </span>
                    </div>

                </div>

                <p class="my-4 text-sm leading-7 text-gray-600">
                    {{ $creator->creator_bio ?: 'مبدع يشارك أعماله وإبداعاته.' }}
                </p>

                <div class="mb-5 flex flex-wrap gap-2">

                    @foreach (($creator->creator_specialties ?? []) as $specialty)

                        <span class="rounded-full bg-[var(--site-soft)] px-3 py-1 text-xs text-[var(--site-accent)]">
                            {{ $specialties[$specialty] ?? $specialty }}
                        </span>

                    @endforeach

                </div>

                <a
                    href="{{ route('creators.show', $creator) }}"
                    class="btn-primary inline-block"
                >
                    اكتشف أعماله
                </a>

            </article>

        @empty

            <div class="col-span-full rounded-2xl border p-10 text-center">

                <div class="mb-3 text-4xl">✨</div>

                <h3 class="font-bold">
                    لا يوجد مبدعون للعرض حاليًا
                </h3>

                <p class="mt-2 text-sm text-gray-500">
                    ستظهر هنا الملفات الإبداعية للأشخاص الذين اختاروا المشاركة.
                </p>

            </div>

        @endforelse

    </div>

    <div class="mt-8">
        {{ $creators->links() }}
    </div>

</div>

@endsection
