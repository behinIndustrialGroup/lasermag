<?php

namespace Behin\SimpleWorkflowReport\Controllers\Core;

use App\Http\Controllers\Controller;
use Behin\SimpleWorkflow\Controllers\Core\CaseController;
use Behin\SimpleWorkflow\Controllers\Core\FormController;
use Behin\SimpleWorkflow\Controllers\Core\InboxController;
use Behin\SimpleWorkflow\Controllers\Core\ProcessController;
use Behin\SimpleWorkflow\Controllers\Core\TaskController;
use Behin\SimpleWorkflow\Controllers\Core\VariableController;
use Behin\SimpleWorkflow\Models\Core\Process;
use Behin\SimpleWorkflow\Models\Core\TaskActor;
use Behin\SimpleWorkflow\Models\Core\Variable;
use Behin\SimpleWorkflow\Models\Entities\Creditor;
use Behin\SimpleWorkflow\Models\Entities\Financials;
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
use Behin\SimpleWorkflowReport\Exports\CounterpartyFinancialTransactionExport;
use BehinFileControl\Controllers\FileController;
use Illuminate\Validation\Rule;

class FinancialTransactionController extends Controller
{
    public function prepareData($request)
    {
        $filter = $request->query('filter', 'negative');
        $caseNumber = $request->query('case_number');
        $onlyAssignedUsers = $request->boolean('only_assigned', false);

        $totalAmountExpression = "SUM(CASE
                WHEN type = 'بدهکار' THEN -amount
                WHEN type = 'بستانکار' THEN amount
                ELSE 0
            END)";

        $creditorsQuery = Transactions::select(
            'account_id',
            DB::raw("{$totalAmountExpression} as total_amount"),
        )
            ->when($caseNumber !== null && $caseNumber !== '', function ($query) use ($caseNumber) {
                $query->where('case_number', $caseNumber);
            })
            ->when($onlyAssignedUsers, function ($query) {
                $assignCounterParties = Customers::whereNotNull('user_id')->pluck('id');
                $query->whereIn('account_id', $assignCounterParties);
            }, function ($query) {
                // وقتی onlyAssignedUsers = false
                $unassignedCounterParties = Customers::whereNull('user_id')->pluck('id');
                $query->whereIn('account_id', $unassignedCounterParties);
            })
            ->groupBy('account_id');

        switch ($filter) {
            case 'positive':
                $creditorsQuery->havingRaw("{$totalAmountExpression} > 0");
                break;
            case 'all':
                break;
            default:
                $filter = 'negative';
                $creditorsQuery->havingRaw("{$totalAmountExpression} < 0");
                break;
        }

