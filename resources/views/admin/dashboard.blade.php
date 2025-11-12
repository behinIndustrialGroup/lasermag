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

    </div>
@endsection
