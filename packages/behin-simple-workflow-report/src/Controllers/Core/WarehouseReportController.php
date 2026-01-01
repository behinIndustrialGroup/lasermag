<?php

namespace Behin\SimpleWorkflowReport\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Behin\SimpleWorkflow\Models\Entities\Products;
use Behin\SimpleWorkflow\Models\Entities\Inventory_transactions;
use Illuminate\Support\Facades\Log;

class WarehouseReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->all();
        $perPage = (int) ($filters['per_page'] ?? 25);
        $perPage = $perPage > 0 ? $perPage : 25;

        $query = $this->baseQuery();
        

        $rows = $query->get()->each(function ($row) {
            // Process each row if needed
            $in = Inventory_transactions::where('product_id', $row->product_id)->where('inventory_transaction_type', 'افزایش')->sum('quantity');
            Log::info($row->product_id . ': '. $in);
            $out = Inventory_transactions::where('product_id', $row->product_id)->where('inventory_transaction_type', 'کاهش')->sum('quantity');
            $row->stock = $in - $out;
            $row->save();
        });


        return view('SimpleWorkflowReportView::Core.Warehouses.index', [
            'rows' => $rows,
        ]);
    }

    protected function baseQuery()
    {
        $products = Products::query();
        return $products;
    }


    
}
