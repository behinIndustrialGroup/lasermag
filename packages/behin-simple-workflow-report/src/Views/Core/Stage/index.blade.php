@extends('behin-layouts.app')

@section('title', 'گزارش مرحله‌ای درخواست‌ها')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">فیلتر مراحل</h5>
                        <span class="text-muted small">مراحل دارای درخواست باز</span>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('simpleWorkflowReport.stage-report.index') }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-9">
                                    <label class="form-label">انتخاب مرحله (امکان انتخاب چند مرحله)</label>
                                    <select name="tasks[]" class="form-select" multiple size="8">
                                        @foreach ($tasks as $processId => $processTasks)
                                            @php
                                                $processName = $processTasks->first()?->process?->name ?? 'سایر فرایندها';
                                            @endphp
                                            <optgroup label="{{ $processName }}">
                                                @foreach ($processTasks as $task)
                                                    <option value="{{ $task->id }}" @selected(in_array($task->id, $selectedTasks))>
                                                        {{ $task->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <small class="text-muted d-block mt-2">برای انتخاب چند مرحله کلیدهای Ctrl یا Command را نگه دارید.</small>
                                </div>
                                <div class="col-md-3 d-flex gap-2 justify-content-md-end">
                                    <button type="submit" class="btn btn-primary flex-grow-1">اعمال فیلتر</button>
                                    <a href="{{ route('simpleWorkflowReport.stage-report.index') }}" class="btn btn-outline-secondary">حذف فیلتر</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0">لیست درخواست‌ها</h5>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-light text-primary">{{ $rows->count() }} مورد</span>
                            <form method="GET" action="{{ route('simpleWorkflowReport.stage-report.export') }}">
                                @foreach ($selectedTasks as $taskId)
                                    <input type="hidden" name="tasks[]" value="{{ $taskId }}">
                                @endforeach
                                <button type="submit" class="btn btn-outline-light btn-sm">
                                    خروجی اکسل
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>شماره درخواست</th>
                                    <th>نام مشتری</th>
                                    <th>موبایل مشتری</th>
                                    <th>کدملی</th>
                                    <th>استان</th>
                                    <th>پیمانکار</th>
                                    <th>شناسه قبض</th>
                                    <th>کدپستی</th>
                                    <th>آدرس</th>
                                    <th>مرحله جاری</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($rows as $row)
                                    <tr>
                                        <td>{{ $row['case_number'] }}</td>
                                        <td>{{ $row['customer_name'] ?? '---' }}</td>
                                        <td dir="ltr">{{ $row['customer_mobile'] ?? '---' }}</td>
                                        <td dir="ltr">{{ $row['customer_national_id'] ?? '---' }}</td>
                                        <td>{{ $row['province'] ?? '---' }}</td>
                                        <td>{{ $row['contractor'] ?? '---' }}</td>
                                        <td dir="ltr">{{ $row['bill_identifier'] ?? '---' }}</td>
                                        <td dir="ltr">{{ $row['postal_code'] ?? '---' }}</td>
                                        <td>{{ $row['address'] ?? '---' }}</td>
                                        <td>{{ $row['current_stage'] ?? '---' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">رکوردی یافت نشد.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
