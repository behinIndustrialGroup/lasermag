@extends('behin-layouts.app')

@section('style')
    <style>
        .user-notifications-hero {
            background: linear-gradient(135deg, #0f4c81 0%, #1d8cf8 100%);
            border-radius: 22px;
            padding: 26px 32px;
            color: #fff;
            box-shadow: 0 20px 40px rgba(15, 76, 129, 0.28);
        }

        .user-notifications-hero h1 {
            margin-bottom: 6px;
            font-weight: 700;
        }

        .user-notifications-hero p {
            margin: 0;
            opacity: .85;
        }

        .user-notifications-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 24px 44px rgba(31, 58, 96, 0.15);
            overflow: hidden;
        }

        .user-notifications-table thead {
            background-color: #f3f6fb;
        }

        .user-notifications-table th {
            color: #1f3a60;
            font-size: .85rem;
            letter-spacing: .04em;
        }

        .user-notifications-table tbody tr {
            transition: all .2s ease;
        }

        .user-notifications-table tbody tr.unread {
            background: rgba(29, 140, 248, 0.08);
        }

        .user-notifications-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: inset 4px 0 0 #1d8cf8;
            background-color: #f8fbff;
        }

        .badge-soft-success {
            background: rgba(34, 197, 94, .15);
            color: #15803d;
            border-radius: 999px;
            padding: .45rem 1.1rem;
            font-weight: 600;
        }

        .badge-soft-danger {
            background: rgba(248, 113, 113, .18);
            color: #b91c1c;
            border-radius: 999px;
            padding: .45rem 1.1rem;
            font-weight: 600;
        }

        .link-material {
            color: #2563eb;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: gap .2s ease;
        }

        .link-material:hover {
            color: #1740ad;
            gap: 10px;
        }

        .notifications-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #2563eb 0%, #1d8cf8 100%);
            border-color: transparent;
        }

        .notifications-pagination .page-link {
            border-radius: 12px;
            margin: 0 4px;
            color: #1f3a60;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="user-notifications-hero d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">{{ __('پیام‌های شما') }}</h1>
                <p>{{ __('همه اعلان‌های دریافت شده از سوی سامانه را در یک نگاه مدیریت کنید') }}</p>
            </div>
            <x-user-notifications::unread-indicator :count="$unreadCount" />
        </div>

        <div class="card user-notifications-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table user-notifications-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th scope="col" class="text-end px-4 py-3">{{ __('عنوان') }}</th>
                                <th scope="col" class="text-end px-4 py-3">{{ __('تاریخ ارسال') }}</th>
                                <th scope="col" class="text-end px-4 py-3">{{ __('وضعیت') }}</th>
                                <th scope="col" class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($notifications as $notification)
                                <tr class="{{ $notification->is_read ? '' : 'unread' }}">
                                    <td class="px-4 py-3 fw-semibold text-dark">{{ $notification->title }}</td>
                                    <td class="px-4 py-3 text-muted">{{ optional($notification->created_at)->format('Y/m/d H:i') }}</td>
                                    <td class="px-4 py-3">
                                        @if($notification->is_read)
                                            <span class="badge-soft-success">{{ __('خوانده شده') }}</span>
                                        @else
                                            <span class="badge-soft-danger">{{ __('خوانده نشده') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-start">
                                        <a href="{{ route('notifications.show', $notification) }}" class="link-material">
                                            <span>{{ __('مشاهده') }}</span>
                                            <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-muted">{{ __('پیامی برای نمایش وجود ندارد.') }}</td>
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
