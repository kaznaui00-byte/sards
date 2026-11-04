<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'سرد')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body,
        button,
        input,
        textarea,
        select {
            font-family: 'IBM Plex Sans Arabic', sans-serif !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white text-[#29252c]">

    <x-navbar :page="$page ?? ''" />

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="border-t border-gray-200 bg-white">
        <div class="mx-auto max-w-7xl px-6 py-8 text-center">
            <p class="text-xl font-black">سرد</p>

            <p class="mt-2 text-sm text-gray-500">
                مساحة للكلمة والصوت والحكاية والإبداع.
            </p>

            <p class="mt-4 text-xs text-gray-400">
                جميع الحقوق محفوظة © سرد
            </p>
        </div>
    </footer>

</body>
</html>