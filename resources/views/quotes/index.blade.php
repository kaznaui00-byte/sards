@extends('Layout.app')

@section('title', 'الاقتباسات | QuoteIt')

@section('content')

<div class="qi-page">

    <div class="qi-container">

        {{-- عنوان القسم --}}
        <header class="qi-page-header">

            <div class="qi-eyebrow">QuoteIt · مساحة الكلمة</div>

            <h1 class="qi-page-title">الاقتباسات</h1>

            <p class="qi-page-description">
                كلمات تختصر شعورًا، وأفكار تبقى معنا.
                اكتشفي الاقتباسات وشاركي كلماتك مع الآخرين.
            </p>

        </header>

        {{-- البحث --}}
        <section class="qi-section">

            <form
                action="{{ route('quotes.index') }}"
                method="GET"
                class="flex flex-wrap gap-3"
            >

                <input
                    type="search"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="ابحثي عن اقتباس أو كاتب..."
                    class="qi-field"
                    style="flex:1;min-width:220px;margin:0;"
                >

                <button type="submit" class="btn-primary">
                    بحث
                </button>

                @if(!empty($search))
                    <a href="{{ route('quotes.index') }}" class="btn-secondary">
                        إلغاء البحث
                    </a>
                @endif

            </form>

        </section>

        {{-- رسائل النجاح --}}
        @if(session('success'))
            <div class="qi-quote-card mb-5" role="status">
                {{ session('success') }}
            </div>
        @endif

        {{-- أخطاء التحقق --}}
        @if($errors->any())
            <div class="qi-quote-card mb-5" role="alert">
                <strong>يرجى مراجعة البيانات:</strong>

                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- إضافة المحتوى --}}
        @auth

            <section class="qi-section">

                <div class="qi-section-header">
                    <h2 class="qi-section-title">شاركي محتواك</h2>
                </div>

                <div class="grid gap-5 md:grid-cols-2">

                    {{-- إضافة اقتباس مكتوب --}}
                    <div class="qi-form">

                        <h3 class="qi-card-title">
                            ✍️ اقتباس جديد
                        </h3>

                        <p class="qi-card-description mb-4">
                            اكتبي كلماتك وشاركيها مع مجتمع QuoteIt.
                        </p>

                        <form
                            action="{{ route('quotes.store') }}"
                            method="POST"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="post_type"
                                value="text"
                            >

                            <textarea
                                name="text"
                                class="qi-field"
                                rows="5"
                                minlength="3"
                                maxlength="155"
                                required
                                placeholder="اكتبي اقتباسك هنا..."
                            >{{ old('post_type') === 'text' ? old('text') : '' }}</textarea>

                            <label for="quote-attribution" class="label mt-3">
                                اسم قائل المقولة (اختياري)
                            </label>

                            <input
                                id="quote-attribution"
                                type="text"
                                name="attribution"
                                class="qi-field"
                                maxlength="100"
                                placeholder="مثال: غازي القصيبي"
                                value="{{ old('attribution') }}"
                            >

                            <p class="qi-meta">
                                من 3 إلى 155 حرفًا.
                            </p>

                            <button type="submit" class="btn-primary w-full">
                                نشر الاقتباس
                            </button>

                        </form>

                    </div>

                    {{-- إضافة تسجيل صوتي --}}
                    <div class="qi-form">

                        <h3 class="qi-card-title">
                            🎙️ تسجيل صوتي جديد
                        </h3>

                        <p class="qi-card-description mb-4">
                            سجلي بصوتك أو ارفعي ملفًا صوتيًا.
                        </p>

                        <form
                            action="{{ route('quotes.store') }}"
                            method="POST"
                            enctype="multipart/form-data"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="post_type"
                                value="audio"
                            >

                            <label for="audio-title" class="label">
                                عنوان التسجيل
                            </label>

                            <input
                                id="audio-title"
                                type="text"
                                name="title"
                                class="qi-field"
                                maxlength="100"
                                required
                                placeholder="عنوان الحلقة أو التسجيل..."
                                value="{{ old('post_type') === 'audio' ? old('title') : '' }}"
                            >

                            <label for="quote-audio-input" class="label">
                                الملف الصوتي
                            </label>

                            <input
                                id="quote-audio-input"
                                type="file"
                                name="audio"
                                accept="audio/*"
                                class="qi-field"
                            >

                            <div class="flex flex-wrap gap-2">

                                <button
                                    type="button"
                                    id="record-audio"
                                    class="btn-secondary"
                                >
                                    🎙️ بدء التسجيل
                                </button>

                                <button
                                    type="button"
                                    id="stop-audio"
                                    class="btn-secondary"
                                    disabled
                                >
                                    إيقاف التسجيل
                                </button>

                            </div>

                            <p
                                id="audio-status"
                                class="qi-meta"
                                role="status"
                                aria-live="polite"
                            >
                                سجلي صوتك أو اختاري ملفًا.
                            </p>

                            <audio
                                id="audio-preview"
                                controls
                                hidden
                                class="qi-audio"
                                aria-label="معاينة التسجيل"
                            ></audio>

                            <p class="qi-meta mb-4">
                                الحد الأعلى لحجم الملف 10 ميجابايت.
                            </p>

                            <button type="submit" class="btn-primary w-full">
                                نشر التسجيل
                            </button>

                        </form>

                    </div>

                </div>

            </section>

        @else

            <section class="qi-section">
                <div class="qi-empty">
                    سجلي الدخول حتى تتمكني من نشر الاقتباسات والتسجيلات الصوتية.

                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn-primary">
                            تسجيل الدخول
                        </a>
                    </div>
                </div>
            </section>

        @endauth

        {{-- المحتوى المنشور --}}
        <section class="qi-section">

            <div class="qi-section-header">

                <div>
                    <div class="qi-eyebrow">من مجتمع QuoteIt</div>

                    <h2 class="qi-section-title">
                        {{ !empty($search) ? 'نتائج البحث' : 'أحدث الاقتباسات' }}
                    </h2>
                </div>

                <span class="qi-meta">
                    {{ !empty($search) ? 'نتائج البحث عن: '.$search : 'الأحدث أولًا' }}
                </span>

            </div>

            <div class="space-y-5">

                @forelse($quotes as $quote)

                    <article class="qi-card">

                        <div class="qi-card-body">

                            {{-- التسجيل الصوتي --}}
                            @if($quote->post_type === 'audio')

                                <div class="mb-4">

                                    <div class="qi-eyebrow">
                                        بودكاست وتسجيلات صوتية
                                    </div>

                                    <h3 class="qi-card-title">
                                        🎧 {{ $quote->title }}
                                    </h3>

                                    @if($quote->audio_path)

                                        <audio
                                            controls
                                            preload="none"
                                            class="qi-audio"
                                            aria-label="تشغيل التسجيل الصوتي"
                                        >
                                            <source src="{{ asset('storage/'.$quote->audio_path) }}">
                                            متصفحك لا يدعم تشغيل الصوت.
                                        </audio>

                                    @else

                                        <p class="qi-card-description">
                                            الملف الصوتي غير متاح.
                                        </p>

                                    @endif

                                </div>

                            {{-- الاقتباس المكتوب --}}
                            @else

                                <div class="qi-eyebrow">
                                    اقتباس مكتوب
                                </div>

                                <blockquote class="qi-quote-text">
                                    “{{ $quote->text }}”
                                </blockquote>

                                <div class="flex flex-wrap gap-2 mt-4">

                                    <button
                                        type="button"
                                        class="btn-secondary quote-speak"
                                        data-quote="{{ $quote->text }}"
                                    >
                                        🔊 استمعي للاقتباس
                                    </button>

                                    <button
                                        type="button"
                                        class="btn-secondary quote-stop-reading"
                                    >
                                        ⏹ إيقاف القراءة
                                    </button>

                                </div>

                                <a
    href="{{ url('/sign-language-app/') . '#/text?q=' . urlencode($quote->text ?? '') }}"
    class="btn-secondary inline-flex items-center gap-2 mt-4"
