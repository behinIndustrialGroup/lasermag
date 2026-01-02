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
                    @forelse ($rows as $row)
                        <tr>
                            <td>{{ $row->number }}</td>
                            <td>{{ $row->creator->name ?? '' }}</td>
                            <td>{{ $row->purchase->supplier->name ?? '' }}</td>
                            <td>{{ number_format($row->purchase->total_amount ?? 0) }}</td>
                            <td>
                                @foreach($row->purchaseItems as $item)
                                    {{ $item->product->name ?? '' }} ({{ $item->quantity }}x{{ number_format($item->price) }})<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach($row->financialTransaction as $transaction)
                                    {{ $transaction->account->name }} 
                                    {{ number_format($transaction->amount) }}
                                    {{ $transaction->type }}
                                    <br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($row->inventoryTransaction as $inv)
                                    {{ $inv->quantity }} * {{ $inv->inventory_transaction_type }} {{ $inv->type }}<br>
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
