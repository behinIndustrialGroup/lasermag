@extends('behin-layouts.app')

@section('title', 'جزئیات درخواست')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        @if (isset($_GET['msg']))
            <div class="col-md-10 col-lg-8">
                <div class="alert alert-success">
                    {{ $_GET['msg'] }}
                </div>
            </div>
        @endif
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="material-icons me-2">description</i>
                    <h5 class="mb-0">{{ $case->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <i class="material-icons text-muted me-2">confirmation_number</i>
                        <strong class="me-2">{{ trans('fields.Case Number') }}:</strong>
                        <span>{{ $case->number }}</span>
                    </div>

                    <div class="mb-3 d-flex align-items-center">
                        <i class="material-icons text-muted me-2">work_outline</i>
                        <strong class="me-2">فرایند:</strong>
                        <span>{{ $case->process?->name ?? '-' }}</span>
                    </div>

                    <div class="mb-3 d-flex align-items-center">
                        <i class="material-icons text-muted me-2">event</i>
                        <strong class="me-2">{{ trans('fields.Created At') }}:</strong>
                        <span dir="ltr">{{ toJalali($case->created_at)->format('Y-m-d H:i') }}</span>
                    </div>

                    <div class="mb-3 d-flex align-items-start">
                        <i class="material-icons text-muted me-2">flag</i>
                        <strong class="me-2">مرحله جاری:</strong><br>
                        <span>
                            @foreach ($case->whereIs() as $w)
                                <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">
                                    {{ $w->task->name }}
                                </span>
                                <small class="text-muted"> توسط: </small>
                                <span class="badge bg-info text-dark px-2 py-1 rounded-pill">
                                    {{ getUserInfo($w->actor)?->name }}
                                </span>
                                <br>
                            @endforeach
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
