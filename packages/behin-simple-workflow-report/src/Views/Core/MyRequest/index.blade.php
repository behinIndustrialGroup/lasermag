@extends('behin-layouts.app')

@section('title', 'درخواست‌های من')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="material-icons me-2">assignment</i>
                    <h5 class="mb-0">درخواست‌های من</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-bold text-secondary">شماره پرونده</th>
                                    <th class="fw-bold text-secondary">فرایند</th>
                                    <th class="fw-bold text-secondary">تاریخ ایجاد</th>
                                    <th class="fw-bold text-secondary text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cases as $case)
                                    <tr class="hover-row">
                                        <td>{{ $case->number }}</td>
                                        <td>{{ $case->process?->name }}</td>
                                        <td dir="ltr">{{ toJalali($case->created_at)->format('Y-m-d H:i') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('simpleWorkflowReport.my-request.show', ['my_request' => $case->id]) }}" 
                                               class="btn btn-sm btn-primary d-flex align-items-center justify-content-center gap-1 rounded-pill shadow-sm">
                                                <i class="material-icons" style="font-size:16px;">visibility</i> 
                                                مشاهده
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- استایل اضافی برای متریال حس --}}
<style>
    .hover-row:hover {
        background-color: #f5f5f5;
        transition: background-color 0.2s ease-in-out;
    }
    .table th, .table td {
        padding: 0.9rem 1rem;
        vertical-align: middle;
    }
</style>
@endsection
