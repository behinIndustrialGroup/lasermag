@extends('behin-layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('ویرایش آیتم داشبورد') }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('simpleWorkflow.dashboard-items.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="name">{{ trans('fields.Name') }}</label>
                                <input type="text" name="name" id="name" class="form-control" required
                                    value="{{ old('name', $item->name) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">{{ trans('fields.Description') }}</label>
                                <input type="text" name="description" id="description" class="form-control"
                                    value="{{ old('description', $item->description) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="icon">{{ trans('fields.Icon') }}</label>
                                <input type="text" name="icon" id="icon" class="form-control" required
                                    value="{{ old('icon', $item->icon) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="color">{{ trans('fields.Color') }}</label>
                                <input type="text" name="color" id="color" class="form-control" required
                                    value="{{ old('color', $item->color) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="url">{{ trans('fields.Url') }}</label>
                                <input type="text" name="url" id="url" class="form-control" required
                                    value="{{ old('url', $item->url) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="access_key">{{ trans('fields.Access') }}</label>
                                <input type="text" name="access_key" id="access_key" class="form-control"
                                    value="{{ old('access_key', $item->access_key) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="sort_order">{{ trans('fields.Order') }}</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control"
                                    value="{{ old('sort_order', $item->sort_order) }}" min="0">
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