>
    🤟 ترجمة الاقتباس بلغة الإشارة(قريباً)
</a>

                            @endif

                            <hr class="qi-divider">

                            {{-- معلومات الناشر --}}
                            <div class="mt-4 space-y-3">

                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <p class="qi-meta">
                                        نشره
                                        <strong>{{ $quote->user->name ?? 'كاتب غير معروف' }}</strong>
                                    </p>

                                    <time class="qi-meta" datetime="{{ $quote->created_at->toAtomString() }}">
                                        {{ $quote->created_at->format('Y/m/d') }}
                                    </time>
                                </div>

                                @if($quote->attribution)
                                    <p class="qi-meta">
                                        قائل المقولة:
                                        <strong>{{ $quote->attribution }}</strong>
                                    </p>
                                @endif

                                {{-- أعداد التفاعل --}}
                                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 pt-3">

                                    <div class="flex items-center gap-4">
                                        <span class="qi-meta">
                                            ❤️ {{ $quote->likes->count() }}
                                        </span>

                                        <span class="qi-meta">
                                            ↻ {{ $quote->reposts->count() }}
                                        </span>
                                    </div>

                                    @auth
                                        @php
                                            $liked = $quote->likes->contains('user_id', auth()->id());

                                            $favorited = \App\Models\Favorite::where(
                                                'user_id',
                                                auth()->id()
                                            )->where('quote_id', $quote->id)->exists();

                                            $reposted = $quote->reposts->contains('user_id', auth()->id());
                                        @endphp

                                        <div class="flex flex-wrap items-center gap-2">

                                            {{-- الإعجاب --}}
                                            <form action="{{ route('quotes.like', $quote) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-secondary">
                                                    {{ $liked ? '❤️ أعجبني' : '♡ إعجاب' }}
                                                </button>
                                            </form>

                                            {{-- إعادة النشر --}}
                                            <form action="{{ route('quotes.repost', $quote) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-secondary">
                                                    {{ $reposted ? '↩ تمت إعادة النشر' : '↻ إعادة نشر' }}
                                                </button>
                                            </form>

                                            {{-- المفضلة --}}
                                            <form action="{{ route('quotes.favorite', $quote) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-secondary">
                                                    {{ $favorited ? '★ محفوظ' : '☆ حفظ' }}
                                                </button>
                                            </form>

                                            {{-- تعديل المنشور --}}
                                            @if(auth()->id() === $quote->user_id && $quote->post_type !== 'audio')
                                                <a href="{{ route('quotes.edit', $quote) }}" class="btn-secondary">
                                                    تعديل
                                                </a>
                                            @endif

                                            {{-- حذف المنشور --}}
                                            @if(auth()->id() === $quote->user_id)
                                                <form
                                                    action="{{ route('quotes.destroy', $quote) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('هل تريدين حذف هذا المنشور؟')"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-secondary">
                                                        حذف
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        <a href="{{ route('login') }}" class="btn-secondary">
                                            سجّلي الدخول للتفاعل
                                        </a>
                                    @endauth
                                </div>

                                {{-- تفاصيل إعادة النشر --}}
                                @if($quote->reposts->isNotEmpty())
                                    <details class="border-t border-gray-200 pt-3">
                                        <summary class="cursor-pointer text-sm font-semibold">
                                            عرض تفاصيل إعادة النشر
                                        </summary>

                                        <div class="mt-3 space-y-2">
                                            @foreach($quote->reposts as $repost)
                                                <div class="flex flex-wrap justify-between gap-2">
                                                    <strong class="text-sm">
                                                        {{ $repost->user->name ?? 'مستخدم' }}
                                                    </strong>

                                                    <time class="qi-meta">
                                                        {{ $repost->created_at->format('Y/m/d - H:i') }}
                                                    </time>
                                                </div>
                                            @endforeach
                                        </div>
                                    </details>
                                @endif

                            </div>

                        </div>

                    </article>

                @empty

                    <div class="qi-empty">

                        {{ !empty($search)
                            ? 'لم يتم العثور على محتوى مطابق للبحث.'
                            : 'لا توجد اقتباسات منشورة حتى الآن.' }}

                    </div>

                @endforelse

            </div>

            {{-- صفحات النتائج إذا كان المتحكم يستخدم Pagination --}}
            @if(method_exists($quotes, 'links'))
                <div class="mt-8">
                    {{ $quotes->links() }}
                </div>
            @endif

        </section>

    </div>

</div>

@endsection