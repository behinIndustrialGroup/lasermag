<?php

namespace Behin\SimpleWorkflowReport\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->all();
        $perPage = (int) ($filters['per_page'] ?? 25);
        $perPage = $perPage > 0 ? $perPage : 25;

        $query = $this->baseQuery();
        $this->applyFilters($query, $filters);
        $query->groupBy([
            'p.id',
            'p.invoice_no',
            'p.purchase_date',
            'p.purchase_date_alt',
            'p.total_amount',
            'p.status',
            'p.currency_unit',
            'p.case_number',
            'c.name',
        ]);

        $rows = $query
            ->orderByDesc('p.purchase_date')
            ->orderByDesc('p.created_at')
            ->paginate($perPage)
            ->appends($filters);

        $summary = $this->summaryQuery();
        $this->applyFilters($summary, $filters);
        $totals = $summary->first();

        return view('SimpleWorkflowReportView::Core.Sales.index', [
            'rows' => $rows,
            'filters' => $filters,
            'perPage' => $perPage,
            'totals' => $totals,
        ]);
    }

    protected function baseQuery()
    {
        return DB::table('wf_entity_purchases as p')
            ->leftJoin('wf_entity_customers as c', 'p.supplier_id', '=', 'c.id')
            ->leftJoin('wf_entity_purchase_items as pi', 'pi.case_number', '=', 'p.case_number')
            ->select([
                'p.id',
                'p.invoice_no',
                'p.purchase_date',
                'p.purchase_date_alt',
                'p.total_amount',
                'p.status',
                'p.currency_unit',
                'p.case_number',
                'c.name as customer_name',
                DB::raw('COALESCE(SUM(pi.quantity), 0) as total_quantity'),
                DB::raw('COALESCE(SUM(pi.total), 0) as items_total'),
                DB::raw('GROUP_CONCAT(CONCAT(pi.name, " (", pi.quantity, ")") SEPARATOR "، ") as items_list')
            ])
            ->whereNull('p.deleted_at')
            ->where(function ($query) {
                $query->whereNull('pi.deleted_at')
                    ->orWhereNull('pi.id');
            });
    }

    protected function summaryQuery()
    {
        return DB::table('wf_entity_purchases as p')
            ->leftJoin('wf_entity_customers as c', 'p.supplier_id', '=', 'c.id')
            ->leftJoin('wf_entity_purchase_items as pi', 'pi.case_number', '=', 'p.case_number')
            ->select([
                DB::raw('COALESCE(SUM(pi.quantity), 0) as total_quantity'),
                DB::raw('COALESCE(SUM(pi.total), 0) as items_total'),
                DB::raw('COALESCE(SUM(p.total_amount), 0) as total_invoice_amount'),
            ])
            ->whereNull('p.deleted_at')
            ->where(function ($query) {
                $query->whereNull('pi.deleted_at')
                    ->orWhereNull('pi.id');
            });
    }

    protected function applyFilters($query, array $filters): void
    {
        if (!empty($filters['customer'])) {
            $query->where('c.name', 'like', '%' . $filters['customer'] . '%');
        }

        if (!empty($filters['invoice_no'])) {
            $query->where('p.invoice_no', 'like', '%' . $filters['invoice_no'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('p.status', $filters['status']);
        }

        if (!empty($filters['currency_unit'])) {
            $query->where('p.currency_unit', $filters['currency_unit']);
        }

        if (!empty($filters['case_number'])) {
            $query->where('p.case_number', 'like', '%' . $filters['case_number'] . '%');
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('p.purchase_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('p.purchase_date', '<=', $filters['to_date']);
        }
    }
}
