@extends('behin-layouts.app')

@section('title', 'افزودن سند حسابداری')

@php
    $showBackBtn = true;
    $backBtnRouteName = 'simpleWorkflowReport.financial-transactions.index';
    $backBtnName = 'بازگشت به دفتر معین';
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
    <div class="card">
        <div class="card-header">
            سند حسابداری
        </div>
        <div class="card-body">

            <table class="table table-bordered">
                شماره سند: {{ $caseNumber }}
                <input type="hidden" name="case_number" id="" value="{{ $caseNumber }}">
                <thead>
                    <tr>
                        <th>طرف حساب</th>
                        <th>بدهکار</th>
                        <th>بستانکار</th>
                        <th>توضیحات</th>
                        <th>اقدامات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($draftRecord as $item)
                        @php
                            $color = $item->type == 'بدهکار' ? '#ff9494' : '#93fab6';
                        @endphp
                        <tr style="background: {{ $color }}">
                            <td>{{ $item->account->name }}</td>
                            <td>
                                @if ($item->type == 'بدهکار')
                                    {{ number_format($item->amount) }}
                                @endif
                            </td>
                            <td>
                                @if ($item->type == 'بستانکار')
                                    {{ number_format($item->amount) }}
                                @endif
                            </td>
                            <td>
                                {{ $item->description }}
                            </td>
                            <td>
                                <a href="{{ route('simpleWorkflowReport.draftTransaction.copy', $item->id) }}"
                                    class="btn btn-sm btn-info">
                                    <i class="fa fa-copy"></i>
                                    کپی
                                </a>
                                <button class="btn btn-sm btn-primary" onclick="edit(`{{ $item->id }}`)">
                                    <i class="fa fa-edit"></i>
                                    ویرایش
                                </button>
                                <form action="{{ route('simpleWorkflowReport.draftTransaction.delete', $item->id) }}"
                                    method="POST" style="display: inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" class="btn btn-sm btn-danger" value="حذف">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <button class="btn btn-sm btn-info" onclick="create(`{{ $caseNumber }}`)">
                                افزودن رکورد جدید
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="row">
                <a href="{{ route('simpleWorkflowReport.draftTransaction.storeToTransactions', $caseNumber) }}"
                    class="btn btn-sm btn-primary">ذخیره</a>

            </div>

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
        function edit(id) {
            var url = "{{ route('simpleWorkflowReport.draftTransaction.edit', 'draftTransaction') }}";
            url = url.replace('draftTransaction', id);
            open_admin_modal(url, 'ویرایش رکورد');
        }
    </script>
@endsection
