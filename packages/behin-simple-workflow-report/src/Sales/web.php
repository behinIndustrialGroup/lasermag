<?php

use Behin\SimpleWorkflowReport\Sales\Controllers\SaleReportController;
use Illuminate\Support\Facades\Route;

Route::name('sales.')->prefix('sales')->middleware(['web', 'auth', 'access:گزارش فرایندهای فروش'])->group(function () {
    Route::get('/', [SaleReportController::class, 'index'])->name('index');
    Route::get('{case}/edit', [SaleReportController::class, 'edit'])->name('edit');
});
