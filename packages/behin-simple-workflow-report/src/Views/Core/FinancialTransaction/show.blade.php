@php
    
@endphp

<div class="card table-responsive">
    <div class="card-header bg-secondary text-center">
        <h3 class="card-title">جزئیات حساب: {{ $creditors[0]->counterparty()->name ?? '' }}</h3>
        @if ($showHeaderBtn)
            
            
        @endif
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="more-details">
            <thead>
                <tr>
                    <th>نوع</th>
                    <th>طرف حساب</th>
                    <th>مبلغ</th>
                    <th>بابت پرونده</th>
                    <th>نوع پرداختی</th>
                    <th>{{ trans('fields.reference_id') }}</th>
                    <th>{{ trans('fields.date') }}</th>
                    <th>{{ trans('fields.destination_account_name') }}</th>
                    <th>توضیحات</th>
                    <th>اقدامات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($creditors as $creditor)
                    <tr id="financial-transaction-row-{{ $creditor->id }}"
                        @if ($creditor->type == 'بدهکار') style="background: #f56c6c" @endif
                        @if ($creditor->type == 'بستانکار') class="bg-success" @endif>
                        <td>{{ $creditor->type }}</td>
                        <td>{{ $creditor->counterparty()->name ?? '' }}</td>
                        <td dir="ltr">
                            {{ str_contains($creditor->amount, ',') ? $creditor->amount : number_format((int) $creditor->amount) }}
                        </td>
                        <td>
                            @if (!empty($creditor->case_number))
                                <a href="{{ route('simpleWorkflowReport.external-internal.show', ['external_internal' => $creditor->case_number]) }}"
                                    class="text-decoration-none me-1">
                                    <i class="fa fa-external-link text-primary"></i>
                                </a>
                            @endif
                            {{ $creditor->case_number }}
                        </td>
                        <td>{{ $creditor->financial_method }}</td>
                        <td>{{ $creditor->reference_id }}</td>
                        <td>{{ $creditor->date }}</td>
                        <td>{{ $creditor->destinationAccountName()?->name ?? '' }}</td>
                        <td>{{ $creditor->description }}</td>
                        <td>
                            @if (access('اصلاح یا حذف تراکنش مالی'))
                                <button
                                    class="btn btn-sm btn-{{ $creditor->type == 'بستانکار' ? 'primary' : 'warning' }} mb-1"
                                    onclick="editFinancialTransaction(`{{ $creditor->id }}`)">ویرایش</button>
                                <button class="btn btn-sm btn-danger mb-1"
                                    onclick="deleteFinancialTransaction(`{{ $creditor->id }}`)">حذف</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-right">مانده حساب:</th>
                    <th id="sum-amount"></th>
                    <th colspan="4"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    (function() {
        const editUrlTemplate =
            "{{ route('simpleWorkflowReport.financial-transactions.edit', ['financial_transaction' => '__id__']) }}";
        const destroyUrlTemplate =
            "{{ route('simpleWorkflowReport.financial-transactions.destroy', ['financial_transaction' => '__id__']) }}";
        const csrfToken = '{{ csrf_token() }}';

        // اگر DataTable از قبل وجود دارد، ابتدا آن را نابود کن
        if ($.fn.DataTable.isDataTable('#more-details')) {
            $('#more-details').DataTable().destroy();
        }

        const moreDetailsTable = $('#more-details').DataTable({
            "pageLength": 25,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Persian.json"
            },
            "order": [
                [6, "desc"]
            ],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api();

                function calculateTotal(selector) {
                    return api.rows(selector).data().reduce(function(a, b) {
                        var type = b[0]; // ستون اول: بدهکار یا بستانکار
                        var amount = parseInt(b[2].toString().replace(/,/g, '')) || 0;
                        if (type === 'بدهکار') amount = -amount;
                        return a + amount;
                    }, 0);
                }

                var pageTotal = calculateTotal({
                    page: 'current'
                });
                var total = calculateTotal({});

                $('#sum-amount').html(
                    total.toLocaleString() + ' (این صفحه: ' + pageTotal.toLocaleString() + ')'
                );
            }
        });

        window.editFinancialTransaction = function(id) {
            const url = editUrlTemplate.replace('__id__', id);
            open_admin_modal(url, 'ویرایش تراکنش مالی');
        }

        window.deleteFinancialTransaction = function(id) {
            if (!confirm('آیا از حذف این تراکنش اطمینان دارید؟')) return;

            const url = destroyUrlTemplate.replace('__id__', id);

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: csrfToken
                },
                success: function(response) {
                    const row = $('#financial-transaction-row-' + id);
                    if (row.length) {
                        moreDetailsTable.row(row).remove().draw();
                    }

                    const message = response.message ?? 'تراکنش با موفقیت حذف شد.';
                    if (typeof show_message === 'function') show_message(message);
                    else alert(message);
                },
                error: function(xhr) {
                    if (typeof show_error === 'function') show_error(xhr);
                    else alert('خطا در حذف تراکنش');
                }
            });
        }
    })();
</script>
