@extends('Layout.app')

@section('title', 'Creator Profile | QuoteIt')

@section('content')

<div class="container mx-auto max-w-4xl px-4 py-10">

    <a href="{{ route('creators.index') }}"
       class="mb-8 inline-block text-purple-600">
        ← العودة إلى المبدعين
    </a>

    <section class="rounded-2xl border p-7">

        <div class="flex flex-col items-center gap-4 text-center sm:flex-row sm:text-right">

            <img
                src="{{ $user->avatar
                    ? asset('storage/' . $user->avatar)
                    : asset('images/profile.jpeg') }}"
                alt="Creator profile"
                class="h-28 w-28 rounded-full border object-cover"
            >

            <div>
                <h1 class="text-3xl font-bold">
                    {{ $user->nickname ?: $user->name }}
                </h1>

                <p class="mt-3 leading-8 text-gray-500">
                    {{ $user->creator_bio ?: 'مبدع في مجتمع QuoteIt.' }}
                </p>
            </div>

        </div>

        <hr class="my-6">

        <h2 class="mb-4 text-xl font-bold">
            التخصصات
        </h2>

        @php
            $labels = [
                'writer' => '✍️ كاتب ومؤلف',
                'novelist' => '📚 كاتب روايات',
                'storyteller' => '📖 راوي قصص',
                'podcaster' => '🎙️ صانع بودكاست',
                'quote_creator' => '💭 كاتب اقتباسات',
                'sign_language_creator' => '🤟 صانع محتوى بلغة الإشارة',
                'accessible_content_creator' => '♿ صانع محتوى ميسّر',
                'artist' => '🎨 فنان ومبدع',
            ];
        @endphp

        <div class="flex flex-wrap gap-2">

            @foreach (($user->creator_specialties ?? []) as $specialty)

                <span class="rounded-full bg-purple-100 px-4 py-2 text-sm text-purple-800">
                    {{ $labels[$specialty] ?? $specialty }}
                </span>

            @endforeach

        </div>

    </section>


    <section class="mt-10">

        <h2 class="mb-5 text-2xl font-bold">
            أعمال {{ $user->nickname ?: $user->name }}
        </h2>

        <div class="space-y-4">

            @forelse ($user->quotes as $quote)

                <article class="rounded-2xl border p-5">

                    @if ($quote->post_type === 'audio')

                        <h3 class="mb-3 font-bold">
                            🎧 {{ $quote->title }}
                        </h3>

                        @if ($quote->audio_path)
                            <audio controls preload="none" class="w-full">
                                <source src="{{ asset('storage/' . $quote->audio_path) }}">
                            </audio>
                        @endif

                    @else

                        <p class="text-lg leading-8">
                            {{ $quote->text }}
                        </p>

                    @endif

                    @if ($quote->sign_language_video_path)

                        <div class="mt-4">
                            <h3 class="mb-2 font-semibold">
                                🤟 ترجمة لغة الإشارة
                            </h3>

                            <video controls playsinline class="w-full rounded-xl">
                                <source src="{{ asset('storage/' . $quote->sign_language_video_path) }}">
                            </video>
                        </div>

                    @endif

                    <p class="mt-4 text-sm text-gray-500">
                        {{ $quote->created_at->format('Y-m-d') }}
                    </p>

                </article>

            @empty

                <p class="rounded-xl border p-5 text-gray-500">
                    لم يشارك هذا المبدع أعمالًا بعد.
                </p>

            @endforelse

        </div>

    </section>

</div>

@endsection
