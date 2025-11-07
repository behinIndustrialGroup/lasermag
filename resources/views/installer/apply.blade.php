<!doctype html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>همکاری با ستاپ — ثبت‌نام نصاب پنل خورشیدی</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
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
    <header class="bg-gradient-to-l from-yellow-400 via-yellow-300 to-amber-400 text-gray-900">
        <div class="container px-6 py-12">
            <div class="flex flex-col md:flex-row items-start gap-8">
                <div class="flex-1 space-y-6">
                    <div>
                        <span class="inline-flex items-center gap-2 bg-white/80 text-sm font-semibold px-3 py-1 rounded-full shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 7h18M5 11h14M7 15h10M9 19h6" />
                            </svg>
                            فرصت همکاری جدید برای نصاب‌ها
                        </span>
                        <h1 class="text-3xl md:text-4xl font-bold mt-4">ثبت‌نام نصاب پنل خورشیدی در ستاپ</h1>
                        <p class="mt-3 text-base md:text-lg leading-relaxed">اگر تیم یا شرکت نصب پنل خورشیدی هستید، ستاپ فرصت
                            همکاری پایدار، تامین مالی و پروژه‌های آماده را در سراسر ایران برای شما فراهم می‌کند. اطلاعات خود
                            را ثبت کنید تا در اولین فرصت با شما تماس بگیریم.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="bg-white/70 p-4 rounded-xl shadow">
                            <div class="font-semibold text-gray-700">پروژه‌های فعال</div>
                            <div class="text-2xl font-bold">+۴۰ شهر</div>
                        </div>
                        <div class="bg-white/70 p-4 rounded-xl shadow">
                            <div class="font-semibold text-gray-700">پرداخت منظم</div>
                            <div class="text-2xl font-bold">تضمین‌شده</div>
                        </div>
                        <div class="bg-white/70 p-4 rounded-xl shadow">
                            <div class="font-semibold text-gray-700">پشتیبانی فنی</div>
                            <div class="text-2xl font-bold">همراه شما</div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 w-full">
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h2 class="text-xl font-bold mb-4">فرم درخواست همکاری</h2>
                        @if (session('status'))
                            <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        <form method="POST" action="{{ route('installers.store') }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="block text-sm">
                                    <span class="mb-1 block">نام</span>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 focus:border-amber-500 focus:outline-none" />
                                </label>
                                <label class="block text-sm">
                                    <span class="mb-1 block">نام خانوادگی</span>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 focus:border-amber-500 focus:outline-none" />
                                </label>
                                <label class="block text-sm">
                                    <span class="mb-1 block">کد ملی</span>
                                    <input type="text" name="national_id" value="{{ old('national_id') }}" required
                                        dir="ltr" inputmode="numeric"
                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 focus:border-amber-500 focus:outline-none" />
                                </label>
                                <label class="block text-sm">
                                    <span class="mb-1 block">شماره موبایل</span>
                                    <input type="text" name="phone" value="{{ old('phone') }}" required dir="ltr"
                                        inputmode="tel"
                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 focus:border-amber-500 focus:outline-none" />
                                </label>
                                <label class="block text-sm">
                                    <span class="mb-1 block">استان فعالیت</span>
                                    <input type="text" name="province" value="{{ old('province') }}" required
                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 focus:border-amber-500 focus:outline-none" />
                                </label>
                                <label class="block text-sm">
                                    <span class="mb-1 block">شهر</span>
                                    <input type="text" name="city" value="{{ old('city') }}" required
                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 focus:border-amber-500 focus:outline-none" />
                                </label>
                            </div>
                            <label class="block text-sm">
                                <span class="mb-1 block">توضیحات تکمیلی (تجربه، تجهیزات، ظرفیت اجرا و ...)</span>
                                <textarea name="description" rows="4"
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 focus:border-amber-500 focus:outline-none">{{ old('description') }}</textarea>
                            </label>
                            <button type="submit"
                                class="w-full rounded-lg bg-gray-900 px-4 py-3 text-white font-semibold hover:bg-gray-800 transition">
                                ثبت درخواست همکاری
                            </button>
                            <p class="text-xs text-gray-500 text-center">با ثبت اطلاعات، شرایط همکاری ستاپ را مطالعه کرده‌اید و با
                                تماس کارشناسان ما موافق هستید.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container px-6 py-12 space-y-16">
        <section>
            <h2 class="text-2xl font-bold mb-6">چرا همکاری با ستاپ؟</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="font-semibold text-lg mb-2">پروژه‌های آماده و متنوع</h3>
                    <p class="text-sm leading-6">در ستاپ دسترسی مستقیم به درخواست‌های نصب خانگی، تجاری و نیروگاهی
                        دارید. پروژه‌ها پس از اعتبارسنجی مالی و فنی در اختیار تیم شما قرار می‌گیرند.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="font-semibold text-lg mb-2">تسویه شفاف و منظم</h3>
                    <p class="text-sm leading-6">برنامه پرداخت استاندارد، پیش‌پرداخت اجرای پروژه و تسویه مرحله‌ای به شما
                        کمک می‌کند بدون دغدغه نقدینگی پروژه‌های بیش‌تری را بپذیرید.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="font-semibold text-lg mb-2">پشتیبانی مهندسی و لجستیک</h3>
                    <p class="text-sm leading-6">از تامین تجهیزات اصلی تا پشتیبانی طراحی و نظارت، تیم ستاپ همراه شماست تا
                        کیفیت و رضایت کارفرما تضمین شود.</p>
                </div>
            </div>
        </section>

        <section>
            <div class="bg-white rounded-2xl shadow p-8">
                <h2 class="text-2xl font-bold mb-4">فرایند پیوستن نصاب‌ها به ستاپ</h2>
                <ol class="list-decimal pr-5 space-y-3 text-sm leading-7">
                    <li>تکمیل فرم همکاری و معرفی تجهیزات، تیم اجرایی و استان‌های تحت پوشش.</li>
                    <li>تماس کارشناسان ستاپ، بررسی سوابق اجرایی و عقد قرارداد همکاری.</li>
                    <li>دریافت پروژه‌های پایلوت، ورود به پنل پیمانکاران و آغاز همکاری گسترده.</li>
                </ol>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gradient-to-tr from-amber-50 to-white rounded-2xl shadow p-6">
                <h3 class="text-xl font-semibold mb-3">انتظارات ما از همکاران</h3>
                <ul class="list-disc pr-5 space-y-2 text-sm leading-6">
                    <li>رعایت استانداردهای اجرایی و ایمنی نیروگاه‌های خورشیدی.</li>
                    <li>استفاده از تیم‌های مجرب و تجهیزات تایید شده.</li>
                    <li>تعهد به زمان‌بندی پروژه و گزارش‌دهی منظم در سامانه ستاپ.</li>
                </ul>
            </div>
            <div class="bg-gradient-to-tr from-white to-amber-50 rounded-2xl shadow p-6">
                <h3 class="text-xl font-semibold mb-3">چه چیزهایی ارائه می‌دهیم؟</h3>
                <ul class="list-disc pr-5 space-y-2 text-sm leading-6">
                    <li>قراردادهای شفاف با نرخ‌های رقابتی و پرداخت مطمئن.</li>
                    <li>لیـدهای آماده و پشتیبانی بازاریابی در استان شما.</li>
                    <li>آموزش‌های به‌روز و دسترسی به شبکه تامین تجهیزات معتبر.</li>
                </ul>
            </div>
        </section>
    </main>

    <footer class="bg-gray-900 text-gray-100">
        <div class="container px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
            <div>ستاپ — سامانه تأمین و اجرای پروژه‌های خورشیدی</div>
            <div class="flex gap-4">
                <span>تلفن پشتیبانی: 021-91307571</span>
                <span>ایمیل: info@s3tup.ir</span>
            </div>
        </div>
    </footer>
</body>

</html>
