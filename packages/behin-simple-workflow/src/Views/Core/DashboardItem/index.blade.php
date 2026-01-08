@extends('behin-layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ __('مدیریت آیتم‌های داشبورد') }}</h3>
                <a href="{{ route('simpleWorkflow.dashboard-items.create') }}" class="btn btn-success">
                    {{ trans('Create') }}
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card table-responsive">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('fields.Name') }}</th>
                                    <th>{{ trans('fields.Description') }}</th>
                                    <th>{{ trans('fields.Icon') }}</th>
                                    <th>{{ trans('fields.Color') }}</th>
                                    <th>{{ trans('fields.Url') }}</th>
                                    <th>{{ trans('fields.Access') }}</th>
                                    <th>{{ trans('fields.Order') }}</th>
                                    <th>{{ trans('fields.Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->icon }}</td>
                                        <td>{{ $item->color }}</td>
                                        <td>{{ $item->url }}</td>
                                        <td>{{ $item->access_key }}</td>
                                        <td>{{ $item->sort_order }}</td>
                                        <td>
                                            <a href="{{ route('simpleWorkflow.dashboard-items.edit', $item->id) }}"
                                                class="btn btn-sm btn-primary">{{ trans('fields.Edit') }}</a>
                                            <form action="{{ route('simpleWorkflow.dashboard-items.destroy', $item->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('{{ trans('messages.confirmDelete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">{{ trans('fields.Delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">{{ __('آیتمی یافت نشد') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
