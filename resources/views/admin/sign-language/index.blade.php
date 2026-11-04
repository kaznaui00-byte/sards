
@extends('Layout.app')

@section('title', 'سرد')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 text-[var(--site-text)] sm:px-6">

    {{-- HERO --}}
    <section class="relative mb-8 overflow-hidden rounded-3xl text-white border border-[#b9a38d]/20 bg-gradient-to-br from-[#29272b] via-[#3a3631] to-zinc-950 p-7 shadow-xl sm:p-10">

        <div class="relative z-10 max-w-2xl">
            <span class="mb-4 inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm">
                ✨ A space for every voice
            </span>

            <h1 class="mb-4 text-4xl font-extrabold leading-tight sm:text-5xl">
                سرد
            </h1>

            <h2 class="mb-3 text-2xl font-bold sm:text-3xl">
                للكلمات صوت، وللأصوات حكاية.
            </h2>

            <p class="mb-7 max-w-xl leading-8 text-[#e7e2d9]">
                مساحة تجمع الاقتباسات المكتوبة والحلقات الصوتية،
                وتجعل المحتوى متاحًا للجميع بطرق مختلفة.
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="#written-posts"
                   class="rounded-xl bg-white px-5 py-3 font-bold text-[#24241f] transition hover:bg-[var(--site-soft)]">
                    استكشف الاقتباسات
                </a>

                <a href="#audio-posts"
                   class="rounded-xl border border-white/40 bg-white/10 px-5 py-3 font-bold text-white transition hover:bg-white/20">
                    🎧 استمع للحلقات
                </a>
            </div>
        </div>

        <div class="pointer-events-none absolute -bottom-20 -right-10 h-72 w-72 rounded-full bg-[#b9a38d]/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-10 -top-16 h-48 w-48 rounded-full bg-[#b9a38d]/20 blur-3xl"></div>

    </section>


    {{-- ACCESSIBILITY FEATURES --}}
    <section class="mb-9 grid grid-cols-1 gap-3 sm:grid-cols-3">

        <div class="rounded-2xl border border-[var(--site-border)] bg-[var(--site-surface)] p-4">
            <div class="mb-2 text-2xl">🔊</div>
            <h3 class="font-bold">القراءة الصوتية</h3>
            <p class="mt-1 text-sm text-gray-500">
                استمع إلى النصوص المكتوبة بدل قراءتها.
            </p>
        </div>

        <div class="rounded-2xl border border-[var(--site-border)] bg-[var(--site-surface)] p-4">
            <div class="mb-2 text-2xl">🤟</div>
            <h3 class="font-bold">لغة الإشارة</h3>
            <p class="mt-1 text-sm text-gray-500">
                شاهد ترجمة المنشورات عند توفر الفيديو.
            </p>
        </div>

        <div class="rounded-2xl border border-[var(--site-border)] bg-[var(--site-surface)] p-4">
            <div class="mb-2 text-2xl">🎧</div>
            <h3 class="font-bold">المحتوى الصوتي</h3>
            <p class="mt-1 text-sm text-gray-500">
                استمع إلى الحلقات والتسجيلات الصوتية.
            </p>
        </div>

    </section>


    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div class="mb-5 rounded-xl border border-green-500/30 bg-green-500/10 p-4 text-green-300"
             role="status">
            {{ session('success') }}
        </div>
    @endif


    {{-- ERROR MESSAGES --}}
    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-red-300"
             role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif


    {{-- PUBLISH SECTION --}}
    @auth

        <section id="publish-posts" class="mb-10">

            <div class="mb-5">
                <p class="text-sm font-semibold text-[var(--site-accent)]">SHARE YOUR VOICE</p>
                <h2 class="mt-1 text-2xl font-bold">شارك محتواك</h2>
                <p class="mt-1 text-sm text-gray-500">
                    اختر الطريقة التي تناسبك.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                {{-- TEXT FORM --}}
                <div id="publish-text"
                     class="rounded-2xl border border-[var(--site-border)] bg-[var(--site-surface)] p-5 shadow-lg">

                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#b9a38d]/20 text-2xl">
                            ✍️
                        </div>

                        <div>
                            <h3 class="font-bold">منشور كتابي</h3>
                            <p class="text-xs text-gray-500">Write a quote</p>
                        </div>
                    </div>

                    <form action="{{ route('quotes.store') }}"
                          method="POST"
                          class="space-y-4">

                        @csrf

                        <input type="hidden" name="post_type" value="text">

                        <textarea
                            name="text"
                            minlength="3"
                            maxlength="155"
                            required
                            placeholder="اكتب اقتباسك هنا..."
                            class="w-full rounded-xl border border-[var(--site-border)] bg-[var(--site-surface)] p-4 text-[var(--site-text)] outline-none transition placeholder:text-gray-500 focus:border-[#b9a38d]"
                            rows="5"
                        >{{ old('post_type') === 'text' ? old('text') : '' }}</textarea>

                        <button type="submit"
                                class="w-full rounded-xl bg-[#29272b] px-5 py-3 font-bold text-white transition hover:bg-[#3d3a35]">
                            نشر الاقتباس
                        </button>

                    </form>
                </div>


                {{-- AUDIO FORM --}}
                <div id="publish-audio"
                     class="rounded-2xl border border-[var(--site-border)] bg-[var(--site-surface)] p-5 shadow-lg">

                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#b9a38d]/20 text-2xl">
                            🎙️
                        </div>

                        <div>
                            <h3 class="font-bold">حلقة صوتية</h3>
                            <p class="text-xs text-gray-500">Publish an audio episode</p>
                        </div>
                    </div>

                    <form action="{{ route('quotes.store') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="space-y-4">

                        @csrf

                        <input type="hidden" name="post_type" value="audio">

                        <input
                            type="text"
                            name="title"
                            maxlength="100"
                            required
                            placeholder="عنوان الحلقة الصوتية..."
                            value="{{ old('post_type') === 'audio' ? old('title') : '' }}"
                            class="w-full rounded-xl border border-[var(--site-border)] bg-[var(--site-surface)] p-3 text-[var(--site-text)] outline-none placeholder:text-gray-500 focus:border-[#b9a38d]"
                        >

                        <input
                            id="quote-audio-input"
                            type="file"
                            name="audio"
                            accept="audio/*"
                            class="sr-only"
                            onchange="document.getElementById('audio-file-name').textContent = this.files[0]?.name ?? 'لم يتم اختيار ملف';"
                        >

                        <label for="quote-audio-input"
                               class="block cursor-pointer rounded-xl border border-dashed border-[#b9a38d]/50 bg-[#b9a38d]/10 p-4 text-center transition hover:bg-[#b9a38d]/20">
                            <span class="text-xl">📁</span>
                            <span class="mt-2 block font-semibold">
                                اختر ملفًا صوتيًا
                            </span>
                            <span id="audio-file-name"
                                  class="mt-1 block text-xs text-gray-500">
                                لم يتم اختيار ملف
                            </span>
                        </label>

                        <div class="flex gap-2">
                            <button type="button"
                                    id="record-audio"
                                    class="flex-1 rounded-xl bg-[#29272b] px-3 py-3 text-sm font-bold text-white hover:bg-[#3d3a35]">
                                🎙️ تسجيل
                            </button>

                            <button type="button"
                                    id="stop-audio"
                                    class="flex-1 rounded-xl border border-[var(--site-border)] bg-[var(--site-soft)] px-3 py-3 text-sm font-bold hover:bg-[var(--site-soft)]"
                                    disabled>
                                ⏹ إيقاف
                            </button>
                        </div>

                        <p id="audio-status"
                           class="text-xs text-gray-500"
                           role="status"
                           aria-live="polite">
                            سجّل صوتك أو اختر ملفًا صوتيًا.
                        </p>

                        <audio id="audio-preview"
                               controls
                               hidden
                               class="w-full">
                        </audio>

                        <p class="text-xs text-gray-500">
                            الحد الأقصى لحجم الملف 4 ميجابايت.
                        </p>

                        <button type="submit"
                                class="w-full rounded-xl bg-[#29272b] px-5 py-3 font-bold text-white transition hover:bg-[#3d3a35]">
                            نشر الحلقة الصوتية
                        </button>

                    </form>
                </div>

            </div>

        </section>

    @else

        <section class="mb-10 rounded-2xl border border-[#b9a38d]/20 bg-[#29272b]/40 p-5 text-center">
            <h2 class="text-xl font-bold">عندك كلمات أو صوت تبي تشاركه؟</h2>
            <p class="mt-2 text-sm text-gray-600">
                سجّل دخولك وابدأ مشاركة محتواك.
            </p>

            <div class="mt-4 flex justify-center gap-3">
                <a href="{{ route('login') }}"
                   class="rounded-xl bg-[#29272b] px-5 py-3 font-bold hover:bg-[#3d3a35]">
                    تسجيل الدخول
                </a>

                <a href="{{ route('signup') }}"
                   class="rounded-xl border border-[var(--site-border)] px-5 py-3 font-bold hover:bg-[var(--site-soft)]">
                    إنشاء حساب
                </a>
            </div>
        </section>

    @endauth


    {{-- SEARCH --}}
    <section class="mb-7">

        <form action="{{ route('quotes.index') }}"
              method="GET"
              class="flex gap-2">

            <input
                type="search"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="ابحث عن اقتباس أو اسم ناشر..."
                class="min-w-0 flex-1 rounded-xl border border-[var(--site-border)] bg-[var(--site-surface)] p-3 text-[var(--site-text)] outline-none placeholder:text-gray-500 focus:border-[#b9a38d]"
            >

            <button type="submit"
                    class="rounded-xl bg-[#29272b] px-5 font-bold text-white hover:bg-[#3d3a35]">
                بحث
            </button>

            @if (!empty($search))
                <a href="{{ route('quotes.index') }}"
                   class="rounded-xl border border-[var(--site-border)] px-4 py-3">
                    الكل
                </a>
            @endif

        </form>

    </section>


    {{-- LATEST POSTS --}}
    <section id="latest-posts">

        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-sm font-semibold text-[var(--site-accent)]">DISCOVER</p>
                <h2 class="mt-1 text-2xl font-bold">أحدث المنشورات</h2>
            </div>

            <span class="text-sm text-gray-500">
                كلمات وأصوات من مجتمع سرد
            </span>
        </div>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            @forelse ($quotes as $quote)

                <article class="flex flex-col rounded-2xl border border-[var(--site-border)] bg-[var(--site-surface)] p-5 shadow-lg transition duration-200 hover:-translate-y-1 hover:border-[#b9a38d]/40">

                    {{-- POST TYPE --}}
                    @if ($quote->post_type === 'audio')

                        <div class="mb-4 flex items-center gap-2">
                            <span class="rounded-lg bg-[#b9a38d]/20 px-3 py-2 text-xl">
                                🎧
                            </span>

                            <span class="text-xs font-bold uppercase tracking-wider text-[var(--site-accent)]">
                                Audio Episode
                            </span>
                        </div>

                        <h3 class="mb-4 text-xl font-bold">
                            {{ $quote->title }}
                        </h3>

                        @if ($quote->audio_path)
                            <audio controls preload="none" class="mb-4 w-full">
                                <source src="{{ \App\Support\Media::url($quote->audio_path) }}">
                                Your browser does not support audio playback.
                            </audio>
                        @else
                            <p class="mb-4 text-sm text-gray-500">
                                التسجيل الصوتي غير متوفر.
                            </p>
                        @endif

                    @else

                        <div class="mb-4 flex items-center gap-2">
                            <span class="rounded-lg bg-[#b9a38d]/20 px-3 py-2 text-xl">
                                ✍️
                            </span>

                            <span class="text-xs font-bold uppercase tracking-wider text-[var(--site-accent)]">
                                Written Quote
                            </span>
                        </div>

                        <p class="mb-5 min-h-16 text-xl leading-9">
                            {{ $quote->text }}
                        </p>

                        <div class="mb-4 flex flex-wrap gap-2">

                            <button
                                type="button"
                                class="quote-speak rounded-lg bg-[#b9a38d]/30 px-4 py-2 text-sm font-semibold text-[var(--site-accent)] hover:bg-[#b9a38d]/50"
                                data-quote="{{ $quote->text }}"
                            >
                                🔊 استمع للنص
                            </button>

                            <button
                                type="button"
                                class="quote-stop-reading rounded-lg border border-[var(--site-border)] px-4 py-2 text-sm hover:bg-[var(--site-soft)]"
                            >
                                ⏹ إيقاف
                            </button>

                        </div>

                    @endif


                    {{-- SIGN LANGUAGE VIDEO --}}
                    @if ($quote->sign_language_video_path)

                        <div class="mb-4 rounded-xl border border-[#b9a38d]/20 bg-[#29272b]/20 p-3">

                            <h4 class="mb-3 text-sm font-bold text-[var(--site-accent)]">
                                🤟 ترجمة لغة الإشارة
                            </h4>

                            <video
                                controls
                                preload="metadata"
                                playsinline
                                class="w-full rounded-lg"
                                aria-label="Sign language translation"
                            >
                                <source
                                    src="{{ \App\Support\Media::url($quote->sign_language_video_path) }}"
                                >
                                Your browser does not support video playback.
                            </video>

                        </div>

                    @endif


                    {{-- POST INFORMATION --}}
                    <div class="mt-auto border-t border-[var(--site-border)] pt-4">

                        <div class="mb-4 flex items-center justify-between gap-2 text-xs text-gray-500">

                            <span>
                                نشر بواسطة {{ $quote->user->name ?? 'مستخدم' }}
                            </span>

                            <span>
                                {{ $quote->created_at->format('Y-m-d') }}
                            </span>

                        </div>

                        <p class="mb-4 text-sm font-semibold text-gray-600">
                            ❤️ {{ $quote->likes()->count() }} إعجاب
                        </p>


                        @auth

                            @php
                                $liked = $quote->likes()
                                    ->where('user_id', auth()->id())
                                    ->exists();

                                $favorited = \App\Models\Favorite::where(
                                    'user_id',
                                    auth()->id()
                                )
                                ->where('quote_id', $quote->id)
                                ->exists();
                            @endphp

                            <div class="flex flex-wrap gap-2">

                                <form
                                    action="{{ route('quotes.like', $quote) }}"
                                    method="POST"
                                >
                                    @csrf

                                    <button type="submit"
                                            class="rounded-lg bg-[#b9a38d]/30 px-3 py-2 text-sm font-semibold hover:bg-[#b9a38d]/50">
                                        {{ $liked ? '💔 إلغاء الإعجاب' : '❤️ أعجبني' }}
                                    </button>
                                </form>


                                <form
                                    action="{{ route('quotes.favorite', $quote) }}"
                                    method="POST"
                                >
                                    @csrf

                                    <button type="submit"
                                            class="rounded-lg border border-[var(--site-border)] px-3 py-2 text-sm hover:bg-[var(--site-soft)]">
                                        {{ $favorited ? '⭐ إزالة من المفضلة' : '☆ أضف للمفضلة' }}
                                    </button>
                                </form>


                                @if (auth()->id() === $quote->user_id)

                                    @if ($quote->post_type !== 'audio')
                                        <a
                                            href="{{ route('quotes.edit', $quote) }}"
                                            class="rounded-lg border border-[var(--site-border)] px-3 py-2 text-sm hover:bg-[var(--site-soft)]"
                                        >
                                            تعديل
                                        </a>
                                    @endif

                                    <form
                                        action="{{ route('quotes.destroy', $quote) }}"
                                        method="POST"
                                        onsubmit="return confirm('هل تريد حذف المنشور؟')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="rounded-lg border border-red-500/30 px-3 py-2 text-sm text-red-700 hover:bg-red-500/10">
                                            حذف
                                        </button>
                                    </form>

                                @endif

                            </div>

                        @endauth

                    </div>

                </article>

            @empty

                <div class="col-span-full rounded-2xl border border-[var(--site-border)] bg-[var(--site-surface)] p-8 text-center">

                    <div class="mb-3 text-4xl">🌱</div>

                    <h3 class="font-bold">
                        لا توجد منشورات حتى الآن
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        كن أول من يشارك اقتباسًا أو حلقة صوتية!
                    </p>

                </div>

            @endforelse

        </div>

    </section>

    {{-- FOOTER --}}
    <footer class="mt-12 border-t border-[var(--site-border)] py-6 text-center text-sm text-gray-500">
        سرد © {{ date('Y') }}
        <p class="mt-1">Words to read. Voices to hear.</p>
    </footer>

</div>
@endsection
