@extends('behin-layouts.app')

@section('title', 'افزودن سند حسابداری')

@section('content')
<h4>سند حسابداری </h4>
<form action="{{ route('simpleWorkflowReport.financial-transactions.addDraftTransaction') }}" method="POST" id="add-credit-form">
    @csrf
    <div class="row col-sm-12 p-0 m-0 dynamic-form">

        <div class="col-sm-4">
            <div class="form-group"><label>طرف حساب</label>
                @if (isset($account_id))
                    <input type="text" name="account_name" value="{{ $account_id->name }}" class="form-control"
                        id="account_name" readonly>
                    <input type="hidden" name="account_id" value="{{ $account_id->id }}" class="form-control"
                        id="account_id" readonly>
                @else
                    <select name="account_id" class="form-control select2" id="counterparty">
                        <option value="">انتخاب کنید</option>
                        @foreach ($counterParties as $counterParty)
                            <option value="{{ $counterParty->id }}">{{ $counterParty->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>

        <div class="col-sm-8"></div>

        <div class="col-sm-4">
            <div class="form-group"><label>نوع</label>
                <select name="type" class="form-control" value="" query="" id="type" placeholder=""
                    style="">
                    <option value="">Select</option>
                    <option selected value="بستانکار">بستانکار</option>
                    <option value="بدهکار">بدهکار</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group"><label>مبلغ</label><input type="text" name="amount" list="amount_list"
                    class="form-control formatted-digit" inputmode="numeric" id="amount" placeholder=""
                    style=""></div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label>{{ trans('fields.date') }}</label>
                <input type="text" name="date" value="" class="form-control pwt-datepicker-input-element"
                    id="date" placeholder="" style="" script="">
                <input type="hidden" name="date_alt" id="date_alt">
                <script>
                    $('#date').persianDatepicker({
                        viewMode: 'day',
                        initialValue: false,
                        format: 'YYYY-MM-DD',
                        initialValueType: 'persian',
                        altField: '#date_alt',
                        calendar: {
                            persian: {
                                leapYearMode: 'astronomical',
                                locale: 'fa'
                            }
                        }
                    });
                </script>
            </div>

        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label>بابت پرونده</label>
                <input type="text" name="case_number" list="case_number_list" class="form-control"
                    inputmode="numeric" id="case_number" placeholder="" style="">
            </div>
        </div>
        


        
        <div class="col-sm-12">
            <div class="form-group"><label>توضیحات</label>
                <textarea name="description" class="form-control" rows="5"></textarea>
            </div>
        </div>

    </div>
</form>
<div class="row">
    <button class="btn btn-sm btn-primary" onclick="submitForm()">
        ذخیره
    </button>
</div>


<script>
    initial_view()
</script>

<script>
    
</script>
@endsection

