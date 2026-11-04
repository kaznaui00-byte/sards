
@extends('Layout.app')

@section('title', 'سرد | البودكاست')

@section('content')

<div class="container mx-auto max-w-6xl px-4 py-10" dir="rtl">

    <header class="mb-10 text-right">
        <p class="text-sm font-bold text-purple-500">SARD AUDIO</p>
        <h1 class="mt-2 text-3xl font-bold">البودكاست والحلقات الصوتية</h1>
        <p class="mt-3 text-gray-500">
            اكتشف أصواتًا وحكايات من مجتمع سرد.
        </p>
    </header>

    @if (auth()->check())
        <div class="mb-10 rounded-2xl border p-6 text-right">

            <div class="mb-5 text-center">
                <div class="mb-3 text-5xl">🎙️</div>
                <h2 class="text-2xl font-bold">ابدأ حكايتك الصوتية</h2>
                <p class="mt-2 text-sm text-gray-500">
                    سجّل صوتك أو ارفع حلقتك وشاركها مع مجتمع سرد.
                </p>
            </div>

            @if (session('success'))
                <p class="mb-4 text-green-600">{{ session('success') }}</p>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-red-600">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form id="podcastForm"
                  action="{{ route('podcasts.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <label class="mb-2 block font-semibold">عنوان الحلقة</label>

                <input
                    type="text"
                    name="title"
                    required
                    maxlength="100"
                    value="{{ old('title') }}"
                    class="mb-5 w-full rounded-lg border p-3"
                    placeholder="اكتب عنوان الحلقة"
                >

                <div class="rounded-xl border p-5 text-center">

                    <h3 class="mb-2 font-bold">🎤 سجّل صوتك مباشرة</h3>

                    <p class="mb-4 text-sm text-gray-500">
                        اضغط على بدء التسجيل للسماح باستخدام الميكروفون.
                    </p>

                    <div class="flex flex-wrap justify-center gap-3">

                        <button
                            type="button"
                            id="startRecording"
                            class="rounded-lg bg-purple-700 px-6 py-3 font-bold text-white">
                            بدء التسجيل
                        </button>

                        <button
                            type="button"
                            id="stopRecording"
                            disabled
                            class="rounded-lg border px-6 py-3 font-bold disabled:opacity-40">
                            إيقاف التسجيل
                        </button>

                    </div>

                    <audio
                        id="audioPreview"
                        controls
                        class="mt-5 hidden w-full">
                    </audio>

                    <p id="recordingStatus" class="mt-3 text-sm text-gray-500">
                        لم يبدأ التسجيل بعد.
                    </p>

                </div>

                <div class="my-5 text-center text-sm text-gray-400">
                    أو
                </div>

                <label class="mb-2 block font-semibold">
                    ارفع ملفًا صوتيًا
                </label>

                <input
                    type="file"
                    id="audioFile"
                    name="audio"
                    accept=".mp3,.m4a,.wav,.ogg,.webm,.mp4"
                    class="mb-5 block w-full rounded-lg border p-3"
                >

                <button
                    type="submit"
                    class="w-full rounded-lg bg-purple-700 px-6 py-3 font-bold text-white">
                    نشر الحلقة
                </button>

            </form>
        </div>
    @else
        <div class="mb-10 rounded-2xl border p-8 text-center">
            <h2 class="font-bold">هل ترغب بمشاركة صوتك؟</h2>
            <p class="mt-2 text-sm text-gray-500">
                سجّل الدخول لتتمكن من نشر حلقتك الصوتية.
            </p>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

        @forelse ($episodes as $episode)

            <article class="rounded-2xl border p-5">

                <div class="mb-4 flex h-36 items-center justify-center rounded-xl bg-gradient-to-br from-violet-950 to-purple-700 text-5xl">
                    🎧
                </div>

                <h2 class="mb-3 text-xl font-bold">
                    {{ $episode->title }}
                </h2>

                <p class="mb-4 text-sm text-gray-500">
                    تقديم: {{ $episode->user->name }}
                </p>

                <audio controls preload="none" class="w-full">
                    <source src="{{ \App\Support\Media::url($episode->audio_path) }}">
                </audio>

                <p class="mt-4 text-xs text-gray-500">
                    {{ $episode->created_at->format('Y-m-d') }}
                </p>

                <a
                    href="{{ route('creators.show', $episode->user) }}"
                    class="mt-4 inline-block text-sm font-semibold text-purple-600">
                    اكتشف صاحب الحلقة ←
                </a>

                <section class="mt-5 border-t pt-4" aria-label="ردود الحلقة">
                    <h3 class="mb-2 text-sm font-bold">الردود ({{ $episode->comments->count() }})</h3>

                    <ul class="space-y-2">
                        @foreach ($episode->comments as $comment)
                            <li class="rounded-lg bg-[#f7f6f4] p-3 text-sm">
                                <p class="font-bold">{{ $comment->user->name }}</p>
                                <p class="mt-1 break-words">{{ $comment->body }}</p>

                                @auth
                                    @if ($comment->user_id === auth()->id() || auth()->user()->is_admin)
                                        <form method="POST" action="{{ route('podcasts.comments.destroy', $comment) }}" class="mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-red-700">حذف</button>
                                        </form>
                                    @endif
                                @endauth
                            </li>
                        @endforeach
                    </ul>

                    @auth
                        <form method="POST" action="{{ route('podcasts.comments.store', $episode) }}" class="mt-3 flex gap-2">
                            @csrf
                            <input name="body" maxlength="500" required placeholder="اكتب ردك" aria-label="اكتب ردك"
                                   class="min-w-0 flex-1 rounded-lg border px-3 py-2 text-sm">
                            <button type="submit" class="btn-secondary">إرسال</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="mt-3 inline-block text-sm font-semibold text-purple-600">سجّل الدخول للرد</a>
                    @endauth
                </section>

            </article>

        @empty

            <div class="col-span-full rounded-2xl border p-10 text-center">
                <div class="mb-3 text-4xl">🎙️</div>

                <h2 class="font-bold">
                    لا توجد حلقات صوتية حتى الآن
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    ستظهر هنا الحلقات المنشورة في سرد.
                </p>
            </div>

        @endforelse

    </div>

    <div class="mt-8">
        {{ $episodes->links() }}
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const startButton = document.getElementById('startRecording');
    const stopButton = document.getElementById('stopRecording');

    // إذا لم يكن المستخدم مسجلاً للدخول
    if (!startButton || !stopButton) return;

    const preview = document.getElementById('audioPreview');
    const status = document.getElementById('recordingStatus');
    const audioFile = document.getElementById('audioFile');
    const form = document.getElementById('podcastForm');

    let recorder = null;
    let chunks = [];
    let recordedBlob = null;

    startButton.addEventListener('click', async function () {

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            status.textContent = 'المتصفح لا يدعم التسجيل أو يحتاج إلى اتصال آمن HTTPS.';
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                audio: true
            });

            chunks = [];

            recorder = new MediaRecorder(stream);

            recorder.ondataavailable = function (event) {
                if (event.data.size > 0) {
                    chunks.push(event.data);
                }
            };

            recorder.onstop = function () {

                recordedBlob = new Blob(chunks, {
                    type: recorder.mimeType || 'audio/webm'
                });

                const audioURL = URL.createObjectURL(recordedBlob);

                preview.src = audioURL;
                preview.classList.remove('hidden');

                stream.getTracks().forEach(track => track.stop());

                status.textContent = 'تم التسجيل. استمع إلى صوتك قبل النشر.';
            };

            recorder.start();

            startButton.disabled = true;
            stopButton.disabled = false;

            status.textContent = 'جاري التسجيل...';

        } catch (error) {
            status.textContent = 'لم نتمكن من تشغيل الميكروفون. تحقّق من إذن المتصفح.';
        }

    });

    stopButton.addEventListener('click', function () {

        if (recorder && recorder.state === 'recording') {
            recorder.stop();

            startButton.disabled = false;
            stopButton.disabled = true;
        }

    });

    audioFile.addEventListener('change', function () {
        if (audioFile.files.length > 0) {
            recordedBlob = null;
            preview.classList.add('hidden');
            status.textContent = 'تم اختيار ملف صوتي للرفع.';
        }
    });

    form.addEventListener('submit', function (event) {

        if (recordedBlob && !audioFile.files.length) {
            event.preventDefault();

            const file = new File(
                [recordedBlob],
                'recording.webm',
                { type: recordedBlob.type }
            );

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);

            audioFile.files = dataTransfer.files;

            form.submit();
        }

    });

});
</script>

@endsection