@extends('behin-layouts.welcome')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #eeee23 0%, #2575fc 100%);
        font-family: 'IRANSans', sans-serif;
    }

    .register-card {
        backdrop-filter: blur(15px);
        background: rgba(255, 255, 255, 0.85);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease-in-out;
    }
    .register-card:hover {
        transform: translateY(-5px);
    }

    .form-control {
        border: none;
        border-bottom: 2px solid #ccc;
        border-radius: 0;
        background: transparent;
        box-shadow: none !important;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-bottom: 2px solid #2575fc;
        outline: none;
    }

    .btn-gradient {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        border: none;
        color: #fff;
        font-weight: bold;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37,117,252,0.3);
    }

    .floating-label {
        position: absolute;
        top: 12px;
        right: 10px;
        font-size: 14px;
        color: #777;
        transition: 0.3s;
        pointer-events: none;
    }
    .form-control:focus + .floating-label,
    .form-control:not(:placeholder-shown) + .floating-label {
        top: -8px;
        right: 0;
        font-size: 12px;
        color: #2575fc;
    }
</style>

<div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="card register-card p-4" style="max-width: 420px; width: 100%;">
        <!-- لوگو -->
        <div class="text-center mb-4">
            <img src="{{ url('behin/logo.webp') . '?' . config('app.version')}}" class="img-fluid" style="max-height: 70px" alt="Logo">
        </div>

        <h4 class="text-center mb-4 fw-bold text-dark">ثبت نام</h4>
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            

        <form id="register-form" method="POST" action="{{ route('register') }}">
            @csrf

            <!-- نام و نام خانوادگی -->
            <div class="mb-4 position-relative">
                <input type="text" name="name" class="form-control" id="inputName" placeholder=" " required>
                <label for="inputName" class="floating-label"><i class="fa fa-user me-1"></i> نام و نام خانوادگی</label>
            </div>

            <!-- موبایل -->
            <div class="mb-4 position-relative">
                <input type="text" name="email" class="form-control"
                 id="inputMobile" 
                 placeholder=" "
                 value="{{ request()->get('mobile') }}"
                 required>
                <label for="inputMobile" class="floating-label"><i class="fa fa-phone me-1"></i> موبایل</label>
            </div>

            <!-- رمز عبور -->
            <div class="mb-4 position-relative">
                <input type="password" name="password" class="form-control" id="inputPassword" placeholder=" " required>
                <label for="inputPassword" class="floating-label"><i class="fa fa-lock me-1"></i> رمز عبور</label>
            </div>

            <!-- دکمه ثبت نام -->
            <button type="submit" class="btn btn-gradient w-100 py-3">
                ثبت نام
            </button>
        </form>

        <!-- لینک‌ها -->
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="d-block small text-decoration-none text-primary">صفحه ورود</a>
        </div>

        <!-- اینماد -->
        <div class="mt-4 text-center">
            @include('auth.partial.enamad-and-version')
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
