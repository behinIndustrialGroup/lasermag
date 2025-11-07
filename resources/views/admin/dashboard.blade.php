@extends('behin-layouts.app')

@php
    $disableBackBtn = false;
@endphp

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .mobile-tile {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .mobile-tile span {
            font-size: 0.85rem;
            font-weight: 500;
            color: #333;
        }

        .bordered-row {
            border: solid #ddd;
            padding: 5px 0 5px 0;
            border-radius: 10px;
            margin-bottom: 5px;
        }

        .rounded-4 {
            border-radius: 10px;
        }

        .carousel-inner {
            height: 400px;
        }

        @media (max-width: 767.98px) {
            .icon-circle {
                width: 60px;
                height: 60px;
                /* font-size: 24px; */
            }

            .mobile-tile span {
                font-size: 0.75rem;
            }

            .carousel-inner {
                height: auto;
            }

        }
    </style>
    <div class="container py-4">
        <div class="row g-3 text-center justify-content-center mb-2">
            <div class="col-12" style="padding: 0px">
                <div class="hero-banner rounded-4 shadow-sm text-white text-center p-5">
                    <h2 class="fw-bold mb-3 animate__animated animate__fadeInDown">
                        ستاپ
                    </h2>
                    <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                        خیلی راحت می‌توانید درخواست خود را ثبت کنید و به پیمانکار مربوطه متصل شوید.
                    </p>
                    <a href="{{ route('simpleWorkflow.process.start', [
                        'taskId' => 'cf8147ed-042e-49a9-a9cf-04b7591a4eca',
                        'force' => 1,
                        'redirect' => 1,
                        'inDraft' => 0,
                    ]) }}"
                        class="btn btn-warning btn-lg rounded-pill px-4 animate__animated animate__pulse animate__infinite">
                        🚀 ثبت درخواست
                    </a>
                </div>
            </div>
        </div>

        <!-- اضافه کردن Animate.css از CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

        <style>
            .hero-banner {
                background: linear-gradient(135deg, #ffdd55 0%, #2196f3 100%);
                min-height: 250px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                transition: transform 0.3s ease;
                padding: 3rem;
                /* پیش‌فرض دسکتاپ */
            }

            .hero-banner:hover {
                transform: scale(1.02);
            }

            /* حالت موبایل */
            @media (max-width: 576px) {
                .hero-banner {
                    padding: 1.5rem;
                    /* کوچیک‌تر */
                    min-height: 180px;
                    /* ارتفاع کمتر */
                }

                .hero-banner h2 {
                    font-size: 1.2rem;
                    /* متن تیتر کوچیک‌تر */
                }

                .hero-banner p {
                    font-size: 0.9rem;
                    /* متن توضیحی کوچیک‌تر */
                }

                .hero-banner a.btn {
                    font-size: 0.9rem;
                    /* دکمه جمع‌وجورتر */
                    padding: 0.4rem 1rem;
                }
            }
        </style>

        <div class="row g-3 text-center justify-content-center bordered-row">
            @if (auth()->user()->access('ثبت درخواست احداث نیروگاه'))
                <div class="col-4 col-md-3">
                    <a href="{{ route('simpleWorkflow.process.start', [
                        'taskId' => 'cf8147ed-042e-49a9-a9cf-04b7591a4eca',
                        'force' => 1,
                        'redirect' => 1,
                        'inDraft' => 0,
                    ]) }}"
                        class="mobile-tile text-decoration-none">
                        <div class="text-white">
                            <i class="icon-circle bg-warning bi bi-brightness-high"></i>
                        </div>
                        <span>احداث نیروگاه</span>
                    </a>
                </div>
            @endif

            @if (auth()->user()->access('داشبورد: آیکون شروع فرایند'))
                <div class="col-4 col-md-3">
                    <a href="{{ route('simpleWorkflow.process.startListView') }}" class="mobile-tile text-decoration-none">
                        <div class="text-white">
                            <i class="icon-circle bg-success bi bi-list-task"></i>
                        </div>
                        <span>شروع فرایند</span>
                    </a>
                </div>
            @endif

            @if (auth()->user()->access('داشبورد: درخواست های تکمیل نشده'))
                <div class="col-4 col-md-3">
                    <a href="{{ route('simpleWorkflow.inbox.index') }}" class="mobile-tile text-decoration-none">
                        <div class="text-white">
                            <i class="icon-circle bg-info bi bi-list"></i>
                        </div>
                        <span>درخواست‌های تکمیل نشده</span>
                    </a>
                </div>
            @endif
            @if (auth()->user()->access('داشبورد: درخواست های من'))
                <div class="col-4 col-md-3">
                    <a href="{{ route('simpleWorkflowReport.my-request.index') }}" class="mobile-tile text-decoration-none">
                        <div class="text-white">
                            <i class="icon-circle bg-success bi bi-list"></i>
                        </div>
                        <span>درخواست‌های من</span>
                    </a>
                </div>
            @endif

        </div>
    </div>
@endsection
