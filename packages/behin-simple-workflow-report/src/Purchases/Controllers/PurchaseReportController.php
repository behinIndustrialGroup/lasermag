<?php

namespace Behin\SimpleWorkflowReport\Purchases\Controllers;

use Behin\SimpleWorkflow\Controllers\Core\CaseController;
use Behin\SimpleWorkflow\Controllers\Core\FormController;
use Behin\SimpleWorkflow\Models\Core\Cases;
use Behin\SimpleWorkflow\Models\Entities;
use Exception;

class PurchaseReportController
{
    private $processId = '19cb9d7d-f5c6-4b2b-ba94-9e10a9c34516'; // TODO: get this from config or request
    public function index()
    {
        $cases = Cases::where('process_id', $this->processId)->get()->each(function ($row) {
            $row->purchase = Entities\Purchases::where('case_number', $row->number)->first();
            $row->purchaseItems = Entities\Purchase_items::where('case_number', $row->number)->get();
            $row->financialTransaction = Entities\Transactions::where('case_number', $row->number)->get();
            $row->inventoryTransaction = Entities\Inventory_transactions::where('case_number', $row->number)->get();
        });
        return view('PurchasesReportView::index', compact('cases'));
    }

    public function edit(Cases $case)
    {
        try {
            $editFormId = "c6609f7f-5d1c-4fc4-b4af-79a5d3ddcad5";
            $form = FormController::getById($editFormId);
            $formMode = null;
            return view('PurchasesReportView::edit', compact('case', 'form', 'formMode'));
        } catch (Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
