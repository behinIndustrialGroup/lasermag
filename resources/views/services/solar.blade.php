@extends('behin-layouts.services')

@section('contents')
<main class="flex-grow items-center justify-center">

    <header class="relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-6">
            <nav class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <!-- لوگوی ساده خورشیدی -->
                    <div class="w-10 h-10 rounded-xl bg-yellow-400/90 grid place-content-center shadow-md">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 4V2M12 22v-2M4 12H2M22 12h-2M5 5 3.5 3.5M20.5 20.5 19 19M5 19l-1.5 1.5M20.5 3.5 19 5"
                                stroke="#78350f" stroke-width="1.6" stroke-linecap="round" />
                            <circle cx="12" cy="12" r="4.5" fill="#fde047" stroke="#f59e0b"
                                stroke-width="1.2" />
                        </svg>
                    </div>
                    <span class="text-xl md:text-2xl font-extrabold text-gray-800">گروه صنعتی بهین انرژی</span>
                </div>
            </nav>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
            <div class="grid lg:grid-cols-2 gap-8 items-center">
                <!-- متن قهرمان -->
                <div class="space-y-5">
                    <h1 class="text-3xl md:text-5xl font-black leading-[1.15] text-gray-900">
                        خانه‌ات را با <span
                            class="bg-gradient-to-r from-yellow-500 to-green-600 bg-clip-text text-transparent">انرژی
                            خورشیدی</span>
                        روشن کن.
                    </h1>
                    <p class="text-gray-700 md:text-lg leading-8">
                        قبض برق کمتر، خانه‌ای سبزتر و خیالت راحت‌تر؛ ما مسیرت را از صفر تا نور همراهی می‌کنیم—مشاوره
                        رایگان، طراحی هوشمند، نصب حرفه‌ای.
                    </p>

                    <!-- تنها دکمه صفحه -->
                    <div>
                        <a id="startBtn"
                            href="{{ route('simpleWorkflow.process.start', [
                                'taskId' => '5c81c968-6d70-4285-9950-8ce972f23fdd',
                                'inDraft' => 0,
                                'force' => 1,
                                'redirect' => true,
                            ]) }}"
                            class="inline-flex items-center justify-center px-8 py-3 rounded-2xl text-base md:text-lg font-bold text-white bg-gradient-to-r from-green-600 to-yellow-500 shadow-lg shadow-yellow-500/30 hover:shadow-yellow-500/50 transition-transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-yellow-300">
                            شروع کنید
                            <svg class="ms-2" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </div>

                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500"></span>
                            صرفه‌جویی تا ۷۰٪</div>
                        <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                            پشتیبانی ۲۴/۷</div>
                        <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-blue-400"></span>
                            گارانتی واقعی</div>
                    </div>
                </div>

                <!-- تصویر مفهومی پنل‌ها -->
                <div class="relative">
                    <div class="glass rounded-3xl p-6 md:p-8 shadow-2xl floating">
                        <div
                            class="aspect-[16/10] rounded-2xl overflow-hidden bg-gradient-to-tr from-sky-100 to-emerald-100 grid place-items-center">
                            <!-- پنل خورشیدی SVG -->
                            <svg viewBox="0 0 600 360" class="w-full h-full">
                                <defs>
                                    <linearGradient id="panel" x1="0" x2="0" y1="0"
                                        y2="1">
                                        <stop offset="0%" stop-color="#c7eaff" />
                                        <stop offset="100%" stop-color="#a7f3d0" />
                                    </linearGradient>
                                </defs>
                                <!-- خورشید -->
                                <circle cx="520" cy="60" r="40" fill="#fde047" stroke="#f59e0b"
                                    stroke-width="4" />
                                <!-- زمین -->
                                <rect x="0" y="260" width="600" height="120" fill="#bbf7d0" />
                                <!-- پنل‌ها -->
                                <g transform="translate(100,210) rotate(-12)">
                                    <rect x="0" y="0" width="240" height="120" rx="10" fill="url(#panel)"
                                        stroke="#334155" stroke-width="6" />
                                    <!-- خانه‌های پنل -->
                                    <g transform="translate(16,16)">
                                        <!-- 3x5 grid -->
                                        <g fill="#93c5fd" stroke="#1f2937" stroke-width="2" opacity=".9">
                                            <!-- rows -->
                                            <rect x="0" y="0" width="60" height="26" />
                                            <rect x="68" y="0" width="60" height="26" />
                                            <rect x="136" y="0" width="60" height="26" />

                                            <rect x="0" y="34" width="60" height="26" />
                                            <rect x="68" y="34" width="60" height="26" />
                                            <rect x="136" y="34" width="60" height="26" />

                                            <rect x="0" y="68" width="60" height="26" />
                                            <rect x="68" y="68" width="60" height="26" />
                                            <rect x="136" y="68" width="60" height="26" />
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <div class="mt-4 grid grid-cols-3 gap-3 text-center text-sm text-gray-700">
                            <div class="bg-white/70 rounded-xl p-3 shadow">نصب سریع</div>
                            <div class="bg-white/70 rounded-xl p-3 shadow">تضمین بازده</div>
                            <div class="bg-white/70 rounded-xl p-3 shadow">پشتیبانی هوشمند</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- بخش اعتبار اجتماعی و مزایا -->
    <main id="start" class="relative z-10">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid md:grid-cols-3 gap-6">
                <div class="glass rounded-2xl p-6 shadow-lg">
                    <h3 class="text-xl font-black text-gray-900 mb-2">صرفه‌جویی واقعی در قبض</h3>
                    <p class="text-gray-700 leading-7">با بهره‌گیری از پنل‌های نسل جدید، هزینه برق شما به شکل محسوسی کاهش
                        می‌یابد و سرمایه‌گذاری‌تان در مدت کوتاهی بازمی‌گردد.</p>
                </div>
                <div class="glass rounded-2xl p-6 shadow-lg">
                    <h3 class="text-xl font-black text-gray-900 mb-2">قدرتی پاک و پایدار</h3>
                    <p class="text-gray-700 leading-7">به آینده‌ای سبز سلام کنید؛ انرژی خورشیدی بدون آلودگی و با کمترین
                        نیاز به نگهداری در اختیار شماست.</p>
                </div>
                <div class="glass rounded-2xl p-6 shadow-lg">
                    <h3 class="text-xl font-black text-gray-900 mb-2">همراهی از مشاوره تا روشنایی</h3>
                    <p class="text-gray-700 leading-7">از ایده تا اجرا کنار شما هستیم؛ بررسی محل، طراحی، نصب و پشتیبانی،
                        همه با یک تیم حرفه‌ای.</p>
                </div>
            </div>

            <div class="mt-10 grid md:grid-cols-4 gap-4 text-center">
                <div class="rounded-2xl bg-white/70 p-5 shadow">
                    <div class="text-3xl font-extrabold text-gray-900">+۱۲٬۰۰۰</div>
                    <div class="text-gray-600">ساعت نور ذخیره‌شده</div>
                </div>
                <div class="rounded-2xl bg-white/70 p-5 shadow">
                    <div class="text-3xl font-extrabold text-gray-900">۹۸٪</div>
                    <div class="text-gray-600">رضایت کاربران</div>
                </div>
                <div class="rounded-2xl bg-white/70 p-5 shadow">
                    <div class="text-3xl font-extrabold text-gray-900">–۷۰٪</div>
                    <div class="text-gray-600">کاهش هزینه برق</div>
                </div>
                <div class="rounded-2xl bg-white/70 p-5 shadow">
                    <div class="text-3xl font-extrabold text-gray-900">۲۴/۷</div>
                    <div class="text-gray-600">پشتیبانی همیشه‌آنلاین</div>
                </div>
            </div>
        </section>

        <!-- نقل‌قول الهام‌بخش -->
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-14">
            <div class="glass rounded-3xl p-8 md:p-10 shadow-xl text-center">
                <p class="text-xl md:text-2xl leading-9 text-gray-800">
                    «هر پنل، قدمی است به سوی هوایی پاک‌تر و آینده‌ای روشن‌تر. امروز انتخاب کن تا فردا شگفت‌زده شوی.»
                </p>
                <div class="mt-4 text-sm text-gray-500">— تیم گروه صنعتی بهین انرژی</div>
            </div>
        </section>
    </main>
</main>
@endsection
