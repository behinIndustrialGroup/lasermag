<!doctype html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>تأیید کد یکبار مصرف — ستاپ</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap"
        rel="stylesheet">
    <style>
        html,
        body {
            font-family: 'Vazirmatn', sans-serif;
        }

        .container {
            max-width: 1200px;
            margin-inline: auto
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <!-- Hero -->
    <header class="bg-gradient-to-l from-yellow-400 via-yellow-300 to-amber-400 text-gray-900">
        <div class="container px-6 py-12">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-bold mb-3">کد تأیید برای ورود به ستاپ</h1>
                    <p class="mb-6 text-lg md:text-xl">برای ادامه ورود به سامانه، کد یکبار مصرف ارسال‌شده به شماره
                        ثبت‌شده را وارد کنید.</p>
                    <div class="flex flex-col gap-4 bg-white/80 p-6 rounded-xl shadow w-full">
                        <div class="space-y-2">
                            @if (session('success'))
                                <div class="rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">
                                    {{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm">{{ session('error') }}
                                </div>
                            @endif
                            @isset($error)
                                <div class="rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm">{{ $error }}</div>
                            @endisset
                        </div>
                        <form id="verify-otp-form" method="POST" action="{{ route('otp.verify') }}"
                            class="flex flex-col gap-3">
                            @csrf
                            <input type="hidden" name="phone" id="verifyMobile" value="{{ $phone }}">
                            <label class="text-sm font-semibold" for="inputOtp">کد تایید</label>
                            <input type="text" name="otp" id="inputOtp" placeholder="مثال: ۱۲۳۴۵۶" required
                                autofocus inputmode="numeric" class="p-3 border rounded-lg text-center tracking-widest">
                            <button type="submit"
                                class="bg-gray-900 text-white px-4 py-3 rounded-lg font-semibold">تأیید و ورود</button>
                        </form>
                        <form method="POST" action="{{ route('otp.send') }}" class="flex flex-col">
                            @csrf
                            <input type="hidden" name="phone" value="{{ $phone }}">
                            <button type="submit"
                                class="border border-gray-300 text-gray-700 px-4 py-3 rounded-lg font-semibold disabled:opacity-60"
                                id="resendBtn" disabled>ارسال مجدد کد (60)</button>
                        </form>

                    </div>
                </div>
                <div class="flex-1">
                    <div class="rounded-xl overflow-hidden shadow-lg bg-white">
                        <img src="{{ url('behin/slide1.png') }}"
                            alt="پنل خورشیدی" class="w-full h-64 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold mb-2">چرا ستاپ؟</h3>
                            <ol class="list-decimal pr-4 space-y-2 text-sm">
                                <li>ثبت درخواست آنلاین و سریع</li>
                                <li>معرفی پیمانکاران معتبر در سراسر ایران</li>
                                <li>همراهی تا پایان اجرای پروژه</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container px-6 py-12">
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="font-semibold mb-2">کد یکبار مصرف چیست؟</h4>
                <p class="text-sm text-gray-600 leading-6">برای امنیت بیشتر حساب شما، ورود تنها با وارد کردن کد پیامکی
                    انجام می‌شود. این کد برای مدت محدود معتبر است.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="font-semibold mb-2">مشکل در دریافت کد؟</h4>
                <p class="text-sm text-gray-600 leading-6">در صورت عدم دریافت پیامک، پس از پایان شمارش معکوس روی دکمه
                    «ارسال مجدد» بزنید یا با پشتیبانی تماس بگیرید.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="font-semibold mb-2">حفظ اطلاعات</h4>
                <p class="text-sm text-gray-600 leading-6">اطلاعات تماس و هویتی شما نزد ستاپ محفوظ است و فقط برای ارتباط
                    با پیمانکاران مورد استفاده قرار می‌گیرد.</p>
            </div>
        </section>
    </main>

    <footer class="bg-gray-900 text-white py-8">
        <div class="container px-6">
            <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                <div>
                    <h4 class="font-bold mb-2">ستاپ</h4>
                    <p class="text-sm">سامانه تأمین و اجرای پروژه‌های خورشیدی — اتصال متقاضیان به پیمانکاران و ارائه
                        تسهیلات مالی.</p>
                </div>
                <div>
                    <h5 class="font-semibold mb-2">تماس</h5>
                    {{-- <p class="text-sm">ایمیل: info@setap.example</p> --}}
                    <p class="text-sm">تلفن: 02191307571</p>
                </div>
                <div>
                    <h5 class="font-semibold mb-2">مجوزها</h5>
                    {{-- <a referrerpolicy='origin' target='_blank'
                        href='https://trustseal.enamad.ir/?id=642135&Code=Zmyvcsbjmy4wR9QgoHCBdzNN3L93m4qf'><img
                            referrerpolicy='origin'
                            src='https://trustseal.enamad.ir/logo.aspx?id=642135&Code=Zmyvcsbjmy4wR9QgoHCBdzNN3L93m4qf'
                            alt='' style='cursor:pointer' code='Zmyvcsbjmy4wR9QgoHCBdzNN3L93m4qf'></a> --}}

                </div>
            </div>
            <div class="text-sm text-gray-400 mt-6">© تمامی حقوق برای ستاپ محفوظ است.</div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const resendBtn = document.getElementById('resendBtn');
            if (!resendBtn) return;
            let counter = 60;
            const timer = setInterval(() => {
                counter--;
                resendBtn.textContent = `ارسال مجدد کد (${counter})`;
                if (counter <= 0) {
                    clearInterval(timer);
                    resendBtn.textContent = 'ارسال مجدد کد';
                    resendBtn.disabled = false;
                }
            }, 1000);
        });
    </script>
</body>

</html>
