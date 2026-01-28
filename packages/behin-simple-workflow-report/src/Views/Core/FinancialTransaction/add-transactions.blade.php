@extends('behin-layouts.app')

@section('title', 'افزودن سند حسابداری')

@section('content')
<h4>سند حسابداری </h4>
<form action="javascript:void(0)" method="POST" id="add-credit-form">
    @csrf
    <div class="row col-sm-12 p-0 m-0 dynamic-form">

        <table class="table" >
            <input type="text" name="case_number" id="" value="{{ $caseNumber }}">
            <thead>
                <tr>
                    <th>طرف حساب</th>
                    <th>بدهکار</th>
                    <th>بستانکار</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">
                        <button class="btn btn-sm btn-info">
                            افزودن رکورد جدید
                        </button>
                    </td>
                </tr>
            </tfoot>
        </table>

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

