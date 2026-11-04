@extends('Layout.app')

@section('content')

<style>
.story-reading {
    direction: rtl;
    background: #fff;
    min-height: 100vh;
    padding: 40px 20px;
    color: #29252c;
}

.story-container {
    max-width: 850px;
    margin: auto;
}

.story-back {
    display: inline-block;
    color: #806a91;
    text-decoration: none;
    margin-bottom: 30px;
    font-weight: bold;
}

.story-cover {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 18px;
    margin-bottom: 30px;
}

.story-title {
    font-size: 36px;
    font-weight: 800;
    line-height: 1.6;
    margin-bottom: 12px;
}

.story-meta {
    color: #888;
    font-size: 14px;
    margin-bottom: 25px;
}

.story-summary {
    background: #f8f6fa;
    padding: 20px;
    border-radius: 14px;
    line-height: 2;
    margin-bottom: 35px;
    color: #5e5665;
}

.story-content {
    font-size: 18px;
    line-height: 2.3;
    color: #38323d;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.story-delete {
    margin-top: 40px;
    background: #a83232;
    color: white;
    border: 0;
    border-radius: 10px;
    padding: 12px 22px;
    cursor: pointer;
}
</style>

<div class="story-reading">
    <div class="story-container">

        <a href="{{ route('stories.index') }}" class="story-back">
            ← العودة إلى القصص
        </a>

        @if ($story->cover_path)
            <img
                class="story-cover"
                src="{{ \App\Support\Media::url($story->cover_path) }}"
                alt="{{ $story->title }}"
            >
        @endif

        <div style="color:#806a91;font-weight:bold;font-size:14px;">
            {{ $story->story_type === 'short_story' ? 'قصة قصيرة' : 'قصة طويلة' }}
        </div>

        <h1 class="story-title">
            {{ $story->title }}
        </h1>

        <div class="story-meta">
            الكاتب:
            {{ $story->user->nickname ?: $story->user->name }}
            ·
            {{ $story->created_at->format('Y/m/d') }}
        </div>

        @if ($story->summary)
            <div class="story-summary">
                {{ $story->summary }}
            </div>
        @endif

        <article class="story-content">
            {{ $story->content }}
        </article>

        @auth
            @if ($story->user_id === auth()->id())
                <form
                    action="{{ route('stories.destroy', $story) }}"
                    method="POST"
                    onsubmit="return confirm('هل أنت متأكدة من حذف هذه القصة؟')"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="story-delete">
                        حذف القصة
                    </button>
                </form>
            @endif
        @endauth

    </div>
</div>

@endsection