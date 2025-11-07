<?php

namespace Behin\SimpleWorkflowReport\Controllers\Core;

use App\Http\Controllers\Controller;
use Behin\SimpleWorkflow\Models\Core\Task;
use Behin\SimpleWorkflowReport\Exports\StageReportExport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StageReportController extends Controller
{
    protected array $openStatuses = ['new', 'opened', 'inProgress', 'draft'];

    public function index(Request $request): View
    {
        $selectedTaskIds = $this->selectedTaskIds($request);

        return view('SimpleWorkflowReportView::Core.Stage.index', [
            'rows' => $this->fetchRows($selectedTaskIds),
            'tasks' => $this->fetchTasks(),
            'selectedTasks' => $selectedTaskIds,
        ]);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $selectedTaskIds = $this->selectedTaskIds($request);

        return Excel::download(
            new StageReportExport($this->fetchRows($selectedTaskIds)),
            'stage-report.xlsx'
        );
    }

    protected function selectedTaskIds(Request $request): array
    {
        $tasks = array_map(function ($value) {
            return is_numeric($value) ? (int) $value : null;
        }, (array) $request->input('tasks', []));

        return array_values(array_filter($tasks, function ($value) {
            return ! is_null($value);
        }));
    }

    protected function fetchRows(array $selectedTaskIds): Collection
    {
        $caseVariables = DB::table('wf_variables')
            ->select(
                'case_id',
                DB::raw("MAX(CASE WHEN `key` = 'customer_workshop_or_ceo_name' THEN value END) as customer_workshop_or_ceo_name"),
                DB::raw("MAX(CASE WHEN `key` = 'customer_fullname' THEN value END) as customer_fullname"),
                DB::raw("MAX(CASE WHEN `key` = 'user-firstname' THEN value END) as customer_firstname"),
                DB::raw("MAX(CASE WHEN `key` = 'user-lastname' THEN value END) as customer_lastname"),
                DB::raw("MAX(CASE WHEN `key` = 'user-mobile' THEN value END) as customer_mobile"),
                DB::raw("MAX(CASE WHEN `key` IN ('user-nid','user-national_id') THEN value END) as customer_national_id"),
                DB::raw("MAX(CASE WHEN `key` = 'customer_province' THEN value END) as customer_province"),
                DB::raw("MAX(CASE WHEN `key` = 'customer_address' THEN value END) as customer_address"),
                DB::raw("MAX(CASE WHEN `key` = 'customer_postal_code' THEN value END) as customer_postal_code"),
                DB::raw("MAX(CASE WHEN `key` = 'customer_city' THEN value END) as customer_city"),
                DB::raw("MAX(CASE WHEN `key` IN ('request-contractor_id','contractor_id') THEN value END) as request_contractor_id"),
                DB::raw("MAX(CASE WHEN `key` IN ('powerhouse_place_info-id','powerhouse_place_info_id') THEN value END) as powerhouse_place_info_id"),
                DB::raw("MAX(CASE WHEN `key` IN ('electricity_bill_id','bill_id','request-bill_id','powerhouse_place_info-bill_id','powerhouse_place_info-electricity_bill_id','electricity_bill_identifier','subscription_code','subscription_id','subscriber_id') THEN value END) as bill_identifier")
            )
            ->groupBy('case_id');

        $openCases = DB::table('wf_inbox')
            ->select('case_id', 'task_id')
            ->whereIn('status', $this->openStatuses)
            ->distinct();

        $query = DB::table('wf_cases as cases')
            ->joinSub($openCases, 'open_cases', function ($join) {
                $join->on('cases.id', '=', 'open_cases.case_id');
            })
            ->leftJoin('wf_task as tasks', 'tasks.id', '=', 'open_cases.task_id')
            ->leftJoin('wf_process as process', 'process.id', '=', 'cases.process_id')
            ->leftJoin('users as creators', 'creators.id', '=', 'cases.creator')
            ->leftJoinSub($caseVariables, 'vars', function ($join) {
                $join->on('vars.case_id', '=', 'cases.id');
            })
            ->leftJoin('users as contractors', 'contractors.id', '=', 'vars.request_contractor_id')
            ->leftJoin('wf_entity_powerhouse_place_info as place', 'place.id', '=', 'vars.powerhouse_place_info_id')
            ->select([
                'cases.id as case_id',
                'cases.number as case_number',
                'cases.process_id',
                'process.name as process_name',
                'open_cases.task_id',
                'tasks.name as task_name',
                'tasks.name as task_styled_name',
                'creators.email as creator_email',
                'contractors.id as contractor_id',
                'contractors.name as contractor_name',
                'vars.customer_workshop_or_ceo_name',
                'vars.customer_fullname',
                'vars.customer_firstname',
                'vars.customer_lastname',
                'vars.customer_mobile',
                'vars.customer_national_id',
                'vars.customer_province',
                'vars.customer_address',
                'vars.customer_postal_code',
                'vars.customer_city',
                'vars.bill_identifier',
                'place.province as powerhouse_province',
                'place.address as powerhouse_address',
                'place.postal_code as powerhouse_postal_code',
            ])
            ->orderByDesc('cases.created_at');

        if (! empty($selectedTaskIds)) {
            $query->whereIn('open_cases.task_id', $selectedTaskIds);
        }

        return $query->get()->map(function ($row) {
            $nameCandidates = [
                $row->customer_workshop_or_ceo_name,
                $row->customer_fullname,
                trim(($row->customer_firstname ? $row->customer_firstname : '') . ' ' . ($row->customer_lastname ? $row->customer_lastname : '')),
            ];
            $nameCandidates = array_filter(array_map(function ($value) {
                return $value ? trim($value) : null;
            }, $nameCandidates));

            $row->customer_name = $nameCandidates ? reset($nameCandidates) : null;
            $row->province = $row->customer_province ?: $row->powerhouse_province;
            $row->address = $row->customer_address ?: $row->powerhouse_address;
            $row->postal_code = $row->customer_postal_code ?: $row->powerhouse_postal_code;
            $row->current_stage = trim(strip_tags($row->task_styled_name ?? $row->task_name ?? '')) ?: null;

            return [
                'case_id' => $row->case_id,
                'case_number' => $row->case_number,
                'customer_name' => $row->customer_name,
                'customer_mobile' => $row->creator_email ? trim($row->creator_email) : ($row->customer_mobile ? trim($row->customer_mobile) : null),
                'customer_national_id' => $row->customer_national_id,
                'province' => $row->province,
                'contractor' => $row->contractor_name,
                'bill_identifier' => $row->bill_identifier,
                'postal_code' => $row->postal_code,
                'address' => $row->address,
                'current_stage' => $row->current_stage,
            ];
        })->values();
    }

    protected function fetchTasks(): Collection
    {
        return Task::with('process')
            ->whereIn('id', function ($query) {
                $query->select('task_id')
                    ->from('wf_inbox')
                    ->whereIn('status', $this->openStatuses);
            })
            ->orderBy('process_id')
            ->orderBy('name')
            ->get()
            ->groupBy('process_id');
    }
}
