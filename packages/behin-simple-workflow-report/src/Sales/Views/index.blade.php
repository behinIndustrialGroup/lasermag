@extends('behin-layouts.app')

@section('title', 'گزارش فرایند های فروش')

@php
    $showBackBtn = true;
    $backBtnRouteName = 'simpleWorkflowReport.summary-report.index';
    $backBtnName = 'بازگشت به لیست گزارش ها';
@endphp

@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">گزارش فرایندهای فروش</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>اقدامات</th>
                        <th>شماره پرونده</th>
                        <th>وضعیت پرونده</th>
                        <th>ایجاد کننده</th>
                        <th>خریدار</th>
                        <th>مبلغ کل فروش</th>
                        <th>آیتم های فروش</th>
                        <th>تراکنش های مالی</th>
                        <th>تراکنش انبار</th>
                        <th>اقدامات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cases as $case)
                        <tr>
                            <td>
                                <a href="{{ route('simpleWorkflowReport.sales.edit', $case->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                    ویرایش
                                </a>
                            </td>
                            <td>{{ $case->number }}</td>
                            <td>{{ trans("fields.$case->status") }}</td>
                            <td>{{ $case->creator?->name ?? '' }}</td>
                            <td>{{ $case->sale->buyer?->name ?? '' }}</td>
                            <td>{{ number_format($case->sale->total_amount ?? 0) }}</td>
                            <td>
                                @foreach ($case->saleItems as $item)
                                    {{ $item->quantity }} * {{ number_format($item->unit_price) }}
                                    {{ $item->currency_unit }} * {{ $item->product?->name }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($case->financialTransaction as $transaction)
                                    {{ $transaction->account?->name ?? '' }}
                                    {{ number_format($transaction->amount) }}
                                    {{ $transaction->type }}
                                    <br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($case->inventoryTransaction as $inv)
                                    {{ $inv->quantity }} * {{ $inv->inventory_transaction_type }}
                                    {{ $inv->product?->name }}<br>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('simpleWorkflowReport.sales.edit', $case->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                    ویرایش
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">داده‌ای یافت نشد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
