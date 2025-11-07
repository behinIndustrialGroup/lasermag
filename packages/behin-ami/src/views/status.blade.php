@extends('behin-layouts.app')

@section('title')
    وضعیت داخلی‌ها
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div>
                    <h2 class="h4 mb-1">داشبورد وضعیت داخلی‌ها</h2>
                    <p class="text-muted mb-0">گزارش لحظه‌ای ثبت‌نام و پاسخ‌دهی داخلی‌ها در Asterisk.</p>
                </div>
                @php
                    $onlineCount = collect($peers)->filter(fn($peer) => str_contains(strtolower($peer['status'] ?? ''), 'ok'))->count();
                    $totalPeers = count($peers);
                @endphp
                <div class="mt-3 mt-md-0">
                    <span class="badge badge-success mr-1">آنلاین: {{ $onlineCount }}</span>
                    <span class="badge badge-secondary">کل داخلی‌ها: {{ $totalPeers }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-3">
            <div class="card card-primary card-outline h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">لیست داخلی‌ها</h3>
                    <small class="text-muted">آخرین بروزرسانی: {{ now()->format('Y/m/d H:i') }}</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" style="width: 100px;">داخلی</th>
                                    <th>آدرس IP</th>
                                    <th>وضعیت</th>
                                    <th class="text-left">آخرین پاسخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peers as $peer)
                                    @php
                                        $isOnline = str_contains(strtolower($peer['status'] ?? ''), 'ok');
                                    @endphp
                                    <tr>
                                        <td class="text-center font-weight-bold align-middle">{{ $peer['objectname'] }}</td>
                                        <td class="align-middle">{{ $peer['ipaddress'] ?? 'بدون IP ثبت شده' }}</td>
                                        <td class="align-middle">
                                            <span class="badge {{ $isOnline ? 'badge-success' : 'badge-danger' }}">
                                                {{ $isOnline ? 'آنلاین' : 'آفلاین' }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-left">
                                            <small class="text-muted d-block">{{ $peer['status'] }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">اطلاعاتی برای نمایش وجود ندارد.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">تحلیل سریع</h3>
                </div>
                <div class="card-body">
                    <ul class="pl-3 mb-0 text-muted">
                        <li class="mb-2">برای داخلی‌های آفلاین، وضعیت شبکه و ثبت‌نام SIP را بررسی کنید.</li>
                        <li class="mb-2">دسترسی فایروال به پورت‌های سیگنالینگ و RTP را کنترل کنید.</li>
                        <li class="mb-0">در کنسول Asterisk فرمان <code class="text-sm">sip show peers</code> را اجرا کنید.</li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title mb-0">پیشنهادها برای پایش</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">برای نظارت مستمر می‌توانید از موارد زیر استفاده کنید:</p>
                    <ul class="pl-3 mb-0 text-muted small">
                        <li class="mb-1">راه‌اندازی بروزرسانی خودکار یا Push Notification برای داشبورد.</li>
                        <li class="mb-1">ذخیره لاگ‌های AMI جهت تحلیل خطاها.</li>
                        <li class="mb-0">تعریف آستانه هشدار برای تعداد داخلی‌های آفلاین.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