        return $creditorsQuery->get();
    }
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'negative');
        $caseNumber = $request->query('case_number');
        $onlyAssignedUsers = $request->boolean('only_assigned', false);
        $creditors = $this->prepareData($request);
        $account_idDebit = $creditors->where('total_amount', '<', 0)->sum(function ($item) {
            return abs($item->total_amount);
        });
        $account_idCredit = $creditors->where('total_amount', '>', 0)->sum('total_amount');
        $account_idBalance =  Transactions::select(
            DB::raw("
            SUM(
                CASE
                    WHEN type = 'بدهکار' THEN amount
                    ELSE 0
                END
            ) AS total_debit
        "),
            // جمع بستانکاری
            DB::raw("
            SUM(
                CASE
                    WHEN type = 'بستانکار' THEN amount
                    ELSE 0
                END
            ) AS total_credit
        "),

            DB::raw("
        SUM(
            CASE
                WHEN type = 'بستانکار' THEN amount
                ELSE -amount
            END
        ) AS balance
    ")
        )->groupBy('account_id')->get();

        $totalDebit = $account_idBalance
            ->where('balance', '<', 0)
            ->sum(fn($item) => abs($item->balance));

        $totalCredit = $account_idBalance
            ->where('balance', '>', 0)
            ->sum('balance');


        $balance = Transactions::select(
            DB::raw("
            SUM(
                CASE
                    WHEN type = 'بدهکار' THEN -amount
                    WHEN type = 'بستانکار' THEN amount
                    ELSE 0
                END
            ) AS total_amount
        "),
            // جمع بدهکاری
            DB::raw("
            SUM(
                CASE
                    WHEN type = 'بدهکار' THEN amount
                    ELSE 0
                END
            ) AS total_debit
        "),
            // جمع بستانکاری
            DB::raw("
            SUM(
                CASE
                    WHEN type = 'بستانکار' THEN amount
                    ELSE 0
                END
            ) AS total_credit
        ")
        )->first();

        return view(
            'SimpleWorkflowReportView::Core.FinancialTransaction.index',
            compact('creditors', 'filter', 'caseNumber', 'balance', 'account_idDebit', 'account_idCredit', 'account_idBalance', 'totalDebit', 'totalCredit')
        );
    }

    public function userIndex(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $caseNumber = $request->query('case_number');
        $onlyAssignedUsers = $request->boolean('only_assigned', true);
        $request->merge(['only_assigned' => true]);
        $request->merge(['filter' => 'all']);
        $counterParties = Customers::whereNotNull('user_id')->get()->each(function ($row) {
            $row->user_max_advance = EmployeeSalaryReportController::userMaxAdvances($row->user_id);
        });

        $creditors = $this->prepareData($request);
        return view('SimpleWorkflowReportView::Core.UserFinancialTransaction.index', compact('creditors', 'filter', 'caseNumber', 'counterParties'));
    }

    public function openUserSalaryAdvances($account_id)
    {
        $account_id = Customers::find($account_id);
        if (!$account_id->user_id) {
            return "برای این طرف حساب نمیتوانید حساب مساعده باز کنید";
        }

        // گرفتن مجموع تراکنش‌ها برای این طرف حساب
        $request = new Request([
            'filter' => 'all'
        ]);
        $creditors = $this->prepareData($request);

        // پیدا کردن رکورد این کاربر
        $creditor = $creditors->where('account_id', $account_id->id)->first();
        $totalAmount = $creditor ? $creditor->total_amount : 0;

        // اگر total_amount صفر نبود، عملیات انجام نشود
        if ($totalAmount != 0) {
            return redirect()->back()->with('error', 'برای این کاربر به دلیل داشتن مانده حساب، امکان باز کردن مساعده وجود ندارد.');
        }

        $userMaxAdvances = EmployeeSalaryReportController::userMaxAdvances($account_id->user_id);
        $request = new Request([
            'financial_method' => 'نقدی',
            'description' => 'بازکردن مساعده',
            'account_id' => $account_id->id,
            'amount' => $userMaxAdvances
        ]);
        $this->addCredit($request);
        return redirect()->back();
    }

    public function openUserSalaryAdvancesBulk(Request $request)
    {
        $userIds = $request->users ?? [];

        if (empty($userIds)) {
            return response()->json([
                'status' => 'error',
                'message' => 'هیچ کاربری ارسال نشده است'
            ]);
        }

        foreach ($userIds as $id) {
            // فرض می‌کنیم روت تکی از یک تابع داخلی استفاده می‌کند
            $this->openUserSalaryAdvances($id);
        }

        return response()->json(['status' => 'ok']);
    }


    public function closeUserSalaryAdvances($account_id)
    {
        $account_id = Customers::find($account_id);
        if (!$account_id->user_id) {
            return "برای این طرف حساب نمیتوانید حساب مساعده باز کنید";
        }
        $request = new Request([
            'filter' => 'all'
        ]);
        $creditors = $this->prepareData($request);
        $creditor = $creditors->where('account_id', $account_id->id);
        $totalAmount = $creditor->first() ? $creditor->first()->total_amount : 0;
        if ($totalAmount > 0) {
            $request = new Request([
                'financial_method' => 'نقدی',
                'description' => 'بستن مساعده',
                'account_id' => $account_id->id,
                'amount' => $totalAmount
            ]);
            $this->addDebit($request);
        }

        if ($totalAmount < 0) {
            $request = new Request([
                'financial_method' => 'نقدی',
                'description' => 'بستن مساعده',
                'account_id' => $account_id->id,
                'amount' => $totalAmount
            ]);
            $this->addCredit($request);
        }
        return redirect()->back();
    }

    public function userExport(Request $request)
    {
        $request->merge(['only_assigned' => true]);
        $request->merge(['filter' => 'all']);
        $counterParties = Customers::whereNotNull('user_id')->get()->each(function ($row) {
            $row->user_max_advance = EmployeeSalaryReportController::userMaxAdvances($row->user_id);
        });
        $creditors = $this->prepareData($request);
        $data = [];
        foreach ($counterParties as $counterParty) {
            $creditorInfo = $creditors->where('account_id', $counterParty->id);
            $totalAmount = $creditorInfo->first() ? $creditorInfo->first()->total_amount : 0;
            $rest = $counterParty->user_max_advance - $totalAmount;
            $data[] = [
                'user_number' => getUserInfo($counterParty->user_id)->number,
                'user_name' => $counterParty->name ?? '',
                'user_max_advance' => number_format($counterParty->user_max_advance),
                'total_amount' => number_format($totalAmount),
            ];
        }

        return Excel::download(new UserFinancialTransactionExport(collect($data)), 'user_financial_transactions.xlsx');
    }


    public function show(Request $request, $account_id)
    {
        $showHeaderBtn = $request->input('showHeaderBtn', '1') == '1';
        $creditors = Transactions::where('account_id', $account_id)->get();
        return view('SimpleWorkflowReportView::Core.FinancialTransaction.show', compact('creditors', 'showHeaderBtn'));
    }

    public function export($account_id)
    {
        $account_id = Customers::find($account_id);
        $creditors = Transactions::where('account_id', $account_id->id)->get();
        $data = [];
        foreach ($creditors as $creditor) {
            $data[] = [
                'type' => $creditor->type,
                'account_id' => $creditor->account_id()->name,
                'amount' => $creditor->amount,
                'case_number' => $creditor->case_number,
                'financial_method' => $creditor->financial_method,
                'invoice_or_cheque_number' => $creditor->invoice_or_cheque_number,
                'transaction_or_cheque_due_date' => $creditor->transaction_or_cheque_due_date,
                'destination_account_name' => $creditor->destination_account_name,
                'destination_account_number' => $creditor->destination_account_number,
                'description' => $creditor->description,

            ];
        }
        return Excel::download(new CounterpartyFinancialTransactionExport(collect($data)), 'گزارش تراکنش های ' . $account_id->name . '.xlsx');
    }

    public function edit(Transactions $financialTransaction)
    {
        $counterParties = Customers::all();

        return view(
            'SimpleWorkflowReportView::Core.FinancialTransaction.edit',
            compact('financialTransaction', 'counterParties')
        );
    }

    public function showAddCredit($account_id = null)
    {
        $account_id = Customers::find($account_id);
        $counterParties = Customers::all();
        return view('SimpleWorkflowReportView::Core.FinancialTransaction.add-credit', compact('account_id', 'counterParties'));
    }

    public function addCredit(Request $request)
    {
        if ($request->has_destination_account) {
            $validated = $request->validate([
                'amount' => 'required',
                'account_id' => 'required|exists:wf_entity_customers,id',
            ], [
                'amount.required' => 'مبلغ الزامی است',
                'account_id.required' => 'طرف حساب الزامی است',
                'account_id.exists' => 'طرف حساب انتخاب شده معتبر نیست',
            ]);
            $destinationCounterparty = DB::table('wf_entity_customers')->where('id', $request->destination_account_id)->first();
        } else {
            $validated = $request->validate([
                'amount' => 'required',
                'account_id' => 'required|exists:wf_entity_customers,id',
            ], [
                'amount.required' => 'مبلغ الزامی است',
                'account_id.required' => 'طرف حساب الزامی است',
                'account_id.exists' => 'طرف حساب انتخاب شده معتبر نیست',
            ]);
        }

        if ($request->store_in_pretty_cash) {
            $validated = $request->validate([
                'description' => 'required',
                'amount' => 'required',
                'transaction_or_cheque_due_date' => 'required',
                'account_id' => 'required|exists:wf_entity_counter_parties,id',
            ], [
                'description.required' => 'توضیحات الزامی است',
                'amount.required' => 'مبلغ الزامی است',
                'transaction_or_cheque_due_date.required' => 'تاریخ تراکنش الزامی است',
                'account_id.required' => 'طرف حساب الزامی است',
            ]);
        }

        $counterParty = DB::table('wf_entity_customers')->where('id', $request->account_id)->first();
        $amount = str_replace(',', '', $request->amount);
        $finTransaction = Transactions::create([
            'case_number' => $request->case_number,
            'type' => 'بستانکار',
            'financial_method' => $request->financial_method,
            'description' => $request->description,
            'account_id' => $request->account_id,
            'amount' => (string)$amount,
            'reference_id' => $request->reference_id,
            'date' => $request->date,
            'date_alt' => $request->date_alt,
            'destination_account_id' => $request->destination_account_id ?? null,
        ]);
        if (isset($destinationCounterparty)) {
            $autoFinTransaction = Transactions::create([
                'case_number' => $request->case_number,
                'type' => 'بدهکار',
                'financial_method' => $request->financial_method,
                'description' => 'تراکنش خودکار. واریزی ' . $counterParty->name,
                'account_id' => $destinationCounterparty->id,
                'amount' => (string)$amount,
                'reference_id' => $request->reference_id,
                'date' => $request->date,
                'date_alt' => $request->date_alt,
            ]);
            $finTransaction->auto_financial_transaction_id = $autoFinTransaction->id;
            $finTransaction->save();
        }
        if ($request->store_in_pretty_cash) {
            $data = new Request([
                'title' => $request->description,
                'amount' => (string)$amount,
                'paid_at' => $request->transaction_or_cheque_due_date,
                'from_account' => $counterParty->name,
            ]);
            PettyCashController::store($data);
        }
        return redirect()->back(); //->route('simpleWorkflowReport.financial-transactions.index');
    }

    public function showAddDebit($account_id = null, $onlyAssignedUsers = false)
    {
        $account_id = Customers::find($account_id);
        $counterParties = Customers::when($onlyAssignedUsers, function ($query) {
            $query->whereNotNull('user_id');
        })->get();
        return view('SimpleWorkflowReportView::Core.FinancialTransaction.add-debit', compact('account_id', 'counterParties'));
    }

    public function addDebit(Request $request)
    {
        $amount = str_replace(',', '', $request->amount);
        Transactions::create([
            'case_number' => $request->case_number,
            'type' => 'بدهکار',
            'financial_method' => $request->financial_method,
            'description' => $request->description,
            'account_id' => $request->account_id,
            'amount' => (string)$amount,
            'reference_id' => $request->reference_id,
            'date' => $request->date,
            'date_alt' => $request->date_alt,
        ]);
        return redirect()->back(); //->route('simpleWorkflowReport.financial-transactions.index');
    }

    public function update(Request $request, Transactions $financialTransaction)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['بدهکار', 'بستانکار'])],
            'account_id' => ['required'],
            'case_number' => ['nullable', 'string'],
            'amount' => ['required', 'string'],
            'financial_method' => ['nullable', 'string'],
            'invoice_or_cheque_number' => ['nullable', 'string'],
            'transaction_or_cheque_due_date' => ['nullable', 'string'],
            'transaction_or_cheque_due_date_alt' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('file')) {
            $result = FileController::store($request->file('file'), 'simpleWorkflow');
            if ($result['status'] == 200) {
                $financialTransaction->file = $result['dir'];
                $financialTransaction->save();
                return $financialTransaction;
            }
        }

        $amount = str_replace(',', '', $validated['amount']);
        if ($financialTransaction->auto_financial_transaction_id) {
            Transactions::where('id', $financialTransaction->auto_financial_transaction_id)->delete();
        }
        $financialTransaction->delete();

        if ($request->type == 'بستانکار') {
            $this->addCredit($request);
        } else {
            $this->addDebit($request);
        }

        // $financialTransaction->update([
        //     'type' => $validated['type'],
        //     'account_id' => $validated['account_id'],
        //     'case_number' => $validated['case_number'] ?? null,
        //     'amount' => (string) $amount,
        //     'financial_method' => $validated['financial_method'] ?? null,
        //     'invoice_or_cheque_number' => $validated['invoice_or_cheque_number'] ?? null,
        //     'transaction_or_cheque_due_date' => $validated['transaction_or_cheque_due_date'] ?? null,
        //     'transaction_or_cheque_due_date_alt' => $validated['transaction_or_cheque_due_date_alt'] ?? null,
        //     'destination_account_name' => $validated['destination_account_name'] ?? null,
        //     'destination_account_number' => $validated['destination_account_number'] ?? null,
        //     'description' => $validated['description'] ?? null,
        // ]);

        return redirect()
            ->back()
            // ->route('simpleWorkflowReport.financial-transactions.show', $financialTransaction->account_id)
            ->with('success', 'تراکنش با موفقیت ویرایش شد.');
    }

    public function destroy(Transactions $financialTransaction): JsonResponse
    {
        $financialTransaction->delete();

        return response()->json([
            'message' => 'تراکنش با موفقیت حذف شد.',
        ]);
    }
}
