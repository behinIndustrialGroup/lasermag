<?php

namespace Behin\SimpleWorkflowReport\Controllers\Core;

use App\Http\Controllers\Controller;
use Behin\SimpleWorkflow\Controllers\Core\CaseController;
use Behin\SimpleWorkflow\Controllers\Core\FormController;
use Behin\SimpleWorkflow\Controllers\Core\InboxController;
use Behin\SimpleWorkflow\Controllers\Core\ProcessController;
use Behin\SimpleWorkflow\Controllers\Core\TaskController;
use Behin\SimpleWorkflow\Models\Core\Process;
use Behin\SimpleWorkflow\Models\Core\TaskActor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Behin\SimpleWorkflow\Models\Core\Cases;

class MyRequestController extends Controller
{
    public function index(): View
    {
        $cases = Cases::where('creator', Auth::user()->id)->get();
        return view('SimpleWorkflowReportView::Core.MyRequest.index', compact('cases'));
    }

    public function show($case_id)
    {
        $case= CaseController::getById($case_id);
        $view = 'SimpleWorkflowReportView::Core.MyRequest.process.' . $case_id;
        if(view()->exists($view)){
            return view($view, compact('case'));
        }
        return view('SimpleWorkflowReportView::Core.MyRequest.show', compact('case'));
    }

}
