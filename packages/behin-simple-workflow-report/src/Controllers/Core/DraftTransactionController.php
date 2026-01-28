<?php

namespace Behin\SimpleWorkflowReport\Controllers\Core;

use App\Http\Controllers\Controller;
use Behin\SimpleWorkflow\Controllers\Core\CaseController;
use Behin\SimpleWorkflow\Controllers\Core\FormController;
use Behin\SimpleWorkflow\Controllers\Core\InboxController;
use Behin\SimpleWorkflow\Controllers\Core\ProcessController;
use Behin\SimpleWorkflow\Controllers\Core\TaskController;
use Behin\SimpleWorkflow\Controllers\Core\VariableController;
use Behin\SimpleWorkflow\Models\Core\Cases;
use Behin\SimpleWorkflow\Models\Core\Entity;
use Behin\SimpleWorkflow\Models\Core\Process;
use Behin\SimpleWorkflow\Models\Core\TaskActor;
use Behin\SimpleWorkflow\Models\Core\Variable;
use Behin\SimpleWorkflow\Models\Entities\Creditor;
use Behin\SimpleWorkflow\Models\Entities\Financials;
use Behin\SimpleWorkflow\Models\Entities;
use Behin\SimpleWorkflowReport\Exports\UserFinancialTransactionExport;
use Behin\SimpleWorkflowReport\Helper\ReportHelper;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;
use Behin\SimpleWorkflow\Models\Entities\Transactions;
use Behin\SimpleWorkflow\Models\Entities\Customers;
use Behin\SimpleWorkflow\Models\Entities\Draft_transactions;
use Behin\SimpleWorkflowReport\Exports\CounterpartyFinancialTransactionExport;
use BehinFileControl\Controllers\FileController;
use Exception;
use Illuminate\Validation\Rule;

class DraftTransactionController extends Controller
{

    public function index($caseNumber = null)
    {
        if (!$caseNumber) {
            $startTask = "36b99d60-a89a-4fb1-92e9-9ccbd70af1af";
            $inbox = ProcessController::startFromScript($startTask, Auth::id(), null);
            $caseNumber = $inbox->case->number;
            $inbox->status = 'done';
            $inbox->save();
            return redirect()->route('simpleWorkflowReport.draftTransaction.index', $caseNumber);
        }
        $draftRecord = Draft_transactions::where('case_number', $caseNumber)->get();

        return view('SimpleWorkflowReportView::Core.DraftTransaction.index', compact('caseNumber', 'draftRecord'));
    }


    public function create($caseNumber)
    {
        $counterParties = Customers::all();
        return view('SimpleWorkflowReportView::Core.DraftTransaction.create', compact('caseNumber', 'counterParties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'date_alt' => 'required',
            'case_number' => 'required',
            'description' => 'nullable'
        ]);
        Draft_transactions::create($validated);
        return redirect()->route('simpleWorkflowReport.draftTransaction.index', $request->case_number);
    }

    public function storeToTransactions($caseNumber)
    {
        $total = 0;
        $draftRecord = Draft_transactions::where('case_number', $caseNumber)->get();
        foreach ($draftRecord as $item) {
            if ($item->type == 'بدهکار') {
                $total -= $item->amount;
            } else {
                $total += $item->amount;
            }
        }
        if ($total != 0) {
            return redirect()->back()->with(['error' => 'مجموع صفر نیست']);
        }

        //حذف تمام تراکنش ها 
        // در حالتی که یک سند را ویرایش میکنند استفاده میشود
        //یکبار همه تراکنش های با این شماره پرونده را حذف میکند و مجدد ایجاد میکند
        Transactions::where('case_number', $caseNumber)->get()->each(function ($row) {
            $row->delete();
        });

        // برای تمام رکودهای سند حسابداری یک تراکنش ایجاد میکنیم
        foreach ($draftRecord as $item) {
            Transactions::create([
                'account_id' => $item->account_id,
                'type' => $item->type,
                'amount' => $item->amount,
                'date' => $item->date,
                'date_alt' => $item->date_alt,
                'case_number' => $item->case_number,
                'description' => $item->description
            ]);
        }
        return redirect()->route('simpleWorkflowReport.financial-transactions.index');
    }

    public function destroy(Draft_transactions $draftTransaction)
    {
        try {
            $draftTransaction->delete();
            return redirect()->back()->with(['success' => 'حذف شد']);
        } catch (Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit(Draft_transactions $draftTransaction)
    {
        try {
            $counterParties = Customers::all();
            $caseNumber = $draftTransaction->case_number;
            return view('SimpleWorkflowReportView::Core.DraftTransaction.edit', compact('caseNumber', 'counterParties', 'draftTransaction'));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, Draft_transactions $draftTransaction)
    {
        try {
            $draftTransaction->update($request->all());
            return $this->index($draftTransaction->case_number);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function copy(Draft_transactions $draftTransaction)
    {
        try {
            Draft_transactions::create($draftTransaction->toArray());
            return redirect()->back()->with(['success' => 'حذف شد']);
        } catch (Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function archive()
    {
        $draftTransactions = Draft_transactions::whereNotNull('case_number')->groupBy('case_number')->get()->each(function ($row) {
            $row->createdAt = Cases::where('number', $row->case_number)->first()?->created_at;
            $row->details = Draft_transactions::where('case_number', $row->case_number)->get();
        });
        return view('SimpleWorkflowReportView::Core.DraftTransaction.archive', compact('draftTransactions'));
    }
}
