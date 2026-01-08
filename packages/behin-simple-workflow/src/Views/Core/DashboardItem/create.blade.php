@extends('behin-layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('افزودن آیتم داشبورد') }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('simpleWorkflow.dashboard-items.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">{{ trans('fields.Name') }}</label>
                                <input type="text" name="name" id="name" class="form-control" required
                                    value="{{ old('name') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">{{ trans('fields.Description') }}</label>
                                <input type="text" name="description" id="description" class="form-control"
                                    value="{{ old('description') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="icon">{{ trans('fields.Icon') }}</label>
                                <input type="text" name="icon" id="icon" class="form-control" required
                                    value="{{ old('icon') }}" placeholder="ion ion-pie-graph">
                            </div>
                            <div class="form-group mb-3">
                                <label for="color">{{ trans('fields.Color') }}</label>
                                <input type="text" name="color" id="color" class="form-control" required
                                    value="{{ old('color') }}" placeholder="bg-warning">
                            </div>
                            <div class="form-group mb-3">
                                <label for="url">{{ trans('fields.Url') }}</label>
                                <input type="text" name="url" id="url" class="form-control" required
                                    value="{{ old('url') }}" placeholder="/workflow/report">
                            </div>
                            <div class="form-group mb-3">
                                <label for="access_key">{{ trans('fields.Access') }}</label>
                                <input type="text" name="access_key" id="access_key" class="form-control"
                                    value="{{ old('access_key') }}" placeholder="منو >>گزارشات کارتابل>>خلاصه">
                            </div>
                            <div class="form-group mb-3">
                                <label for="sort_order">{{ trans('fields.Order') }}</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control"
                                    value="{{ old('sort_order', 0) }}" min="0">
                            </div>
                            <button type="submit" class="btn btn-success">{{ trans('Save') }}</button>
                            <a href="{{ route('simpleWorkflow.dashboard-items.index') }}" class="btn btn-secondary">
                                {{ trans('Cancel') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
