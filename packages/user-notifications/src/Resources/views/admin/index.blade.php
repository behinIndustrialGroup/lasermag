@extends('behin-layouts.app')

@section('style')
    <style>
        .notifications-hero {
            background: linear-gradient(135deg, #0f4c81 0%, #1d8cf8 100%);
            border-radius: 18px;
            color: #fff;
            padding: 28px 32px;
            box-shadow: 0 20px 45px rgba(15, 76, 129, 0.35);
        }

        .notifications-hero h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .notifications-hero p {
            margin: 0;
            opacity: 0.9;
            font-size: .95rem;
        }

        .notifications-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 16px 32px rgba(20, 69, 120, 0.12);
            overflow: hidden;
        }

        .notifications-table thead {
            background: #f3f6fb;
        }

        .notifications-table th {
            color: #3a4a66;
            font-size: .82rem;
            letter-spacing: .04em;
        }

        .notifications-table tbody tr {
            transition: all .2s ease-in-out;
        }

        .notifications-table tbody tr:hover {
            transform: translateY(-1px);
            box-shadow: inset 5px 0 0 #1d8cf8;
            background-color: #f8fbff;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #1d8cf8 100%);
            border: none;
            color: #fff !important;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.3);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .btn-primary-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.35);
        }

        .notifications-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #2563eb 0%, #1d8cf8 100%);
            border-color: transparent;
        }

        .notifications-pagination .page-link {
            border-radius: 10px;
            margin: 0 4px;
            color: #1f3a60;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="notifications-hero mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h1>{{ __('مدیریت پیام‌ها') }}</h1>
                <p>{{ __('کنترل و پایش پیام‌های ارسالی برای کاربران سامانه') }}</p>
            </div>
            <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary-gradient px-4 py-2 rounded-pill d-inline-flex align-items-center gap-2">
                <i class="fa fa-paper-plane"></i>
                <span>{{ __('ایجاد پیام جدید') }}</span>
            </a>
        </div>

        <div class="card notifications-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 notifications-table align-middle">
                        <thead>
                            <tr>
                                <th scope="col" class="text-end px-4 py-3">{{ __('عنوان') }}</th>
                                <th scope="col" class="text-end px-4 py-3">{{ __('ارسال کننده') }}</th>
                                <th scope="col" class="text-end px-4 py-3">{{ __('دریافت کننده') }}</th>
                                <th scope="col" class="text-end px-4 py-3">{{ __('تاریخ ارسال') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($notifications as $notification)
                                <tr>
                                    <td class="px-4 py-3 fw-semibold text-dark">{{ $notification->title }}</td>
                                    <td class="px-4 py-3 text-muted">{{ optional($notification->sender)->name ?? __('سیستم') }}</td>
                                    <td class="px-4 py-3 text-muted">
                                        @if($notification->receiver_id)
                                            {{ optional($notification->receiver)->name ?? __('کاربر حذف شده') }}
                                        @else
                                            {{ $notification->receiver_role === 'all' ? __('همه کاربران') : __('چند کاربر/نقش') }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-muted">{{ optional($notification->created_at)->format('Y/m/d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-muted">{{ __('پیامی ارسال نشده است.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="notifications-pagination mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
