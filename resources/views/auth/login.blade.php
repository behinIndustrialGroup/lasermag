@extends('behin-layouts.welcome')

@php($colors = config('theme.colors'))

@section('style')
    <style>
        :root {
            --color-primary: {{ $colors['primary'] }};
            --color-secondary: {{ $colors['secondary'] }};
            --color-accent: {{ $colors['accent'] }};
            --color-dark: {{ $colors['dark'] }};
            --color-surface: {{ $colors['surface'] }};
            --color-muted: {{ $colors['muted'] }};
        }

        body {
            background: radial-gradient(circle at top left, var(--color-secondary) 0%, transparent 45%),
                radial-gradient(circle at bottom right, var(--color-primary) 0%, transparent 50%),
                var(--color-dark);
            font-family: 'IRANSans', sans-serif;
            direction: rtl;
        }

        .container-login100 {
            padding: 40px 20px;
        }

        .login-wrapper {
            max-width: 1080px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            align-items: stretch;
        }

        .login-hero {
            position: relative;
            background: linear-gradient(135deg, rgba(255, 51, 102, 0.85), rgba(58, 134, 255, 0.85));
            border-radius: 28px;
            padding: 40px 32px;
            color: var(--color-surface);
            box-shadow: 0 30px 60px rgba(11, 19, 43, 0.35);
            overflow: hidden;
        }

        .login-hero::after {
            content: "";
            position: absolute;
            inset: 20px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            pointer-events: none;
        }

        .login-hero h1 {
            font-size: 2.25rem;
            font-weight: 800;
            line-height: 1.4;
            margin-bottom: 1.5rem;
        }

        .login-hero p {
            font-size: 1.05rem;
            line-height: 1.9;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.85);
        }

        .login-hero .highlight-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 214, 10, 0.15);
            border: 1px solid rgba(255, 214, 10, 0.35);
            color: var(--color-accent);
            border-radius: 999px;
            padding: 0.4rem 1.2rem;
            font-weight: 600;
            margin-bottom: 2rem;
        }

        .laser-beam {
            position: absolute;
            inset-inline-end: -60px;
            top: 40px;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle at center, rgba(255, 214, 10, 0.8), rgba(255, 214, 10, 0) 70%);
            filter: blur(0px);
            opacity: 0.75;
        }

        .feature-list {
            display: grid;
            gap: 1rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .feature-icon {
            inline-size: 12px;
            block-size: 12px;
            border-radius: 4px;
            background: var(--color-accent);
            margin-top: 7px;
            box-shadow: 0 0 15px rgba(255, 214, 10, 0.6);
        }

        .feature-item span {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.85);
        }

        .login-card {
            background: linear-gradient(145deg, rgba(245, 247, 250, 0.9), rgba(255, 255, 255, 0.85));
            border-radius: 28px;
            padding: 40px 36px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 25px 45px rgba(11, 19, 43, 0.28);
            backdrop-filter: blur(14px);
        }

        .login-card::before {
            content: "";
            position: absolute;
            inset-inline-start: -60px;
            inset-block-start: -60px;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(58, 134, 255, 0.25), transparent 60%);
            z-index: 0;
        }

        .login-card::after {
            content: "";
            position: absolute;
            inset-inline-end: -40px;
            inset-block-end: -40px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255, 51, 102, 0.2), transparent 60%);
            z-index: 0;
        }

        .login-card > * {
            position: relative;
            z-index: 1;
        }

        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .login-logo img {
            max-height: 80px;
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 0.75rem;
            color: var(--color-dark);
        }

        .login-subtitle {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--color-muted);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            color: var(--color-dark);
        }

        .form-control {
            border-radius: 16px;
            border: 1px solid rgba(11, 19, 43, 0.08);
            padding: 0.9rem 1rem;
            background: rgba(255, 255, 255, 0.85);
            transition: border 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--color-secondary);
            box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.15);
        }

        .form-check-input {
            width: 1.1rem;
            height: 1.1rem;
            border-radius: 6px;
            border-color: rgba(11, 19, 43, 0.25);
        }

        .btn-login {
            width: 100%;
            padding: 0.95rem;
            border-radius: 18px;
            border: none;
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--color-surface);
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            box-shadow: 0 18px 35px rgba(255, 51, 102, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 24px 45px rgba(58, 134, 255, 0.35);
        }

        .login-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .login-links a {
            color: var(--color-secondary);
            text-decoration: none;
            font-weight: 600;
        }

        .alert {
            border-radius: 16px;
            padding: 0.9rem 1rem;
            font-size: 0.9rem;
        }

        .status-message {
            background: rgba(58, 134, 255, 0.1);
            border: 1px solid rgba(58, 134, 255, 0.25);
            color: var(--color-secondary);
            border-radius: 14px;
            padding: 0.85rem 1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 991px) {
            .container-login100 {
                padding: 30px 15px;
            }

            .login-hero {
                order: -1;
            }
        }
    </style>
@endsection

@section('content')
    <div class="login-wrapper">
        <section class="login-card">
            <div class="login-logo">
                <img src="{{ url('behin/logo.png') . '?' . config('app.version') }}" alt="LaserMag Logo">
            </div>

            <h2 class="login-title">ورود به لیزر مگ</h2>
            <p class="login-subtitle">برای دسترسی به تازه‌ترین مقاله‌های تخصصی و فروشگاه محصولات لیزری وارد شوید.</p>

            @if (session('status'))
                <div class="status-message">{{ session('status') }}</div>
            @endif

            @isset($error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endisset

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label">ایمیل</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required
                        autofocus autocomplete="username">
                    @error('email')
                        <div class="text-danger mt-2 small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">رمز عبور</label>
                    <input id="password" class="form-control" type="password" name="password" required
                        autocomplete="current-password">
                    @error('password')
                        <div class="text-danger mt-2 small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                        <label class="form-check-label" for="remember_me">مرا به خاطر بسپار</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="text-decoration-none" href="{{ route('password.request') }}">رمز عبور را فراموش کرده‌اید؟</a>
                    @endif
                </div>

                <button type="submit" class="btn-login">ورود به حساب کاربری</button>
            </form>

            <div class="login-links">
                <span>حساب کاربری ندارید؟</span>
                <a href="{{ route('register') }}">ثبت‌نام کنید</a>
            </div>

            <div class="mt-4 text-center">
                @include('auth.partial.enamad-and-version')
            </div>
        </section>

        <section class="login-hero">
            <div class="laser-beam" aria-hidden="true"></div>
            <div class="highlight-tag">
                <span>مجله و فروشگاه تخصصی لیزر</span>
            </div>
            <h1>دنیای نور را با <span style="color: var(--color-accent);">لیزر مگ</span> تجربه کنید</h1>
            <p>
                در لیزر مگ به جدیدترین مقالات، تحلیل‌های فناوری و تجهیزات تخصصی لیزر دسترسی دارید.
                تیم ما هر روز مجموعه‌ای از محصولات حرفه‌ای و ویژه‌ی حوزه لیزر را برای شما فراهم می‌کند.
            </p>
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon"></div>
                    <span>ارسال سریع محصولات پرکاربرد صنعتی و آزمایشگاهی</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"></div>
                    <span>دسترسی نامحدود به آرشیو مقاله‌ها و خبرنامه تخصصی</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"></div>
                    <span>پیشنهادهای شخصی‌سازی شده با توجه به حوزه فعالیت شما</span>
                </div>
            </div>
        </section>
    </div>
@endsection
