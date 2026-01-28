<?php

use Behin\SimpleWorkflowReport\Purchases\Controllers\PurchaseReportController;
use Illuminate\Support\Facades\Route;

Route::name('purchases.')->prefix('purchases')->middleware(['web', 'auth', 'access:گزارش فرایندهای خرید'])->group(function () {
    Route::get('', [PurchaseReportController::class, 'index'])->name('index');
    Route::get('{case}/edit', [PurchaseReportController::class, 'edit'])->name('edit');
});
