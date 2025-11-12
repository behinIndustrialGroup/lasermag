<?php
use App\CustomClasses\Access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use UserNotifications\Models\Notification;

$unreadNotificationsCount = Auth::check()
    ? Notification::unreadFor(Auth::id())->count()
    : 0;

$notificationsUrl = Route::has('notifications.index')
    ? route('notifications.index')
    : '#';
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand" style="background-color: #f9f9f9 !important; color: #fff; border-bottom: none;box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">

    <!-- Left navbar links -->
    <ul class="navbar-nav align-items-center">
        <!-- Menu Toggle -->
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="pushmenu" href="#">
                <i class="material-icons">menu</i>
            </a>
        </li>

        <!-- Send SMS -->
        @if (access('send-sms'))
            <li class="nav-item">
                <a href="{{ url('admin/send-sms') }}" class="btn btn-sm btn-outline-light ms-2">
                    <i class="material-icons" style="font-size:18px;">sms</i>
                    ارسال پیامک
                </a>
            </li>
        @endif

        <!-- Test Notification -->
        {{-- <li class="nav-item d-none d-md-block">
            <a href="{{ route('send-notification') }}" class="btn btn-sm btn-warning">
                <i class="fa fa-bell"></i>
                تست نوتیفیکیشن
            </a>
        </li> --}}
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ms-auto align-items-center" >

        <!-- Refresh -->
        <li class="nav-item mr-2">
            <a href="{{ $notificationsUrl }}" class="btn btn-sm btn-outline-dark notification-button" style="color: black !important" title="{{ __('پیام‌ها') }}">
                <i class="material-icons" style="font-size: 20px;">notifications</i>
                <span class="sr-only">{{ __('پیام‌ها') }}</span>
                @if($unreadNotificationsCount > 0)
                    <span class="notification-badge">
                        {{ $unreadNotificationsCount }}
                    </span>
                @endif
            </a>
        </li>

        {{-- <li class="mr-2">
            <a href="tel:+982191307571" type="button" class="btn btn-sm btn-outline-dark" style="color: black !important">
                پشتیبانی: 02191307571 <i class="material-icons" style="font-size: 13px">phone</i>
            </a>
        </li> --}}

        <!-- Home -->
        {{-- <li class="mr-2">
            <a href="{{ url('admin') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-home"></i>
            </a>
        </li> --}}

        <!-- Todo List -->
        {{-- @include('TodoListViews::partial-views.todo-list-icon') --}}

        <!-- User Profile -->
        {{-- @include('UserProfileViews::partial-views.user-profile-icon') --}}

        <!-- Logout -->
        {{-- <li class="mr-2">
            <button class="btn btn-sm btn-danger">
                <a href="{{ route('logout') }}">
                    خروج <i class="fa fa-sign-out"></i>
                </a>
            </button>
        </li> --}}
    </ul>
</nav>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
    .navbar .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .navbar .btn-outline-light:hover {
        background: rgba(255,255,255,0.1);
        color: #fff;
    }
    .navbar .btn-warning {
        color: #000;
        font-weight: 600;
    }
    .navbar .nav-link:hover {
        background: rgba(255,255,255,0.08);
        border-radius: 8px;
    }
    .navbar-expand{
        justify-content:space-between !important;
    }
    .notification-button {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
    }
    .notification-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        min-width: 18px;
        height: 18px;
        padding: 0 4px;
        font-size: 11px;
        font-weight: 600;
        color: #fff;
        background-color: #dc3545;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
</style>
