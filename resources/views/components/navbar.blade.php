@props(['page' => ''])

@php
    $items = [
        ['name' => 'الرئيسية', 'route' => 'home'],
        ['name' => 'الاقتباسات', 'route' => 'quotes.index'],
        ['name' => 'البودكاست', 'route' => 'podcasts.index'],
        ['name' => 'المبدعون', 'route' => 'creators.index'],
    ];
@endphp

<nav class="quoteit-nav" dir="rtl">
    <div class="quoteit-nav-inner">
        {{-- شعار سرد --}}
        <a href="{{ route('home') }}" class="quoteit-brand">
            <img src="{{ asset('images/Untitled-1.png') }}" alt="سرد">
        </a>
       
        {{-- روابط القائمة --}}
        <div class="quoteit-nav-links">
            @foreach($items as $item)
                <a href="{{ route($item['route']) }}"
                   @if(request()->routeIs($item['route'])) aria-current="page" @endif
                   class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">
                    {{ $item['name'] }}
                </a>
            @endforeach
        </div>

        {{-- أدوات الموقع --}}
        <div class="quoteit-nav-tools">

            <button type="button"
                id="accessibility-toggle"
                aria-label="إعدادات إمكانية الوصول"
                aria-controls="accessibility-panel"
                aria-expanded="false">♿</button>

            <div id="accessibility-panel"
                hidden
                dir="rtl"
                class="quoteit-access-panel">

                <h3>إمكانية الوصول</h3>
                <p>حجم الخط</p>

                <div class="quoteit-access-actions">
                    <button type="button" id="font-increase">تكبير +</button>
                    <button type="button" id="font-decrease">تصغير −</button>
                    <button type="button" id="font-reset">الأصلي</button>
                </div>

                <hr>

                <button type="button" id="contrast-toggle">التباين العالي</button>
                <button type="button" id="motion-toggle">تقليل الحركة</button>
            </div>

            <button type="button"
                id="theme-toggle"
                aria-label="تفعيل الوضع الليلي"
                title="الوضع الليلي">☾</button>

            <a href="{{ route('quotes.index') }}"
                aria-label="البحث"
                class="quoteit-search">

                <svg width="21" height="21"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m16 16 4 4"/>
                </svg>
            </a>
            @auth
                <a href="{{ url('/profile') }}" class="quoteit-account">
                    حسابي
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="quoteit-logout">
                        خروج
                    </button>
                </form>
            @else

                </a>
            @endauth
    <a href="{{ route('login') }}" class="quoteit-account">دخول</a>

    <a href="{{ route('signup') }}" class="quoteit-account">
        إنشاء حساب
    </a>

        
        </div>
    </div>
</nav>

<style>
/* الشريط الرئيسي */

.quoteit-nav {
    position: sticky;
    top: 0;
    z-index: 1000;
    width: 100%;
    background: #fff;
    color: #202020;
    border-bottom: 1px solid #e7e4df;
}

.quoteit-nav-inner {
    max-width: 1320px;
    min-height: 78px;
    margin: auto;
    padding: 12px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 28px;
}

.quoteit-brand {
    display: flex;
    align-items: center;
    width: 85px;
    height: 45px;
    overflow: hidden;
}

.quoteit-brand img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;

}


/* روابط القائمة */

.quoteit-nav-links {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 30px;
    flex: 1;
}

.quoteit-nav-links a {
    color: #333;
    text-decoration: none;
    font-size: 15px;
    font-weight: 700;
    padding: 10px 0;
    border-bottom: 2px solid transparent;
    transition: color 0.2s ease;
}

.quoteit-nav-links a:hover,
.quoteit-nav-links a.active {
    color: #000;
    border-color: #222;
}

/* الأدوات */

.quoteit-nav-tools {
    display: flex;
    align-items: center;
    gap: 13px;
    flex-shrink: 0;
}

.quoteit-nav-tools form {
    margin: 0;
}

.quoteit-nav-tools > button,
.quoteit-search {
    width: 39px;
    height: 39px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5e2dc;
    border-radius: 50%;
    background: transparent;
    color: #222;
    cursor: pointer;
    text-decoration: none;
    font-size: 18px;
}

.quoteit-account {
    color: #222;
    text-decoration: none;
    font-weight: 750;
    font-size: 14px;
}

.quoteit-logout {
    border: 0;
    background: transparent;
    color: #777;
    cursor: pointer;
    font: inherit;
}

/* قائمة إمكانية الوصول */

.quoteit-access-panel {
    position: fixed;
    top: 85px;
    right: 24px;
    z-index: 2000;
    width: min(320px, calc(100vw - 32px));
    padding: 20px;
    background: #fff;
    color: #222;
    border: 1px solid #ddd;
    border-radius: 12px;
    box-shadow: 0 10px 35px #0002;
}

.quoteit-access-panel h3 {
    margin: 0 0 14px;
}

.quoteit-access-panel button {
    margin: 4px;
    padding: 8px 10px;
    border: 1px solid #ddd;
    border-radius: 7px;
    background: #fff;
    color: #222;
    cursor: pointer;
}

.quoteit-access-panel hr {
    margin: 14px 0;
}

/* الوضع الليلي */

html[data-theme="dark"] .quoteit-nav {
    background: #191919;
    color: #fff;
    border-color: #383838;
}

html[data-theme="dark"] .quoteit-brand,
html[data-theme="dark"] .quoteit-nav-links a,
html[data-theme="dark"] .quoteit-account,
html[data-theme="dark"] .quoteit-search,
html[data-theme="dark"] .quoteit-nav-tools > button {
    color: #f5f5f5;
}

/* الشاشات المتوسطة */

@media (max-width: 850px) {
    .quoteit-nav-inner {
        flex-wrap: wrap;
        gap: 12px;
        padding: 13px 18px;
    }

    .quoteit-nav-links {
        order: 3;
        width: 100%;
        flex-basis: 100%;
        justify-content: flex-start;
        gap: 23px;
        overflow-x: auto;
    }
}

/* الجوال */

@media (max-width: 700px) {
    .quoteit-nav-inner {
        padding: 12px 14px;
        gap: 10px;
    }

    .quoteit-brand img {
       width: 75px;
height: 35px;
    }

    .quoteit-nav-links {
        order: 3;
        flex: 0 0 100%;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-top: 8px;
    }

    .quoteit-nav-links a {
        font-size: 13px;
        white-space: nowrap;
    }

    .quoteit-nav-tools {
        order: 2;
        gap: 7px;
    }
}
</style>