@extends('behin-layouts.app')

@section('style')
    <style>
        .notification-detail-wrapper {
            max-width: 900px;
            margin: 0 auto;
        }

        .notification-detail-card {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(15, 76, 129, 0.18);
        }

        .notification-detail-header {
            background: linear-gradient(135deg, #0f4c81 0%, #1d8cf8 100%);
            padding: 34px 40px;
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .notification-detail-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .notification-detail-header span {
            font-size: .95rem;
            opacity: .85;
        }

        .notification-detail-body {
            background: #fff;
            padding: 36px 40px 42px;
        }

        .notification-content {
            font-size: 1rem;
            line-height: 1.9;
            color: #1f3a60;
        }

        .btn-outline-material {
            border-radius: 50px;
            border: 2px solid #2563eb;
            color: #2563eb;
            font-weight: 600;
            padding: 10px 24px;
            transition: all .2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline-material:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d8cf8 100%);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 16px 28px rgba(37, 99, 235, 0.25);
        }

        .btn-solid-material {
            border: none;
            border-radius: 50px;
            background: linear-gradient(135deg, #2563eb 0%, #1d8cf8 100%);
            color: #fff;
            font-weight: 600;
            padding: 10px 28px;
            box-shadow: 0 18px 32px rgba(37, 99, 235, 0.25);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .btn-solid-material:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 36px rgba(37, 99, 235, 0.3);
        }

        .notification-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="notification-detail-wrapper">
            <div class="notification-detail-card">
                <div class="notification-detail-header">
                    <h1>{{ $notification->title }}</h1>
                    <span>{{ __('ارسال شده در :date', ['date' => optional($notification->created_at)->format('Y/m/d H:i')]) }}</span>
                </div>
                <div class="notification-detail-body">
                    <div class="notification-content">
                        {!! nl2br(e($notification->message)) !!}
                    </div>

                    <div class="notification-actions mt-4">
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-material">
                            <i class="fa fa-arrow-right"></i>
                            {{ __('بازگشت به لیست پیام‌ها') }}
                        </a>

                        @if(!$notification->is_read)
                            <form action="{{ route('notifications.mark', $notification) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-solid-material">
                                    <i class="fa fa-check ms-1"></i>
                                    {{ __('علامت به عنوان خوانده شده') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
