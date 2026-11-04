@extends('Layout.app')

@section('title', 'My Profile')

@section('content')

@php
    $user = auth()->user();

    $specialties = [
        'writer' => '✍️ كاتب ومؤلف',
        'novelist' => '📚 كاتب روايات',
        'storyteller' => '📖 راوي قصص',
        'podcaster' => '🎙️ صانع بودكاست',
        'quote_creator' => '💭 كاتب اقتباسات وخواطر',
        'sign_language_creator' => '🤟 صانع محتوى بلغة الإشارة',
        'accessible_content_creator' => '♿ صانع محتوى ميسّر',
        'artist' => '🎨 فنان ومبدع',
    ];

    $selectedSpecialties = old(
        'creator_specialties',
        $user->creator_specialties ?? []
    );
@endphp

<div class="mx-auto max-w-4xl px-4 py-8">

    <div class="mb-8 rounded-3xl bg-gradient-to-br from-violet-950 to-purple-800 p-8 text-white">
        <p class="text-sm text-purple-200">YOUR CREATOR SPACE</p>
        <h1 class="mt-2 text-3xl font-bold">ملفي الشخصي</h1>
        <p class="mt-3 text-purple-100">
            عرّف الآخرين بك وبإبداعك ومواهبك.
        </p>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-xl border border-green-300 bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-300 bg-red-50 p-4 text-red-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form
        action="{{ route('profile.update') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6"
    >
        @csrf
        @method('PUT')

        {{-- Personal Information --}}
        <section class="rounded-2xl border p-6">

            <h2 class="mb-5 text-xl font-bold">المعلومات الشخصية</h2>

            <div class="mb-5">
                <img
                    src="{{ $user->avatar ? \App\Support\Media::url($user->avatar) : asset('images/profile.jpeg') }}"
                    alt="Profile Picture"
                    class="mb-4 h-24 w-24 rounded-full border object-cover"
                >

                <label class="mb-2 block font-semibold">
                    الصورة الشخصية
                </label>

                <input
                    type="file"
                    name="avatar"
                    accept=".jpg,.jpeg,.png,.webp"
                    class="input w-full"
                >

                <p class="mt-2 text-sm text-gray-500">
                    JPG أو PNG أو WEBP — الحد الأقصى 2 MB.
                </p>
            </div>

            <div class="space-y-4">

                <div>
                    <label class="mb-2 block font-semibold">الاسم الكامل</label>
                    <input
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        minlength="2"
                        maxlength="50"
                        class="input w-full"
                    >
                </div>

                <div>
                    <label class="mb-2 block font-semibold">الاسم المستعار</label>
                    <input
                        name="nickname"
                        type="text"
                        value="{{ old('nickname', $user->nickname) }}"
                        maxlength="50"
                        class="input w-full"
                    >
                </div>

                <div>
                    <label class="mb-2 block font-semibold">البريد الإلكتروني</label>
                    <input
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="input w-full"
                    >
                </div>

                <div>
                    <label class="mb-2 block font-semibold">تاريخ الميلاد</label>
                    <input
                        name="birthdate"
                        type="date"
                        value="{{ old('birthdate', $user->birthdate?->format('Y-m-d')) }}"
                        max="{{ now()->format('Y-m-d') }}"
                        class="input w-full"
                    >
                </div>

                <div>
                    <label class="block font-semibold">العمر</label>
                    <p class="mt-1 text-gray-500">
                        {{ $user->birthdate
                            ? $user->birthdate->age . ' سنة'
                            : 'أضيفي تاريخ الميلاد لعرض العمر.' }}
                    </p>
                </div>

            </div>
        </section>


        {{-- Creator Specialties --}}
        <section class="rounded-2xl border p-6">

            <p class="mb-2 text-sm font-bold text-violet-600">
                CREATOR PROFILE
            </p>

            <h2 class="mb-2 text-xl font-bold">
                ✨ تخصصاتي الإبداعية
            </h2>

            <p class="mb-5 text-sm text-gray-500">
                اختَر التخصصات التي تعبّر عن محتواك.
                يمكنك اختيار خمسة تخصصات كحد أقصى.
            </p>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                @foreach ($specialties as $key => $label)

                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition hover:border-purple-400">

                        <input
                            type="checkbox"
                            name="creator_specialties[]"
                            value="{{ $key }}"
                            {{ in_array($key, $selectedSpecialties ?? [], true) ? 'checked' : '' }}
                        >

                        <span>{{ $label }}</span>

                    </label>

                @endforeach

            </div>

            <div class="mt-6">

                <label class="mb-2 block font-semibold">
                    نبذة عنك وعن أعمالك
                </label>

                <textarea
                    name="creator_bio"
                    maxlength="500"
                    rows="4"
                    placeholder="اكتب نبذة عن نفسك ومواهبك وأعمالك..."
                    class="input w-full"
                >{{ old('creator_bio', $user->creator_bio) }}</textarea>

                <p class="mt-2 text-xs text-gray-500">
                    الحد الأقصى 500 حرف.
                </p>

            </div>

        </section>


        {{-- Creator Visibility --}}
        <section class="rounded-2xl border border-purple-300 bg-purple-50 p-6">

            <input
                type="hidden"
                name="show_in_creator_space"
                value="0"
            >

            <label class="flex cursor-pointer items-start gap-3">

                <input
                    type="checkbox"
                    name="show_in_creator_space"
                    value="1"
                    class="mt-1"
                    {{ old('show_in_creator_space', $user->show_in_creator_space) ? 'checked' : '' }}
                >

                <span>
                    <strong class="block text-gray-900">
                        🌟 أريد الظهور في مساحة المبدعين
                    </strong>

                    <span class="mt-2 block text-sm text-gray-600">
                        عند تفعيل هذا الخيار يستطيع زوار سرد اكتشاف ملفك الإبداعي وتخصصاتك وأعمالك.
                        المشاركة اختيارية.
                    </span>
                </span>

            </label>

        </section>


        <button
            type="submit"
            class="w-full rounded-xl bg-violet-700 px-6 py-4 font-bold text-white transition hover:bg-violet-600"
        >
            حفظ التعديلات
        </button>

    </form>


    {{-- My Posts --}}
    <section class="mt-10">

        <h2 class="mb-5 text-2xl font-bold">
            منشوراتي ({{ $user->quotes()->count() }})
        </h2>

        @forelse ($user->quotes()->latest()->get() as $quote)

            <article class="mb-4 rounded-2xl border p-5">

                @if ($quote->post_type === 'audio')

                    <h3 class="mb-3 font-bold">
                        🎧 {{ $quote->title }}
                    </h3>

                    @if ($quote->audio_path)
                        <audio controls class="w-full">
                            <source src="{{ \App\Support\Media::url($quote->audio_path) }}">
                        </audio>
                    @endif

                @else

                    <p class="text-lg">
                        {{ $quote->text }}
                    </p>

                    <a
                        href="{{ route('quotes.edit', $quote) }}"
                        class="mt-3 inline-block text-violet-700"
                    >
                        تعديل الاقتباس
                    </a>

                @endif

                <p class="mt-3 text-sm text-gray-500">
                    {{ $quote->created_at->format('Y-m-d') }}
                    · ❤️ {{ $quote->likes()->count() }} إعجاب
                </p>

            </article>

        @empty

            <p class="rounded-xl border p-5 text-gray-500">
                لم تنشر أي محتوى حتى الآن.
            </p>

        @endforelse

    </section>

</div>
@endsection
