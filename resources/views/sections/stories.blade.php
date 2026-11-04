@extends('Layout.app')

@section('content')

<style>
.stories-page {
    direction: rtl;
    background: #fff;
    min-height: 100vh;
    padding: 45px 20px;
    color: #29252c;
}

.stories-container {
    max-width: 1150px;
    margin: auto;
}

.stories-heading {
    margin-bottom: 30px;
}

.stories-heading h1 {
    font-size: 36px;
    font-weight: 800;
}

.stories-heading p {
    color: #777;
    line-height: 2;
    margin-top: 10px;
}

.stories-categories {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 30px 0;
    padding-bottom: 25px;
    border-bottom: 1px solid #eee;
}

.story-category {
    text-decoration: none;
    color: #62576b;
    background: #fff;
    border: 1px solid #e6e0e9;
    padding: 11px 20px;
    border-radius: 30px;
    font-size: 14px;
    transition: .2s;
}

.story-category:hover,
.story-category.active {
    background: #29252c;
    color: white;
    border-color: #29252c;
}

.story-form {
    background: #faf9f8;
    border: 1px solid #eee;
    border-radius: 18px;
    padding: 25px;
    margin-bottom: 35px;
}

.story-form summary {
    cursor: pointer;
    font-size: 19px;
    font-weight: 800;
}

.story-field {
    width: 100%;
    border: 1px solid #ddd6e2;
    border-radius: 10px;
    padding: 13px;
    margin: 8px 0 17px;
    background: white;
    display: block;
}

.story-field:focus {
    outline: 2px solid #d8cce2;
}

.story-label {
    font-weight: 700;
    font-size: 14px;
}

.story-submit {
    background: #29252c;
    color: white;
    border: 0;
    border-radius: 10px;
    padding: 13px 25px;
    cursor: pointer;
}

.stories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 22px;
}

.story-card {
    border: 1px solid #eee;
    border-radius: 16px;
    overflow: hidden;
    background: white;
    transition: .2s;
}

.story-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,.06);
}

.story-card-cover {
    height: 220px;
    background: #f5f2f6;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.story-card-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.story-card-info {
    padding: 20px;
}

.story-card-info h3 {
    font-size: 20px;
    font-weight: 800;
    margin: 10px 0;
}

.story-card-info p {
    color: #777;
    font-size: 14px;
    line-height: 1.9;
}

.story-read-link {
    display: inline-block;
    margin-top: 15px;
    text-decoration: none;
    color: #806a91;
    font-weight: bold;
}

.story-message {
    background: #f0f8f0;
    color: #27632e;
    border-radius: 10px;
    padding: 14px;
    margin-bottom: 20px;
}

.story-error {
    color: #b42318;
    font-size: 13px;
    margin: -10px 0 14px;
}

.story-empty {
    color: #888;
    padding: 35px 15px;
    text-align: center;
}

@media(max-width:600px) {
    .stories-page {
        padding: 30px 15px;
    }

    .stories-heading h1 {
        font-size: 29px;
    }
}
</style>

