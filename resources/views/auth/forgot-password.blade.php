@extends('behin-layouts.welcome')

@section('content')
    <style>
        body {
            background: linear-gradient(135deg, #eeee23 0%, #2575fc 100%);
            font-family: 'IRANSans', sans-serif;
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
        }

        .content-box {
            color: #fff;
        }

        .content-box h1 {
            font-size: 2rem;
            font-weight: bold;
        }

        .features {
            margin-top: 30px;
        }

        .feature-item {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .feature-item i {
            font-size: 22px;
            margin-left: 10px;
            color: #ffd700;
        }

        .login-card {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }

        .login-card:hover {
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
            box-shadow: 0 8px 20px rgba(37, 117, 252, 0.3);
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

        .form-control:focus+.floating-label,
        .form-control:not(:placeholder-shown)+.floating-label {
            top: -8px;
            right: 0;
            font-size: 12px;
            color: #2575fc;
        }
    </style>
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
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center col-sm-12">
                <img src="{{ url('public/behin/logo.png') . '?' . config('app.version') }}" class="col-sm-12" alt="">
            </div>
            <div class="card-body">
                <form action="{{ route('password.sms') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="mobile" 
                        value="{{ old('mobile') }}"
                        placeholder="موبایل"
                        autocomplete="mobile"
                        autofocus
                        inputmode="numeric"
                        aria-invalid="{{ $errors->has('mobile') }}"
                        aria-describedby="mobile-error"
                        dir="ltr">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-phone"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" class="btn btn-primary col-sm-12" value="ارسال کد">
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
