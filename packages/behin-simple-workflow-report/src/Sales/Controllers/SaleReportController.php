<?php

namespace Behin\SimpleWorkflowReport\Sales\Controllers;

use Behin\SimpleWorkflow\Models\Core\Cases;
use Behin\SimpleWorkflow\Models\Entities;

class SaleReportController
{
    private $processId = '8a838a89-9d36-4c7f-a14d-82d9408bce2e'; // TODO: get this from config or request
    public function index()
    {

        $cases = Cases::where('process_id', $this->processId)->get()->each(function($row){
            $row->sale = Entities\Sales::where('case_number', $row->number)->first();
            $row->saleItems = Entities\Sale_items::where('case_number', $row->number)->get();
            $row->financialTransaction = Entities\Transactions::where('case_number', $row->number)->get();
            $row->inventoryTransaction = Entities\Inventory_transactions::where('case_number', $row->number)->get();
        });
        return view('SalesReportView::index', compact('cases'));
    }
}