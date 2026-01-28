@extends('behin-layouts.app')

@section('title', 'ویرایش پرونده خرید')

@php
    $showBackBtn = true;
    $backBtnRouteName = 'simpleWorkflowReport.purchases.index';
    $backBtnName = 'بازگشت به لیست پرونده های خرید';
@endphp

@section('content')


    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">ویرایش پرونده خرید</h5>
        </div>
        <div class="card-body table-responsive">
            <input type="hidden" name="caseId" id="caseId" value="{{ $case->id }}">
            @include('SimpleWorkflowView::Core.Form.preview', [
                'form' => $form,
                'case' => $case,
                'mode' => $formMode,
            ])
        </div>
    </div>
@endsection
