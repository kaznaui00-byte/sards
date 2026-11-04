@extends('Layout.app')

@section('title', 'المستخدمون | لوحة التحكم')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
    @include('admin.partials.nav')

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-5 flex gap-2">
        <input type="search" name="search" value="{{ $search }}" placeholder="ابحث بالاسم أو البريد" aria-label="ابحث بالاسم أو البريد"
               class="min-w-0 flex-1 rounded-lg border border-[#e9e7e4] bg-white px-3 py-2.5">
        <button type="submit" class="btn-secondary">بحث</button>
    </form>

    <ul class="space-y-3">
        @forelse ($users as $listedUser)
            <li class="rounded-xl border border-[#e9e7e4] bg-white p-4">
                <p class="font-bold">
                    {{ $listedUser->name }}
                    @if ($listedUser->is_admin)
                        <span class="mr-1 rounded bg-[#29272b] px-2 py-0.5 text-xs text-white">أدمن</span>
                    @endif
                </p>
                <p class="text-sm break-all text-[#77747a]">{{ $listedUser->email }}</p>
                <p class="mt-1 text-sm text-[#77747a]">
                    انضم {{ $listedUser->created_at?->format('Y/m/d') }} · {{ $listedUser->quotes_count }} منشور
                </p>

                @if ($listedUser->isNot(auth()->user()))
                    <div class="mt-3 flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('admin.users.toggle-admin', $listedUser) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-secondary">{{ $listedUser->is_admin ? 'سحب صلاحية الأدمن' : 'جعله أدمن' }}</button>
                        </form>

                        <form method="POST" action="{{ route('admin.users.destroy', $listedUser) }}" onsubmit="return confirm('سيُحذف المستخدم وكل محتواه نهائيًا. هل تريد المتابعة؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-secondary !text-red-700">حذف</button>
                        </form>
                    </div>
                @endif
            </li>
        @empty
            <li class="rounded-xl border border-[#e9e7e4] bg-white p-6 text-center text-[#77747a]">لا توجد نتائج.</li>
        @endforelse
    </ul>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
@endsection
