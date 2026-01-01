@extends('behin-layouts.app')

@section('title', 'گزارش انبار')

@section('content')


    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">گزارش انبار</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>کالا</th>
                        <th>مانده</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->stock ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">داده‌ای یافت نشد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
