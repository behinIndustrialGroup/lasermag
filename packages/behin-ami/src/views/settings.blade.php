@extends('behin-layouts.app')

@section('title')
    تنظیمات اتصال AMI
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 mb-3">
            <div class="card card-primary card-outline h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title mb-0">پیکربندی اتصال Asterisk Manager Interface</h3>
                        <p class="mb-0 text-muted small">برای اعمال تغییرات فرم را تکمیل و ذخیره کنید.</p>
                    </div>
                    <span class="badge badge-primary">AMI</span>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('ami.settings.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="ami-host" class="font-weight-bold">هاست</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-server"></i></span>
                                    </div>
                                    <input type="text" name="host" id="ami-host" class="form-control"
                                           value="{{ old('host', $setting->host ?? '127.0.0.1') }}"
                                           placeholder="برای نمونه 127.0.0.1">
                                </div>
                                <small class="form-text text-muted">آدرس سرور Asterisk را وارد کنید.</small>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ami-port" class="font-weight-bold">پورت</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-plug"></i></span>
                                    </div>
                                    <input type="text" name="port" id="ami-port" class="form-control"
                                           value="{{ old('port', $setting->port ?? 5038) }}"
                                           placeholder="پورت پیش‌فرض ۵۰۳۸ است">
                                </div>
                                <small class="form-text text-muted">در صورت تغییر پورت، مقدار جدید را وارد کنید.</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="ami-username" class="font-weight-bold">نام کاربری</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    </div>
                                    <input type="text" name="username" id="ami-username" class="form-control"
                                           value="{{ old('username', $setting->username ?? '') }}"
                                           placeholder="نام کاربری تعریف شده در manager.conf">
                                </div>
                                <small class="form-text text-muted">نام کاربری تعیین شده برای دسترسی مدیریتی.</small>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ami-password" class="font-weight-bold">رمز عبور</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password" id="ami-password" class="form-control"
                                           value="{{ old('password', $setting->password ?? '') }}"
                                           placeholder="رمز عبور قدرتمند وارد کنید">
                                </div>
                                <small class="form-text text-muted">برای امنیت بیشتر، از ترکیب حروف و اعداد استفاده کنید.</small>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mt-4">
                            <small class="text-muted">آخرین بروزرسانی: {{ now()->format('Y/m/d H:i') }}</small>
                            <button type="submit" class="btn btn-primary mt-2 mt-md-0">
                                <i class="fa fa-save ml-1"></i> ذخیره تنظیمات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">راهنمای اتصال سریع</h3>
                </div>
                <div class="card-body">
                    <ul class="pl-3 mb-0 text-muted">
                        <li class="mb-2">فعال بودن سرویس <strong>manager</strong> در سرور را بررسی کنید.</li>
                        <li class="mb-2">در فایل <code class="text-sm">manager.conf</code> دسترسی IP این سامانه را تعریف کنید.</li>
                        <li class="mb-0">پس از تغییر تنظیمات، سرویس Asterisk را <span class="font-weight-bold">reload</span> کنید.</li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title mb-0">وضعیت ذخیره شده</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">آدرس</span>
                        <span class="font-weight-bold">{{ $setting?->host ?? '---' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">پورت</span>
                        <span class="font-weight-bold">{{ $setting?->port ?? '---' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">نام کاربری</span>
                        <span class="font-weight-bold">{{ $setting?->username ?? '---' }}</span>
                    </div>
                    <hr>
                    <p class="mb-0 text-muted small">در صورت بروز خطا، تنظیمات شبکه و اطلاعات کاربری را کنترل کنید.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
