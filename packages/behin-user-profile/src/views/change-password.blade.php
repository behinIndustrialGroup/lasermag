@extends('behin-layouts.app')

@section('title')
    تغییر رمز عبور
@endsection

@section('content')
<div class="container py-5 d-flex justify-content-center">

    <div class="card shadow-lg border-0 rounded-4" style="max-width: 500px; width: 100%;">
        <div class="card-body p-4">

            <h4 class="fw-bold text-primary mb-4 text-center">
                <i class="bi bi-shield-lock-fill me-2"></i> تغییر رمز عبور
            </h4>

            <form action="javascript:void(0)" method="POST" id="change-pass-form">
                @csrf
                {{ method_field('put') }}

                <div class="mb-3">
                    <label for="new_password" class="form-label fw-semibold">رمز عبور جدید:</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-key-fill text-secondary"></i>
                        </span>
                        <input type="password" class="form-control" name="new_password" id="new_password" placeholder="••••••••">
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button class="btn btn-gradient btn-lg fw-bold" onclick="change_pass()">
                        <i class="bi bi-check-circle-fill me-1"></i> ثبت رمز جدید
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection

@section('script')
<script>
    function change_pass(){
        var form = $('#change-pass-form')[0];
        var data = new FormData(form);
        send_ajax_formdata_request(
            "{{ route('user-profile.update-password') }}",
            data,
            function(response){
                show_message(response);
                $('#new_password').val('');
            }
        )
    }
</script>
@endsection

@push('styles')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    .btn-gradient {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        border: none;
        color: #fff;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37,117,252,0.3);
    }
</style>
@endpush