<div class="stories-page">
<div class="stories-container">

    {{-- عنوان القسم --}}
    <header class="stories-heading">
        <h1>حكاية</h1>

        <p>
            لكل إنسان حكاية، ولكل حكاية كلمات تستحق أن تُروى.
            اكتشفي القصص وشاركي حكايتك مع مجتمع QuoteIt.
        </p>
    </header>

    {{-- رسائل النظام --}}
    @if(session('success'))
        <div class="story-message">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fff0f0;color:#a83232;padding:15px;border-radius:10px;margin-bottom:20px;">
            يرجى مراجعة البيانات المدخلة والتأكد من الحقول المطلوبة.
        </div>
    @endif

    {{-- التصنيفات --}}
    <nav class="stories-categories">

        <a href="{{ route('stories.index') }}"
           class="story-category {{ !request('filter') ? 'active' : '' }}">
            جميع القصص
        </a>

        <a href="{{ route('stories.index', ['filter' => 'short']) }}"
           class="story-category {{ request('filter') === 'short' ? 'active' : '' }}">
            قصص قصيرة
        </a>

        <a href="{{ route('stories.index', ['filter' => 'long']) }}"
           class="story-category {{ request('filter') === 'long' ? 'active' : '' }}">
            قصص طويلة
        </a>

        <a href="{{ route('stories.index', ['filter' => 'new']) }}"
           class="story-category {{ request('filter') === 'new' ? 'active' : '' }}">
            أحدث القصص
        </a>

    </nav>

    {{-- نموذج إضافة قصة --}}
    @auth

        <details class="story-form" {{ $errors->any() ? 'open' : '' }}>

            <summary>＋ أضيفي قصتك</summary>

            <form
                action="{{ route('stories.store') }}"
                method="POST"
                enctype="multipart/form-data"
                style="margin-top:25px;"
            >

                @csrf

                <label class="story-label">عنوان القصة</label>

                <input
                    type="text"
                    name="title"
                    class="story-field"
                    value="{{ old('title') }}"
                    placeholder="اكتبي عنوان القصة"
                    required
                >

                @error('title')
                    <div class="story-error">{{ $message }}</div>
                @enderror

                <label class="story-label">نوع القصة</label>

                <select name="story_type" class="story-field" required>

                    <option value="short_story"
                        {{ old('story_type') === 'short_story' ? 'selected' : '' }}>
                        قصة قصيرة
                    </option>

                    <option value="long_story"
                        {{ old('story_type') === 'long_story' ? 'selected' : '' }}>
                        قصة طويلة
                    </option>

                </select>

                <label class="story-label">نبذة عن القصة</label>

                <textarea
                    name="summary"
                    class="story-field"
                    rows="3"
                    placeholder="اكتبي نبذة تشجع القراء على اكتشاف قصتك"
                >{{ old('summary') }}</textarea>

                <label class="story-label">نص القصة</label>

                <textarea
                    name="content"
                    class="story-field"
                    rows="12"
                    placeholder="اكتبي قصتك هنا..."
                    required
                >{{ old('content') }}</textarea>

                @error('content')
                    <div class="story-error">{{ $message }}</div>
                @enderror

                <label class="story-label">غلاف القصة</label>

                <input
                    type="file"
                    name="cover_image"
                    class="story-field"
                    accept="image/jpeg,image/png,image/webp"
                >

                @error('cover_image')
                    <div class="story-error">{{ $message }}</div>
                @enderror

                <button type="submit" class="story-submit">
                    نشر القصة
                </button>

            </form>

        </details>

    @else

        <p style="margin-bottom:30px;color:#777;">
            سجلي الدخول حتى تتمكني من نشر قصتك.
        </p>

    @endauth

    {{-- عنوان القصص المعروضة --}}
    <div style="margin:35px 0 22px;">

        <h2 style="font-size:25px;font-weight:800;">

            @switch(request('filter'))

                @case('short')
                    القصص القصيرة
                    @break

                @case('long')
                    القصص الطويلة
                    @break

                @case('new')
                    أحدث القصص
                    @break

                @default
                    جميع القصص

            @endswitch

        </h2>

        <p style="color:#888;font-size:14px;margin-top:8px;">
            اكتشفي حكايات كتبها أعضاء QuoteIt.
        </p>

    </div>

    {{-- بطاقات القصص --}}
    <div class="stories-grid">

        @forelse($stories as $story)

            <article class="story-card">

                <div class="story-card-cover">

                    @if($story->cover_path)

                        <img
                            src="{{ asset('storage/'.$story->cover_path) }}"
                            alt="{{ $story->title }}"
                        >

                    @else

                        <span style="font-size:55px;">📖</span>

                    @endif

                </div>

                <div class="story-card-info">

                    <span style="font-size:13px;color:#806a91;font-weight:bold;">
                        {{ $story->story_type === 'short_story' ? 'قصة قصيرة' : 'قصة طويلة' }}
                    </span>

                    <h3>{{ $story->title }}</h3>

                    <p>
                        {{ $story->summary ?: 'اكتشفي تفاصيل هذه الحكاية.' }}
                    </p>

                    <p>
                        بقلم:
                        {{ $story->user->nickname ?: $story->user->name }}
                    </p>

                    <a
                        href="{{ route('stories.show', $story) }}"
                        class="story-read-link"
                    >
                        قراءة القصة ←
                    </a>

                </div>

            </article>

        @empty

            <div class="story-empty">
                لا توجد قصص في هذا التصنيف حتى الآن.
                <br>
                كوني أول من يشارك حكاية هنا.
            </div>

        @endforelse

    </div>

    {{-- التنقل بين الصفحات --}}
    <div style="margin-top:30px;">
        {{ $stories->links() }}
    </div>

</div>
</div>

@endsection