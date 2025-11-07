<?php

use Illuminate\Support\Facades\Route;
use Behin\Ami\Controllers\AmiSettingController;
use Behin\Ami\Controllers\AmiStatusController;
use Behin\Ami\Controllers\CallRecordingController;

Route::prefix('ami')->middleware(['web', 'auth'])->name('ami.')->group(function () {
    Route::get('settings', [AmiSettingController::class, 'index'])->name('settings');
    Route::post('settings', [AmiSettingController::class, 'store'])->name('settings.store');
    Route::get('status', [AmiStatusController::class, 'index'])->name('status');
    Route::get('calls/recordings/download', [CallRecordingController::class, 'download'])->name('calls.recordings.download');
});
