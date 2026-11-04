
@extends('Layout.app')

@section('title', 'سرد | الرئيسية')

@php
    $latestQuotes = $latestQuotes ?? collect();
    $latestEpisodes = $latestEpisodes ?? collect();
@endphp

@section('content')

<style>
    .sard-home {
        background: #f8f7f3;
        color: #24241f;
        min-height: 100vh;
        direction: rtl;
    }

    .sard-container {
        max-width: 1320px;
        margin: auto;
        padding: 35px 5%;
    }

    .sard-kicker {
        color: #94745b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 3px;
    }

     {.sard-hero {
    min-height: auto;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 35px;
    padding: 35px 0;
    border-bottom: 1px solid #dedbd3;
}
        min-height: auto;
display: flex;
flex-direction: column;
align-items: stretch;
gap: 35px;
padding: 35px 0;
border-bottom: 1px solid #dedbd3;
    }

    .sard-hero h1 {
        font-size: clamp(48px, 7vw, 92px);
        line-height: 1.35;
        font-weight: 800;
        letter-spacing: -3px;
        margin: 22px 0;
    }

    .sard-hero p {
        color: #77736b;
        font-size: 18px;
        line-height: 2.1;
        max-width: 490px;
    }
.sard-hero-art {
    width: 100%;
    min-height: auto;
    padding: 0;
    overflow: hidden;
    background: transparent;
}
.sard-hero-art {
    padding: 0;
    overflow: hidden;
    min-height: 340px;
}
.sard-hero-image {
    width: 100%;
    height: 420px;
    object-fit: cover;
    object-position: center;
    display: block;
}
.sard-hero-art {
    width: 100%;
    padding: 0;
    overflow: hidden;
    min-height: auto;
}
    .sard-hero-art .mark {
        font-size: 90px;
        font-weight: 800;
        line-height: 1.3;
    }

    .sard-hero-art p {
        color: #d5c8b7;
        font-size: 16px;
    }

    .sard-button {
        display: inline-block;
        margin-top: 25px;
        background: #292923;
        color: #fff !important;
        padding: 15px 28px;
        text-decoration: none;
        font-weight: 700;
        transition: .2s;
    }

    .sard-button:hover {
        background: #94745b;
    }

    .sard-section {
        padding: 75px 0;
        border-bottom: 1px solid #dedbd3;
    }

    .sard-section-head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 32px;
    }

    .sard-section h2 {
        font-size: clamp(27px, 4vw, 42px);
        font-weight: 800;
        margin-top: 12px;
    }

    .sard-more {
        color: #94745b;
        text-decoration: none;
        font-weight: 700;
        white-space: nowrap;
    }

    .sard-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .sard-card {
        background: #fffefa;
        border: 1px solid #e7e2d9;
        padding: 30px;
        transition: transform .2s, border-color .2s;
    }

    .sard-card:hover {
        transform: translateY(-4px);
        border-color: #b9a38d;
    }

    .sard-card-label {
        color: #94745b;
        font-size: 12px;
        font-weight: 700;
    }

    .sard-quote {
        font-size: clamp(19px, 2.3vw, 25px);
        font-weight: 600;
        line-height: 2;
        margin: 25px 0;
    }

    .sard-meta {
        color: #88847b;
        font-size: 13px;
        line-height: 2;
    }

    .sard-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 22px;
    }

    .sard-actions button {
        border: 1px solid #e4ded4;
        padding: 9px 14px;
        background: transparent;
        cursor: pointer;
        color: #555149;
        font-family: inherit;
    }

    .sard-actions button:hover {
        background: #f1ece4;
    }

    .sard-empty {
        background: #fffefa;
        padding: 35px;
        color: #77736b;
        text-align: center;
        grid-column: 1 / -1;
    }

    .sard-audio {
        width: 100%;
        margin-top: 20px;
    }

    .sard-footer {
        padding: 35px 5%;
        color: #88847b;
        font-size: 13px;
        text-align: center;
    }

    @media (max-width: 750px) {
        .sard-hero {
            grid-template-columns: 1fr;
            gap: 30px;
            padding: 45px 0;
        }

        .sard-hero-art {
            min-height: 0;
        }

        .sard-hero-image {
            height: auto;
            object-fit: contain;
        }

        .sard-grid {
            grid-template-columns: 1fr;
        }

        .sard-section-head {
            align-items: start;
            flex-direction: column;
        }

        .sard-container {
            padding: 25px 6%;
        }
    }
