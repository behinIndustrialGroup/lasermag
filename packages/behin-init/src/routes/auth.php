<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use BehinInit\App\Http\Controllers\LoginController;
use BehinInit\App\Http\Controllers\PasswordResetController;
use BehinInit\App\Http\Controllers\RegisterUserController;
use BehinInit\App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisterUserController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])
                ->name('login');

    Route::post('login', [LoginController::class, 'store']);

    Route::get('forgot-password', [PasswordResetController::class, 'request'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetController::class, 'sendCode'])
                ->name('password.sms');

    Route::get('reset-password', [PasswordResetController::class, 'showResetForm'])
                ->name('password.reset');

    Route::post('reset-password', [PasswordResetController::class, 'reset'])
                ->name('password.reset.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
