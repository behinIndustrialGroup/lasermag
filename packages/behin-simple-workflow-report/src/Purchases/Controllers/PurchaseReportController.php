<?php

namespace Behin\SimpleWorkflowReport\Purchases\Controllers;

use Behin\SimpleWorkflow\Models\Core\Cases;
use Behin\SimpleWorkflow\Models\Entities;

class PurchaseReportController
{
    private $processId = '19cb9d7d-f5c6-4b2b-ba94-9e10a9c34516'; // TODO: get this from config or request
    public function index()
    {

        $cases = Cases::where('process_id', $this->processId)->get()->each(function($row){
            $row->purchase = Entities\Purchases::where('case_number', $row->number)->first();
            $row->purchaseItems = Entities\Purchase_items::where('case_number', $row->number)->get();
            $row->financialTransaction = Entities\Transactions::where('case_number', $row->number)->get();
            $row->inventoryTransaction = Entities\Inventory_transactions::where('case_number', $row->number)->get();
        });
        return view('PurchasesReportView::index', compact('cases'));
    }
}