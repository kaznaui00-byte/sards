```blade
@extends('Layout.app')

@section('content')

<div dir="rtl" style="background:#faf9f6; min-height:100vh; padding:40px 20px; color:#29252c;">
    <div style="max-width:1000px; margin:auto;">

        <a href="{{ route('novels.index') }}"
           style="display:inline-block; margin-bottom:25px; color:#70518b; text-decoration:none;">
            ← العودة إلى الروايات
        </a>

        {{-- غلاف الرواية ومعلوماتها --}}
        <div style="display:grid; grid-template-columns:minmax(0, 1fr) 220px; gap:30px; align-items:start; background:white; padding:25px; border-radius:20px; border:1px solid #eee9ef;">

            <div>
                <span style="color:#806a91; font-size:13px; font-weight:bold;">
                    {{ $novel->genre }}
                </span>

                <h1 style="font-size:32px; font-weight:800; margin:12px 0;">
                    {{ $novel->title }}
                </h1>

                <p style="color:#777; margin-bottom:15px;">
                    الكاتب: {{ $novel->user->nickname ?: $novel->user->name }}
                </p>

                <p style="line-height:2; color:#625d66;">
                    {{ $novel->description ?: 'لا توجد نبذة عن هذه الرواية.' }}
                </p>

                <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:20px;">
                    <span style="background:#f1edf5; padding:8px 13px; border-radius:20px; font-size:13px;">
                        {{ $novel->length_type === 'short' ? 'رواية قصيرة' : 'رواية طويلة' }}
                    </span>

                    <span style="background:#f1edf5; padding:8px 13px; border-radius:20px; font-size:13px;">
                        {{ $novel->status === 'completed' ? 'مكتملة' : 'مستمرة' }}
                    </span>

                    <span style="background:#f1edf5; padding:8px 13px; border-radius:20px; font-size:13px;">
                        {{ $novel->chapters->count() }} فصل
                    </span>
                </div>
            </div>

            <div>
                @if ($novel->cover_image_path)
                    <img
                        src="{{ asset