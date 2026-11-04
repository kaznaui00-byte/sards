@extends('Layout.app')

@section('title', 'المستخدمون | لوحة التحكم')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
    @include('admin.partials.nav')

    @if (session('reset_link'))
        <div role="status" class="mb-4 rounded-lg bg-amber-50 p-3 text-sm text-amber-900">
            <p class="font-bold">رابط إعادة تعيين كلمة المرور لـ {{ session('reset_link.name') }}</p>
            <p class="mt-1">انسخ الرابط وأرسله لصاحب الحساب فقط. يظهر مرة واحدة وتنتهي صلاحيته خلال ساعة.</p>
            <input readonly value="{{ session('reset_link.url') }}" aria-label="رابط إعادة التعيين" onclick="this.select()"
                   class="mt-2 w-full rounded-lg border border-amber-200 bg-white px-3 py-2 text-xs" dir="ltr">
        </div>
    @endif

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-5 flex gap-2">
        <input type="search" name="search" value="{{ $search }}" placeholder="ابحث بالاسم أو البريد" aria-label="ابحث بالاسم أو البريد"
               class="min-w-0 flex-1 rounded-lg border border-[var(--site-border)] bg-white px-3 py-2.5">
        <button type="submit" class="btn-secondary">بحث</button>
    </form>

    <ul class="space-y-3">
        @forelse ($users as $listedUser)
            <li class="rounded-xl border border-[var(--site-border)] bg-white p-4">
                <p class="font-bold">
                    {{ $listedUser->name }}
                    @if ($listedUser->is_admin)
                        <span class="mr-1 rounded bg-[#29272b] px-2 py-0.5 text-xs text-white">أدمن</span>
                    @endif
                </p>
                <p class="text-sm break-all text-[var(--site-muted)]">{{ $listedUser->email }}</p>
                <p class="mt-1 text-sm text-[var(--site-muted)]">
                    انضم {{ $listedUser->created_at?->format('Y/m/d') }} · {{ $listedUser->quotes_count }} منشور
                </p>

                @if ($listedUser->isNot(auth()->user()))
                    <div class="mt-3 flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('admin.users.reset-link', $listedUser) }}">
                            @csrf
                            <button type="submit" class="btn-secondary">رابط كلمة مرور</button>
                        </form>

                        <form method="POST" action="{{ route('admin.users.toggle-admin', $listedUser) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-secondary">{{ $listedUser->is_admin ? 'سحب صلاحية الأدمن' : 'جعله أدمن' }}</button>
                        </form>

                        <form method="POST" action="{{ route('admin.users.destroy', $listedUser) }}" onsubmit="return confirm('سيُحذف المستخدم وكل محتواه نهائيًا. هل تريد المتابعة؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-secondary btn-danger">حذف</button>
                        </form>
                    </div>
                @endif
            </li>
        @empty
            <li class="rounded-xl border border-[var(--site-border)] bg-white p-6 text-center text-[var(--site-muted)]">لا توجد نتائج.</li>
        @endforelse
    </ul>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
@endsection
