<h4>فرم افزودن بستانکاری</h4>
<form action="javascript:void(0)" method="POST" id="add-credit-form">
    @csrf
    <div class="row col-sm-12 p-0 m-0 dynamic-form" id="dfd41076-26ca-47e4-ab34-17bec3bd89db">

        <div class="col-sm-4">
            <div class="form-group"><label>طرف حساب</label>
                @if (isset($account_id))
                    <input type="text" name="account_name" value="{{ $account_id->name }}" class="form-control"
                        id="account_name" readonly>
                    <input type="hidden" name="account_id" value="{{ $account_id->id }}" class="form-control"
                        id="account_id" readonly>
                @else
                    @php
                        $fieldName = 'account_id';
                        $fieldDetails = getFieldDetailsByName($fieldName);
                        $fieldValue = null;
                        $fieldValueAlt = null;
                    @endphp
                    <div class="">
                        @include('SimpleWorkflowView::Core.Form.field-generator', [
                            'fieldName' => $fieldName,
                            'fieldId' => $fieldName,
                            'fieldClass' => 'col-sm-12',
                            'readOnly' => true,
                            'required' => false,
                            'fieldValue' => $fieldValue,
                            'fieldValueAlt' => $fieldValueAlt ?? '',
                        ])
                    </div>
                    {{-- <select name="account_id" class="form-control select2" id="counterparty">
                        <option value="">انتخاب کنید</option>
                        @foreach ($counterParties as $counterParty)
                            <option value="{{ $counterParty->id }}">{{ $counterParty->name }}</option>
                        @endforeach
                    </select> --}}
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
    <button class="btn btn-primary" onclick="submitForm()">
        ذخیره
    </button>
</div>


<script>
    initial_view()
</script>

<script>
    let counterPartyDataMap = {};

    function submitForm() {
        var fd = new FormData($('#add-credit-form')[0]);
        var url = "{{ route('simpleWorkflowReport.financial-transactions.addCredit') }}";
        send_ajax_formdata_request(
            url,
            fd,
            function(res) {
                console.log(res);
                show_message("ذخیره شد");
                window.location.reload();
            },
            function(res) {
                console.log(res)
                show_error(res);
            }
        )
    }

    function getCounterParty(q, input_id) {
        var scriptId = "0fa291ce-6b0a-4e0b-b9aa-e6b65337f97c";
        var fd = new FormData();
        fd.append('q', q);
        runScript(scriptId, fd, function(response) {
            console.log(response);
            var list = $(`#${input_id}_list`);
            counterPartyDataMap = {}; // ریست آبجکت

            if (list.length) {
                list.html('');
                response.forEach(function(item) {
                    counterPartyDataMap[item.name] = item; // ذخیره اطلاعات هر مشتری بر اساس fullname
                    list.append(`<option value="${item.name}"></option>`);
                });
            } else {
                $('#account_number').after(`<datalist id="${input_id}_list"></datalist>`);
                list = $(`#${input_id}_list`);
                response.forEach(function(item) {
                    counterPartyDataMap[item.name] = item; // ذخیره اطلاعات هر مشتری بر اساس fullname
                    list.append(`<option value="${item.name}"></option>`);
                });
            }
        });
    }

    $('#destination_account_name').on('input', function() {
        var q = $(this).val();
        var selected = counterPartyDataMap[q];
        if (selected) {
            $('#destination_account_number').val(selected.account_number || '');
        }
    });

    $('#destination_account_name').keyup(function() {
        if ($(this).val().length >= 3) {
            getCounterParty($(this).val(), $(this).attr('id'));
        }
    });
</script>
