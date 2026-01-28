@extends('behin-layouts.app')

@section('title', 'آرشیو سندهای حسابداری')

@php
    $showBackBtn = true;
    $backBtnRouteName = 'simpleWorkflowReport.financial-transactions.index';
    $backBtnName = 'بازگشت به دفتر معین';
@endphp

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif  
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif  
    <div class="card">
        <div class="card-header">
            آرشیو سندهای حسابداری
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>شماره سند</th>
                        <th>تاریخ ایجاد</th>
                        <th>جزئیات</th>
                        <th>ویرایش</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($draftTransactions as $item)
                        <tr>
                            <td>{{ $item->case_number }}</td>
                            <td dir="ltr">{{ toJalali($item->createdAt) }}</td>
                            <td>
                                @foreach ($item->details as $detail)
                                    <div class="badge" style="background: #fff; display: block; text-align: right">
                                        {{ $detail->account?->name }} ( {{ number_format($detail->amount) }} )
                                        <p class="text-muted">
                                            {{ $detail->description }}
                                        </p>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('simpleWorkflowReport.draftTransaction.index', $item->case_number) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                    ویرایش
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <script>
        initial_view()
    </script>

    <script>
        public
        function create(caseNumber) {
            var url = "{{ route('simpleWorkflowReport.draftTransaction.create', 'caseNumber') }}";
            url = url.replace('caseNumber', caseNumber);
            open_admin_modal(url, 'افزودن رکورد');
        }
    </script>
@endsection
