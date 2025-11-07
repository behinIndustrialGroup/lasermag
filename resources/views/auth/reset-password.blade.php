@extends('behin-layouts.welcome')

@section('content')
    <div class="register-box">
        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
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

        <div class="card card-outline card-primary">
            <div class="card-header text-center col-sm-12">
                <img src="{{ url('public/behin/logo.png') . '?' . config('app.version') }}" class="col-sm-12" alt="">
            </div>
            <div class="card-body">
                <form action="{{ route('password.reset.store') }}" method="post" id="reset-form">
                    @csrf
                    <input type="hidden" name="mobile" value="{{ session('password_reset_mobile') }}">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="code" 
                        value="{{ old('code') }}" placeholder="کد تایید" autocomplete="new-code">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-key"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="رمز عبور جدید"
                            autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}"
                            placeholder="تکرار رمز عبور" autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" class="btn btn-primary col-sm-12" value="تغییر رمز">
                    </div>
                </form>

                <hr>
                <div style="text-align: center">
                    <a href="{{ route('login') }}" class="text-center">صفحه ورود</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
