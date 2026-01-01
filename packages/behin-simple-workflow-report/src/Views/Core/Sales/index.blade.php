@extends('behin-layouts.app')

@section('title', 'گزارش فروش کالا')

@section('content')
    <div class="card mb-3">
        <div class="card-header">فیلتر جستجو</div>
        <div class="card-body">
            <form action="{{ route('simpleWorkflowReport.sales-report.index') }}" method="GET"
                class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">مشتری</label>
                    <input type="text" name="customer" class="form-control" value="{{ $filters['customer'] ?? '' }}"
                        placeholder="نام مشتری">
                </div>
                <div class="col-md-3">
                    <label class="form-label">شماره فاکتور</label>
                    <input type="text" name="invoice_no" class="form-control" value="{{ $filters['invoice_no'] ?? '' }}"
                        placeholder="شماره فاکتور">
                </div>
                <div class="col-md-2">
                    <label class="form-label">وضعیت</label>
                    <input type="text" name="status" class="form-control" value="{{ $filters['status'] ?? '' }}"
                        placeholder="وضعیت">
                </div>
                <div class="col-md-2">
                    <label class="form-label">واحد ارزی</label>
                    <input type="text" name="currency_unit" class="form-control"
                        value="{{ $filters['currency_unit'] ?? '' }}" placeholder="ریال، دلار و ...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">شماره پرونده</label>
                    <input type="text" name="case_number" class="form-control"
                        value="{{ $filters['case_number'] ?? '' }}" placeholder="شماره پرونده">
                </div>
                <div class="col-md-2">
                    <label class="form-label">از تاریخ</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $filters['from_date'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">تا تاریخ</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $filters['to_date'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">تعداد در صفحه</label>
                    <input type="number" name="per_page" min="1" class="form-control" value="{{ $perPage }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">جستجو</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('simpleWorkflowReport.sales-report.index') }}"
                        class="btn btn-outline-secondary w-100">پاک
                        کردن فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">گزارش فروش</h5>
            <div class="text-muted">جمع تعداد فروش: {{ number_format($totals->total_quantity ?? 0) }} | جمع مبلغ اقلام:
                {{ number_format($totals->items_total ?? 0) }} | جمع مبلغ فاکتورها:
                {{ number_format($totals->total_invoice_amount ?? 0) }}</div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>شماره فاکتور</th>
                        <th>مشتری</th>
                        <th>لیست اقلام (تعداد)</th> <!-- New column -->
                        <th>تاریخ خرید</th>
                        <th>شماره پرونده</th>
                        <th>تعداد کل</th>
                        <th>مجموع مبلغ اقلام</th>
                        <th>مبلغ فاکتور</th>
                        <th>واحد ارزی</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td>{{ $row->invoice_no }}</td>
                            <td>{{ $row->customer_name ?? '---' }}</td>
                            <td>{!! $row->items_list ?? '---' !!}</td> <!-- New cell -->
                            <td>{{ $row->purchase_date ?? $row->purchase_date_alt }}</td>
                            <td>{{ $row->case_number }}</td>
                            <td dir="ltr">{{ number_format($row->total_quantity ?? 0) }}</td>
                            <td dir="ltr">{{ number_format($row->items_total ?? 0) }}</td>
                            <td dir="ltr">{{ number_format($row->total_amount ?? 0) }}</td>
                            <td>{{ $row->currency_unit ?? '---' }}</td>
                            <td>{{ $row->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">داده‌ای یافت نشد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">
            {{ $rows->links() }}
        </div>
    </div>
@endsection