</style>

<div class="sard-home">
    <main class="sard-container">
{{-- الواجهة الرئيسية --}}
<section class="sard-hero">

    <div class="sard-hero-art">
        <img
            src="{{ asset('images/sard-bg.png') }}"
            alt="خلفية سرد"
            class="sard-hero-image"
        >
    </div>

    <div class="sard-hero-content">
                <span class="sard-kicker">SARD — DIGITAL CULTURE</span>

                <h1>للكلمات<br>حياة أخرى.</h1>

                <p>
                    ليست كل الكلمات عابرة،
                    بعضها يستحق أن يُحفظ،
                    وبعضها يستحق أن يُروى.
                    هنا تبدأ حكايات الكلمات.
                </p>

                <a href="{{ route('quotes.index') }}" class="sard-button">
                    اكتشفي عالم سرد ←
                </a>
            </div>

    
</section>

{{-- الاقتباسات --}}
<section class="sard-section">
        {{-- الاقتباسات --}}
        <section class="sard-section">
            <div class="sard-section-head">
                <div>
                    <span class="sard-kicker">WORDS TO REMEMBER</span>
                    <h2>مختارات من سرد</h2>
                </div>

                <a href="{{ route('quotes.index') }}" class="sard-more">
                    جميع الاقتباسات ←
                </a>
            </div>

            <div class="sard-grid">
                @forelse($latestQuotes as $quote)
                    <article class="sard-card">
                        <span class="sard-card-label">اقتباس من مجتمع سرد</span>

                        <blockquote class="sard-quote">
                            «{{ $quote->text }}»
                        </blockquote>

                        @if($quote->attribution)
                            <p class="sard-meta">
                                قائل المقولة: {{ $quote->attribution }}
                            </p>
                        @endif

                        <div class="sard-meta">
                            {{ optional($quote->user)->nickname ?: (optional($quote->user)->name ?: 'كاتب') }}
                            ·
                            {{ $quote->created_at?->format('Y/m/d') }}
                        </div>

                        <div class="sard-actions">
                            @auth
                                <form action="{{ route('quotes.like', $quote) }}" method="POST">
                                    @csrf
                                    <button type="submit">
                                        ❤️ إعجاب ({{ $quote->likes->count() }})
                                    </button>
                                </form>

                                <form action="{{ route('quotes.repost', $quote) }}" method="POST">
                                    @csrf
                                    <button type="submit">
                                        ↻ إعادة نشر ({{ $quote->reposts->count() }})
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="sard-more">
                                    سجّلي الدخول للتفاعل
                                </a>
                            @endauth
                        </div>
                    </article>
                @empty
                    <div class="sard-empty">
                        لا توجد اقتباسات منشورة حتى الآن.
                    </div>
                @endforelse
            </div>
        </section>

        {{-- التسجيلات الصوتية --}}
        <section class="sard-section">
            <div class="sard-section-head">
                <div>
                    <span class="sard-kicker">LISTEN TO THE WORDS</span>
                    <h2>حين تصبح الكلمات صوتًا</h2>
                </div>

                <a href="{{ route('podcasts.index') }}" class="sard-more">
                    استكشفي التسجيلات ←
                </a>
            </div>

            <div class="sard-grid">
                @forelse($latestEpisodes as $episode)
                    <article class="sard-card">
                        <span class="sard-card-label">من أرشيف سرد الصوتي</span>

                        <h3 class="sard-quote">
                            {{ $episode->title }}
                        </h3>

                        @if($episode->audio_path)
                            <audio controls class="sard-audio">
                                <source src="{{ asset('storage/'.$episode->audio_path) }}">
                            </audio>
                        @endif

                        <p class="sard-meta">
                            {{ $episode->user->name ?? 'سرد' }}
                        </p>
                    </article>
                @empty
                    <div class="sard-empty">
                        قريبًا، نستمع إلى حكايات جديدة.
                    </div>
                @endforelse
            </div>
        </section>

    </main>

    <footer class="sard-footer">
        سرد © {{ date('Y') }} — للكلمات أثرٌ لا ينتهي.
    </footer>
</div>

@endsection
