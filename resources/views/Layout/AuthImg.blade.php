<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'سرد')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body, button, input, textarea, select {
            font-family: 'IBM Plex Sans Arabic', sans-serif !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F6F3ED] text-[#252522]">

    <x-navbar :page="$page ?? ''" />

    <main class="min-h-screen">
        @yield('form')
    </main>

    <footer class="border-t border-[#DED5C9] bg-[#F6F3ED]">
        <div class="mx-auto max-w-7xl px-6 py-8 text-center">
            <p class="text-3xl font-black">سرد</p>

            <p class="mt-2 text-sm text-[#77736C]">
                للكلمة أثرٌ لا ينتهي.
            </p>

            <p class="mt-4 text-xs text-[#77736C]">
                جميع الحقوق محفوظة © سرد
            </p>
        </div>
    </footer>

</body>
</html>