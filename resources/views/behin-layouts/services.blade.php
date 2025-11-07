<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>انرژی خورشیدی | آینده‌ات را روشن کن</title>
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">

    <!-- Tailwind (CDN for demo) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="{{ url('behin/behin-dist/dist/css/custom-style.css') . '?' . config('app.version') }}">
    <link rel="stylesheet" href="{{ url('behin/behin-dist/dist/css/custom.css') . '?' . config('app.version') }}">
    <style>
        /* فونت ایران‌سنس/پیش‌فرض */
        @font-face {
            font-family: IransansX;
            src: src: url('/behin/behin-dist/dist/fonts/Vazir.eot');
        }

        html,
        body {
            font-family: Vazir, IransansX, Vazirmatn, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
        }

        /* گرادیان پویا پس‌زمینه */
        .bg-animated {
            background: radial-gradient(60% 60% at 80% 20%, rgba(255, 234, 138, .55) 0%, rgba(255, 234, 138, 0) 60%),
                radial-gradient(60% 60% at 20% 80%, rgba(134, 239, 172, .5) 0%, rgba(134, 239, 172, 0) 60%),
                linear-gradient(135deg, #ecfccb 0%, #fef9c3 45%, #e0f2fe 100%);
            animation: moveGlow 12s ease-in-out infinite alternate;
        }

        @keyframes moveGlow {
            0% {
                background-position: 0% 0%, 100% 100%, 0% 0%;
            }

            100% {
                background-position: 20% -10%, 80% 120%, 100% 100%;
            }
        }

        /* کارت شیشه‌ای */
        .glass {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, .65);
        }

        /* خطوط تزئینی خورشیدی */
        .sunburst {
            background: conic-gradient(from 180deg at 50% 50%, rgba(255, 193, 7, .35), rgba(255, 193, 7, 0) 25%);
            mask: radial-gradient(circle at center, black 30%, transparent 31%);
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-8px)
            }
        }
    </style>
</head>

<body class="min-h-screen bg-animated flex flex-col">
    <!-- لایه‌های تزئینی -->
    <div aria-hidden="true" class="fixed inset-0 pointer-events-none select-none">
        <div class="absolute -top-16 -left-10 w-72 h-72 rounded-full sunburst opacity-40"></div>
        <div class="absolute -bottom-10 -right-10 w-80 h-80 rounded-full sunburst opacity-30"></div>
    </div>

    <!-- محتوا -->
    <main class="flex-grow flex items-center justify-center">
        @yield('contents')
    </main>

    <!-- فوتر -->
    <footer class="border-t border-white/40 bg-white/70 backdrop-blur-sm">
        <div
          class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col md:flex-row items-center justify-between gap-3 text-gray-600">
          <div>© <span id="year"></span> گروه صنعتی بهین انرژی — همه حقوق محفوظ است.</div>
          <div class="text-xs">طراحی با عشق به طبیعت و تکنولوژی ☀️</div>
        </div>
    </footer>
</body>


</html>
