<?php

use Illuminate\Support\Facades\Route;
use UserNotifications\Http\Controllers\NotificationController;

Route::middleware(config('user-notifications.user_middleware', ['web', 'auth']))
    ->prefix('notifications')
    ->name('notifications.')
    ->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark');
    });

Route::middleware(config('user-notifications.admin_middleware', ['web', 'auth']))
    ->prefix('admin/notifications')
    ->name('admin.notifications.')
    ->group(function () {
        Route::get('/', [NotificationController::class, 'adminIndex'])->name('index');
        Route::get('/create', [NotificationController::class, 'create'])->name('create');
        Route::post('/', [NotificationController::class, 'store'])->name('store');
    });
