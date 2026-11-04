@extends('Layout.app')

@section('title', 'القصص والروايات | لوحة التحكم')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
    @include('admin.partials.nav')

    <div class="grid gap-6 lg:grid-cols-2">
        <section>
            <h2 class="mb-3 text-lg font-bold">القصص</h2>
            <ul class="space-y-3">
                @forelse ($stories as $story)
                    <li class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-[var(--site-border)] bg-white p-4">
                        <div class="min-w-0">
                            <a href="{{ route('stories.show', $story) }}" class="font-bold">{{ $story->title }}</a>
                            <p class="text-sm text-[var(--site-muted)]">{{ $story->user?->name ?? 'حساب محذوف' }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.content.stories.destroy', $story) }}" onsubmit="return confirm('هل تريد حذف هذه القصة نهائيًا؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-secondary btn-danger">حذف</button>
                        </form>
                    </li>
                @empty
                    <li class="rounded-xl border border-[var(--site-border)] bg-white p-6 text-center text-[var(--site-muted)]">لا توجد قصص بعد.</li>
                @endforelse
            </ul>
            <div class="mt-4">{{ $stories->links() }}</div>
        </section>

        <section>
            <h2 class="mb-3 text-lg font-bold">الروايات</h2>
            <ul class="space-y-3">
                @forelse ($novels as $novel)
                    <li class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-[var(--site-border)] bg-white p-4">
                        <div class="min-w-0">
                            <a href="{{ route('novels.show', $novel) }}" class="font-bold">{{ $novel->title }}</a>
                            <p class="text-sm text-[var(--site-muted)]">{{ $novel->user?->name ?? 'حساب محذوف' }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.content.novels.destroy', $novel) }}" onsubmit="return confirm('هل تريد حذف هذه الرواية نهائيًا؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-secondary btn-danger">حذف</button>
                        </form>
                    </li>
                @empty
                    <li class="rounded-xl border border-[var(--site-border)] bg-white p-6 text-center text-[var(--site-muted)]">لا توجد روايات بعد.</li>
                @endforelse
            </ul>
            <div class="mt-4">{{ $novels->links() }}</div>
        </section>
    </div>
</div>
@endsection
