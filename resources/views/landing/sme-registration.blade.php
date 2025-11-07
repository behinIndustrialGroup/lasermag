<!doctype html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="فرم ثبت‌نام واحدهای صنفی برای تامین برق از طریق نیروگاه خورشیدی کوچک‌مقیاس یا پکیج‌های ذخیره‌ساز انرژی">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>ثبت‌نام واحدهای صنفی — نیروگاه خورشیدی و پکیج ذخیره‌سازی</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        html,
        body {
            font-family: 'Vazirmatn', sans-serif;
        }

        .container {
            max-width: 1200px;
            margin-inline: auto;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <header class="bg-gradient-to-l from-amber-400 via-yellow-300 to-lime-300 text-gray-900">
        <div class="container px-6 py-12">
            <div class="flex flex-col md:flex-row gap-10 items-center">
                <div class="flex-1 space-y-6">
                    <div>
                        <span class="inline-flex items-center gap-2 bg-white/70 text-amber-700 px-4 py-1 rounded-full text-sm font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3v1.5m6.364.636-1.06 1.06M21 12h-1.5m-.636 6.364-1.06-1.06M12 21v-1.5m-6.364-.636 1.06-1.06M3 12h1.5m.636-6.364 1.06 1.06M9 12a3 3 0 1 0 6 0 3 3 0 0 0-6 0Z" />
                            </svg>
                            تامین برق پایدار برای اصناف
                        </span>
                        <h1 class="mt-4 text-3xl md:text-4xl font-bold leading-tight">
                            فرم ثبت‌نام واحدهای صنفی برای احداث نیروگاه خورشیدی یا نصب پکیج ذخیره‌سازی
                        </h1>
                    </div>
                    <p class="text-lg md:text-xl leading-8">
                        اگر قصد دارید هزینه انرژی کسب‌وکار خود را کاهش دهید و از مزایای انرژی پاک بهره‌مند شوید، فرم زیر
                        را تکمیل کنید تا کارشناسان ستاپ با شما تماس بگیرند و مراحل عقد قرارداد را آغاز کنند.
                    </p>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm md:text-base">
                        <li class="flex items-start gap-2 bg-white/70 rounded-lg p-3 shadow">
                            <span class="mt-1 text-amber-600">•</span>
                            <span>کاهش چشمگیر هزینه برق و ثبات در تامین انرژی</span>
                        </li>
                        <li class="flex items-start gap-2 bg-white/70 rounded-lg p-3 shadow">
                            <span class="mt-1 text-amber-600">•</span>
                            <span>دریافت پشتیبانی فنی و مالی از تیم تخصصی ستاپ</span>
                        </li>
                        <li class="flex items-start gap-2 bg-white/70 rounded-lg p-3 shadow">
                            <span class="mt-1 text-amber-600">•</span>
                            <span>افزایش اعتبار برند با بهره‌گیری از انرژی پاک و پایدار</span>
                        </li>
                        <li class="flex items-start gap-2 bg-white/70 rounded-lg p-3 shadow">
                            <span class="mt-1 text-amber-600">•</span>
                            <span>امکان فروش برق مازاد به شبکه و بازگشت سرمایه در کوتاه‌مدت</span>
                        </li>
                    </ul>
                </div>
                <div class="flex-1 w-full">
                    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                        <h2 class="text-xl font-semibold mb-4 text-gray-900">اطلاعات واحد صنفی</h2>
                        <p class="text-sm text-gray-600 mb-6">لطفاً اطلاعات خود را وارد کنید؛ کارشناسان ما در اسرع وقت جهت هماهنگی
                            و عقد قرارداد با شما تماس خواهند گرفت.</p>
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
                        <form id="registration-form" class="space-y-4" method="POST" action="#">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">نام</label>
                                    <input type="text" name="firstname" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">نام خانوادگی</label>
                                    <input type="text" name="lastname" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">کد ملی</label>
                                    <input type="text" name="national_id" dir="ltr" inputmode="numeric" maxlength="10" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="1234567890" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">شماره موبایل</label>
                                    <input type="tel" name="mobile" dir="ltr" inputmode="numeric" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="09xxxxxxxxx" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">نام اتحادیه یا صنف</label>
                                    <input type="text" name="union" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="مثال: اتحادیه رستوران‌داران" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">استان</label>
                                    <input type="text" name="province" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="مثال: تهران" required>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">توضیحات تکمیلی</label>
                                <textarea name="description" rows="4" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="هرگونه توضیح درباره مصرف فعلی، فضای موجود یا نیازمندی‌های خاص خود را بنویسید."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold shadow hover:bg-gray-800 transition">
                                ارسال درخواست و دریافت مشاوره رایگان
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 mt-4">
                            اطلاعات وارد شده در این فرم تنها برای تماس کارشناسان ستاپ استفاده خواهد شد و در هیچ پایگاه داده‌ای ذخیره
                            نمی‌شود.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container px-6 py-12 space-y-16">
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-lg mb-3">درآمدزایی از فروش برق مازاد</h3>
                <p class="text-sm text-gray-600 leading-6">با اتصال نیروگاه خورشیدی کوچک‌مقیاس به شبکه سراسری، برق مازاد واحد
                    صنفی شما توسط وزارت نیرو خریداری می‌شود و جریان درآمدی پایدار ایجاد می‌کند.</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-lg mb-3">کاهش هزینه انرژی و خاموشی</h3>
                <p class="text-sm text-gray-600 leading-6">پکیج‌های ذخیره‌سازی انرژی، برق اضطراری را در زمان قطعی تامین کرده و
                    اوج مصرف را مدیریت می‌کنند تا هزینه قبوض برق کاهش یابد.</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-lg mb-3">نصب سریع و پشتیبانی کامل</h3>
                <p class="text-sm text-gray-600 leading-6">از ارزیابی اولیه تا تامین تجهیزات، نصب و نگهداری، تیم ستاپ تمامی
                    مراحل را با استانداردهای فنی و مالی همراهی می‌کند.</p>
            </div>
        </section>

        <section class="bg-white rounded-2xl shadow px-6 py-10 md:px-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="space-y-4">
                    <h2 class="text-2xl font-bold">چرا اصناف به سراغ انرژی خورشیدی می‌روند؟</h2>
                    <ul class="space-y-3 text-sm text-gray-600 leading-6">
                        <li>کاهش وابستگی به شبکه برق و جلوگیری از زیان‌های ناشی از قطعی‌های ناگهانی.</li>
                        <li>استفاده از تسهیلات و معافیت‌های مالیاتی مرتبط با انرژی تجدیدپذیر.</li>
                        <li>ایجاد مزیت رقابتی با معرفی کسب‌وکار به عنوان پیشرو در مسئولیت‌پذیری زیست‌محیطی.</li>
                    </ul>
                </div>
                <div class="bg-gradient-to-br from-amber-300/50 via-white to-white rounded-xl border border-amber-100 p-6">
                    <h3 class="text-lg font-semibold mb-3">فرآیند همکاری با ستاپ</h3>
                    <ol class="space-y-3 text-sm text-gray-700 leading-6">
                        <li><strong>ثبت اطلاعات:</strong> فرم را تکمیل کنید تا نیاز انرژی شما بررسی شود.</li>
                        <li><strong>ارزیابی و پیشنهاد:</strong> کارشناسان ما برآورد اقتصادی و فنی ارائه می‌دهند.</li>
                        <li><strong>عقد قرارداد و اجرا:</strong> قرارداد تامین برق تنظیم و عملیات نصب آغاز می‌شود.</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="text-center bg-gradient-to-l from-gray-900 via-gray-800 to-gray-900 text-white rounded-3xl px-8 py-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">همین امروز قدم اول را برای انرژی پایدار بردارید</h2>
            <p class="text-sm md:text-base text-gray-100 mb-6 leading-7">با تکمیل فرم ثبت‌نام، کارشناسان ما در کمتر از ۲۴ ساعت
                برای ارائه راهکار ویژه صنف شما تماس خواهند گرفت.</p>
            <a href="#registration-form" class="inline-flex items-center gap-2 bg-amber-400 text-gray-900 px-6 py-3 rounded-lg font-semibold shadow hover:bg-amber-300 transition">
                آغاز ثبت‌نام
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </section>
    </main>

    <footer class="bg-gray-100 py-6">
        <div class="container px-6 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-600">
            <div>ستاپ — سامانه تامین و اجرای پروژه‌های خورشیدی</div>
            <div class="flex items-center gap-3">
                <span>پشتیبانی: 021-91307571</span>
                <span>ایمیل: info@s3tup.ir</span>
            </div>
        </div>
    </footer>
</body>

</html>
