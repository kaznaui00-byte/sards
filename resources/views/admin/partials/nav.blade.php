@php
    $adminLinks = [
        ['name' => 'نظرة عامة', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard'],
        ['name' => 'المنشورات', 'route' => 'admin.quotes.index', 'match' => 'admin.quotes.*'],
        ['name' => 'المستخدمون', 'route' => 'admin.users.index', 'match' => 'admin.users.*'],
        ['name' => 'القصص والروايات', 'route' => 'admin.content.index', 'match' => 'admin.content.*'],
        ['name' => 'المظهر', 'route' => 'admin.appearance.edit', 'match' => 'admin.appearance.*'],
        ['name' => 'لغة الإشارة', 'route' => 'admin.sign-language.index', 'match' => 'admin.sign-language.*'],
    ];
@endphp

<header class="mb-6">
    <p class="text-sm font-bold text-[var(--site-accent)]">سرد</p>
    <h1 class="mt-1 text-3xl font-extrabold">لوحة التحكم</h1>

    <nav aria-label="أقسام لوحة التحكم" class="mt-5 flex gap-2 overflow-x-auto border-b border-[var(--site-border)] pb-3">
        @foreach ($adminLinks as $adminLink)
            <a href="{{ route($adminLink['route']) }}"
               @if (request()->routeIs($adminLink['match'])) aria-current="page" @endif
               class="whitespace-nowrap rounded-lg px-4 py-2.5 text-sm font-bold {{ request()->routeIs($adminLink['match']) ? 'bg-[var(--site-text)] !text-[var(--site-bg)]' : 'bg-[var(--site-soft)] text-[#29272b]' }}">
                {{ $adminLink['name'] }}
            </a>
        @endforeach
    </nav>
</header>

@if (session('success'))
    <p role="status" class="mb-4 rounded-lg bg-green-50 p-3 text-sm font-bold text-green-800">{{ session('success') }}</p>
@endif

@if (session('error'))
    <p role="alert" class="mb-4 rounded-lg bg-red-50 p-3 text-sm font-bold text-red-800">{{ session('error') }}</p>
@endif
