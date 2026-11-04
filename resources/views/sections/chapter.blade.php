@extends('Layout.app')

@section('title', $chapter->title.' | '.$novel->title)

@section('content')
<div dir="rtl" style="background:#faf9f6; min-height:100vh; padding:40px 20px; color:#29252c;">
    <article style="max-width:760px; margin:auto;">
        <a href="{{ route('novels.show', $novel) }}" style="color:#70518b; text-decoration:none;">← {{ $novel->title }}</a>

        <p style="margin-top:25px; color:#806a91; font-size:14px; font-weight:bold;">الفصل {{ $chapter->chapter_number }}</p>
        <h1 style="font-size:30px; font-weight:800; margin:8px 0 25px;">{{ $chapter->title }}</h1>

        <div style="white-space:pre-line; line-height:2.2; font-size:18px;">{{ $chapter->content }}</div>

        <nav aria-label="التنقل بين الفصول" style="display:flex; flex-wrap:wrap; justify-content:space-between; gap:10px; margin-top:40px;">
            @if ($previousChapter)
                <a href="{{ route('novels.chapters.show', [$novel, $previousChapter]) }}" class="btn-secondary">→ الفصل السابق</a>
            @else
                <span></span>
            @endif

            @if ($nextChapter)
                <a href="{{ route('novels.chapters.show', [$novel, $nextChapter]) }}" class="btn-secondary">الفصل التالي ←</a>
            @endif
        </nav>
    </article>
</div>
@endsection
