<?php

use Behin\SimpleWorkflow\Controllers\Core\ConditionController;
use Behin\SimpleWorkflow\Controllers\Core\FieldController;
use Behin\SimpleWorkflow\Controllers\Core\FormController;
use Behin\SimpleWorkflow\Controllers\Core\InboxController;
use Behin\SimpleWorkflow\Controllers\Core\RoutingController;
use Behin\SimpleWorkflow\Controllers\Core\ScriptController;
use Behin\SimpleWorkflow\Controllers\Core\TaskActorController;
use Behin\SimpleWorkflow\Controllers\Core\TaskController;
use Behin\SimpleWorkflow\Models\Core\Cases;
use Behin\SimpleWorkflow\Models\Core\Variable;
use Behin\SimpleWorkflowReport\Controllers\Core\ChequeReportController;
use Behin\SimpleWorkflowReport\Controllers\Core\ExpiredController;
use Behin\SimpleWorkflowReport\Controllers\Core\ExternalAndInternalReportController;
use Behin\SimpleWorkflowReport\Controllers\Core\AllRequestsReportController;
use Behin\SimpleWorkflowReport\Controllers\Core\DraftTransactionController;
use Behin\SimpleWorkflowReport\Controllers\Core\FinancialTransactionController;
use Behin\SimpleWorkflowReport\Controllers\Core\MyRequestController;
use Behin\SimpleWorkflowReport\Controllers\Core\SalesReportController;
use Behin\SimpleWorkflowReport\Controllers\Core\StageReportController;
use Behin\SimpleWorkflowReport\Controllers\Core\RoleReportFormController;
use Behin\SimpleWorkflowReport\Controllers\Core\SummaryReportController;
use Behin\SimpleWorkflowReport\Controllers\Core\PersonelActivityController;
use Behin\SimpleWorkflowReport\Controllers\Core\PhonebookController;
use Behin\SimpleWorkflowReport\Controllers\Core\RecordingController;
use Behin\SimpleWorkflowReport\Controllers\Core\WarehouseReportController;
use Behin\SimpleWorkflowReport\Controllers\Scripts\TotalTimeoff;
use Behin\SimpleWorkflowReport\Controllers\Scripts\UserTimeoffs;
use BehinInit\App\Http\Middleware\Access;
use Illuminate\Support\Facades\Route;

Route::name('simpleWorkflowReport.')->prefix('workflow-report')->middleware(['web', 'auth'])->group(function () {
    Route::resource('summary-report', SummaryReportController::class);

    Route::resource('my-request', MyRequestController::class);
    Route::get('/ami-recording/{uniqueid}', [RecordingController::class, 'streamRecording']);

    Route::get('all-requests/export', [AllRequestsReportController::class, 'export'])->middleware(Access::class. ':گزارش کل درخواست های ثبت شده')->name('all-requests.export');
    Route::get('all-requests/{case_number}', [AllRequestsReportController::class, 'show'])->middleware(Access::class. ':گزارش کل درخواست های ثبت شده')->name('all-requests.show');
    Route::get('all-requests', [AllRequestsReportController::class, 'index'])->middleware(Access::class. ':گزارش کل درخواست های ثبت شده')->name('all-requests.index');

    Route::get('stage-report/export', [StageReportController::class, 'export'])->name('stage-report.export');
    Route::get('stage-report', [StageReportController::class, 'index'])->name('stage-report.index');

    Route::get('financial-transactions/close-user-salary-advances/{counterparty}', [FinancialTransactionController::class, 'closeUserSalaryAdvances'])->name('financial-transactions.closeUserSalaryAdvances')->middleware('access:گزارش دفتر معین');
    Route::get('financial-transactions/user/export', [FinancialTransactionController::class, 'userExport'])->name('financial-transactions.user.export')->middleware('access:گزارش دفتر معین');
    Route::resource('financial-transactions', FinancialTransactionController::class)->middleware('access:گزارش دفتر معین');
    Route::get('financial-transactions/{counterparty}/show-add-credit', [FinancialTransactionController::class, 'showAddCredit'])->name('financial-transactions.showAddCredit')->middleware('access:گزارش دفتر معین');
    Route::get('financial-transactions/{counterparty?}/export', [FinancialTransactionController::class, 'export'])->name('financial-transactions.export')->middleware('access:گزارش دفتر معین');
    Route::post('financial-transactions/add-credit', [FinancialTransactionController::class, 'addCredit'])->name('financial-transactions.addCredit')->middleware('access:گزارش دفتر معین');
    Route::get('financial-transactions/{counterparty}/show-add-debit/{onlyAssignedUsers?}', [FinancialTransactionController::class, 'showAddDebit'])->name('financial-transactions.showAddDebit')->middleware('access:گزارش دفتر معین');
    Route::post('financial-transactions/add-debit', [FinancialTransactionController::class, 'addDebit'])->name('financial-transactions.addDebit')->middleware('access:گزارش دفتر معین');

    Route::name('draftTransaction.')->prefix('draft-transaction')->group(function(){
        Route::get('index/{caseNumber?}', [DraftTransactionController::class,'index'])->name('index');
        Route::get('create/{caseNumber}', [DraftTransactionController::class,'create'])->name('create');
        Route::post('store', [DraftTransactionController::class,'store'])->name('store');
        Route::get('edit/{draftTransaction}', [DraftTransactionController::class,'edit'])->name('edit');
        Route::put('update/{draftTransaction}', [DraftTransactionController::class,'update'])->name('update');
        Route::any('copy/{draftTransaction}', [DraftTransactionController::class,'copy'])->name('copy');
        Route::delete('delete/{draftTransaction}', [DraftTransactionController::class,'destroy'])->name('delete');
        Route::get('store-to-transaction/{caseNumber}', [DraftTransactionController::class,'storeToTransactions'])->name('storeToTransactions');

        Route::get('archive', [DraftTransactionController::class,'archive'])->name('archive');

    })->middleware('access:گزارش دفتر معین');

    Route::get('sales-report', [SalesReportController::class, 'index'])->name('sales-report.index');
    Route::get('warehouse-report', [WarehouseReportController::class, 'index'])->name('warehouses-report.index');

    require __DIR__.'/../Purchases/web.php';
    require __DIR__.'/../Sales/web.php';


});
