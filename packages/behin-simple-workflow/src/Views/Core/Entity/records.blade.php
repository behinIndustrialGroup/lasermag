@extends('behin-layouts.app')

@section('title')
    {{ trans('fields.Edit Records') }}
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-2">

                {{-- عملیات اصلی --}}
                <a href="{{ route('simpleWorkflow.entities.createRecord', $entity->id) }}" class="btn btn-primary ml-2">
                    <i class="bi bi-plus-lg me-1"></i>
                    {{ trans('fields.Add Record') }}
                </a>

                {{-- ابزارها --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                        <i class="bi bi-gear me-1 ml-2"></i>
                        ابزارها
                    </button>

                    <div class="dropdown-menu p-3" style="min-width: 280px">

                        {{-- Export --}}
                        <form action="{{ route('simpleWorkflow.entities.records.export', $entity->id) }}" method="POST"
                            class="mb-2">
                            @csrf
                            <button class="btn btn-light w-100 text-start">
                                <i class="bi bi-download me-1"></i>
                                {{ trans('fields.Export') }}
                            </button>
                        </form>

                        {{-- Import --}}
                        <form action="{{ route('simpleWorkflow.entities.records.import', $entity->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" class="form-control form-control-sm mb-2" required>

                            <button class="btn btn-light w-100 text-start">
                                <i class="bi bi-upload me-1"></i>
                                {{ trans('fields.Import') }}
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="container card p-3 table-responsive">
        <table class="table table-strpped" id="recordsTable">
            <thead>
                <tr>
                    @foreach ($columns as $column)
                        <th class="text-right">{{ trans('fields.' . $column) }}</th>
                    @endforeach
                    <th>{{ trans('fields.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                    <tr>
                        @foreach ($columns as $column)
                            <td class="text-right">{{ $record->$column }}</td>
                        @endforeach
                        <td>
                            <a href="{{ route('simpleWorkflow.entities.editRecord', [$entity->id, $record->id]) }}"
                                class="btn btn-sm btn-primary">
                                {{ trans('fields.Edit') }}
                            </a>
                            <form action="{{ route('simpleWorkflow.entities.deleteRecord', [$entity->id, $record->id]) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('{{ trans('fields.Are you sure you want to delete this record?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ trans('fields.Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script>
        $('#recordsTable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Persian.json"
            }
        });
    </script>
@endsection
