@extends('behin-layouts.app')

@section('title', 'گزارش فرایند های خرید')

@section('content')


    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">گزارش فرایندهای خرید</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>شماره پرونده</th>
                        <th>ایجاد کننده</th>
                        <th>تامین کننده</th>
                        <th>مبلغ کل خرید</th>
                        <th>آیتم های خرید</th>
                        <th>تراکنش های مالی</th>
                        <th>تراکنش انبار</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cases as $case)
                        <tr>
                            <td>{{ $case->number }}</td>
                            <td>{{ $case->creator->name ?? '' }}</td>
                            <td>{{ $case->purchase->supplier->name ?? '' }}</td>
                            <td>{{ number_format($case->purchase->total_amount ?? 0) }}</td>
                            <td>
                                @foreach($case->purchaseItems as $item)
                                    {{ $item->quantity }} x {{ number_format($item->unit_price) }} {{ $item->currency_unit }} x {{ $item->product->name }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach($case->financialTransaction as $transaction)
                                    {{ $transaction->account->name }} 
                                    {{ number_format($transaction->amount) }}
                                    {{ $transaction->type }}
                                    <br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($case->inventoryTransaction as $inv)
                                    {{ $inv->quantity }} * {{ $inv->inventory_transaction_type }} {{ $inv->product->name }}<br>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">داده‌ای یافت نشد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
