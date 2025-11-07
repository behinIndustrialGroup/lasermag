@extends('behin-layouts.app')

@section('title')
    پروفایل کاربری
@endsection

@section('content')
<div class="container py-5">

    <!-- کارت پروفایل -->
    <div class="card shadow-lg border-0 rounded-4 mb-4">
        <div class="card-body p-4">

            <h4 class="fw-bold text-primary mb-4">
                <i class="bi bi-person-circle me-2"></i> اطلاعات کاربری
            </h4>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="fw-semibold text-muted">نام کاربری:</label>
                    <div class="fs-6">{{ $user->display_name }}</div>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold text-muted">شماره موبایل:</label>
                    <div class="fs-6">{{ $user->email }}</div>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold text-muted">کد ملی:</label>
                    @if ($userProfile?->national_id)
                        <div class="fs-6">{{ $userProfile->national_id ?? ''}}</div>
                    @else
                        <form action="javascript:void(0)" id="store-national-id-form" class="d-flex gap-2">
                            @csrf
                            <input type="text" class="form-control form-control-sm" 
                                   name="national_id" id="national_id" placeholder="کد ملی خود را وارد کنید">
                            <button class="btn btn-sm btn-primary" onclick="store_national_id()">ثبت</button>
                        </form>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end">
                <a href="{{ route('user-profile.change-password') }}" class="btn btn-danger px-4">
                    <i class="bi bi-shield-lock-fill me-1"></i> تغییر رمز عبور
                </a>
            </div>
        </div>
    </div>


</div>
@endsection

@section('script')
<script>
    function store_national_id() {
        var form = $('#store-national-id-form')[0];
        var data = new FormData(form);
        send_ajax_formdata_request(
            "{{ route('user-profile.storeNationalId') }}",
            data,
            function(response) {
                show_message("{{ trans('ok') }}")
                location.reload()
            }
        )
    }

</script>
@endsection

@push('styles')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush
