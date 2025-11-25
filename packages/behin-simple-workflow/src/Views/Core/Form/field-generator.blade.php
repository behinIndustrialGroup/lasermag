@php
    $fieldLabel = trans('SimpleWorkflowLang::fields.' . $fieldName);
    $fieldDetails = getFieldDetailsByName($fieldName);
    $fieldAttributes = $fieldDetails ? json_decode($fieldDetails->attributes) : null;

    $fieldStyle = isset($fieldAttributes->style) ? $fieldAttributes->style : null;
    $fieldScript = isset($fieldAttributes->script) ? $fieldAttributes->script : null;
    $fieldPlaceholder = isset($fieldAttributes->placeholder) ? $fieldAttributes->placeholder : null;
    $fieldOptions = isset($fieldAttributes->options) ? $fieldAttributes->options : null;
    $fieldQuery = isset($fieldAttributes->query) ? $fieldAttributes->query : null;
    $fieldDatalist = isset($fieldAttributes->datalist_from_database)
        ? $fieldAttributes->datalist_from_database
        : null;
@endphp
@if ($fieldDetails->type == 'title')
    {!! Form::title($fieldId, [
        'value' => $fieldValue,
        'class' => '',
        'id' => $fieldId,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'hidden')
    {!! Form::hidden($fieldId, [
        'value' => $fieldValue,
        'class' => '',
        'id' => $fieldId,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'help')
    {!! Form::help($fieldId, [
        'options' => $fieldOptions,
        'class' => '',
        'id' => $fieldDetails->id ?? $fieldId,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'location')
    @php
        $defaultLat = null;
        $defaultLng = null;

        if (isset($variables)) {
            $defaultLat = optional($variables->where('key', $field->fieldName . '_lat')->first())->value;
            $defaultLng = optional($variables->where('key', $field->fieldName . '_lng')->first())->value;
        }
    @endphp
    {!! Form::location($fieldId, [
        'value' => $fieldValue,
        'class' => '',
        'id' => $fieldId,
        'required' => $required,
        'readonly' => $readOnly,
        'defaultZoom' => 13,
        'defaultLat' => $defaultLat,
        'defaultLng' => $defaultLng,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'string')
    {!! Form::text($fieldId, [
        'value' => $fieldValue,
        'class' => 'form-control',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
        'datalist_from_database' => $fieldDatalist,
    ]) !!}
@endif
@if ($fieldDetails->type == 'checkbox')
    {!! Form::checkbox($fieldId, [
        'value' => $fieldValue,
        'class' => '',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'text')
    {!! Form::textarea($fieldId, [
        'value' => $fieldValue,
        'class' => 'form-control',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'date')
    {!! Form::date($fieldId, [
        'value' => $fieldValue,
        'class' => 'form-control',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'time')
    {!! Form::time($fieldId, [
        'value' => $fieldValue,
        'class' => 'form-control timepicker',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'datetime')
    {!! Form::datetime($fieldId, [
        'value' => $fieldValue,
        'class' => 'form-control',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'select')
    {!! Form::select($fieldId, is_string($fieldOptions) ? $fieldOptions : null, [
        'value' => $fieldValue,
        'query' => is_string($fieldQuery) ? $fieldQuery : null,
        'class' => 'form-control',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'select-simple')
    {!! Form::selectSimple($fieldId, is_string($fieldOptions) ? $fieldOptions : null, [
        'value' => $fieldValue,
        'query' => is_string($fieldQuery) ? $fieldQuery : null,
        'class' => 'form-control',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'searchable-input')
    {!! Form::searchableInput($fieldId, [
        'value' => $fieldValue,
        'endpoint' => isset($fieldAttributes->endpoint) && is_string($fieldAttributes->endpoint)
            ? $fieldAttributes->endpoint
            : null,
        'minChars' => isset($fieldAttributes->minChars) ? $fieldAttributes->minChars : null,
        'limit' => isset($fieldAttributes->limit) ? $fieldAttributes->limit : null,
        'initial_label' => $fieldAttributes->initial_label ?? ($fieldAttributes->initialLabel ?? null),
        'class' => 'form-control',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'select-multiple')
    {!! Form::selectMultiple($fieldId, is_string($fieldOptions) ? $fieldOptions : null, [
        'value' => json_decode($fieldValue),
        'query' => is_string($fieldQuery) ? $fieldQuery : null,
        'class' => 'form-control',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'file')
    {{-- @php
        $fieldValues = isset($variables) ? $variables->where('key', $field->fieldName)->pluck('value') : [];
    @endphp --}}
    {!! Form::file($fieldId, [
        'value' => $fieldValue ?? [],
        'class' => 'form-control',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'signature')
    {!! Form::signature($fieldId, [
        'value' => $fieldValue,
        'class' => 'form-control',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
        'datalist_from_database' => $fieldDatalist,
    ]) !!}
@endif
@if ($fieldDetails->type == 'entity')
    {!! Form::entity($fieldId, [
        'columns' => isset($fieldAttributes->columns) && is_string($fieldAttributes->columns) ? $fieldAttributes->columns : null,
        'query' => is_string($fieldQuery) ? $fieldQuery : null,
        'class' => 'form-control',
        'id' => $fieldAttributes->id ?? null,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'button')
    {!! Form::button($fieldName, [
        'class' => $fieldClass,
        'id' => $fieldAttributes->id ?? $fieldName,
        'style' => $fieldStyle,
        'script' => $fieldScript,
    ]) !!}
@endif
@if ($fieldDetails->type == 'view-model')
    {!! Form::viewModel($fieldId, [
        'class' => $fieldClass,
        'id' => $fieldId,
        'view_model_id' => $fieldAttributes->view_model_id ?? null,
        'style' => $fieldStyle,
    ]) !!}
@endif

@if ($fieldDetails->type == 'formatted-digit')
    {!! Form::formattedDigit($fieldId, [
        'value' => $fieldValue,
        'class' => 'form-control',
        'id' => $fieldId,
        'placeholder' => $fieldPlaceholder,
        'required' => $required,
        'readonly' => $readOnly,
        'style' => $fieldStyle,
        'script' => $fieldScript,
        'datalist_from_database' => $fieldDatalist,
    ]) !!}
@endif
