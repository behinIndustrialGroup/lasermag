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
use Behin\SimpleWorkflowReport\Controllers\Core\MyRequestController;
use Behin\SimpleWorkflowReport\Controllers\Core\StageReportController;
use Behin\SimpleWorkflowReport\Controllers\Core\RoleReportFormController;
use Behin\SimpleWorkflowReport\Controllers\Core\SummaryReportController;
use Behin\SimpleWorkflowReport\Controllers\Core\PersonelActivityController;
use Behin\SimpleWorkflowReport\Controllers\Core\PhonebookController;
use Behin\SimpleWorkflowReport\Controllers\Core\RecordingController;
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


});
