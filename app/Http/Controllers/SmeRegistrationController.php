<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstallerApplicationRequest;
use App\Models\InstallerApplication;
use BaleBot\Controllers\BotController;
use Behin\SimpleWorkflow\Controllers\Core\ProcessController;
use BehinUserRoles\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SmeRegistrationController extends Controller
{
    public function create(): View
    {
        return view('landing.sme-registration');
    }

    public function store(Request $request): RedirectResponse
    {
        $mobile = convertPersianToEnglish($request->mobile);
        $user = User::create([
            'mobile' => $mobile,
            'password' => Hash::make(rand(100000, 999999)),
        ]);
        $inbox = ProcessController::start(
            "c1462858-5560-431d-a8c0-f0239038bde7",
            $user->id,
            false,
            false
        );

        $case = $inbox->case;
        $case->saveVariable('use_type', 'صنفی');
        $case->saveVariable('user-firstname', $request->firstname);
        $case->saveVariable('user-lastname', $request->lastname);
        $case->saveVariable('user-mobile', $mobile);
        $case->saveVariable('user-national_id', $request->national_id);
        $case->saveVariable('user-union', $request->union);
        $case->saveVariable('powerhouse_place_info-province', $request->province);
        $case->saveVariable('user-description', $request->description);

        BotController::send(
            "درخواست شماره " . $case->number . "از طریق لینک اصناف ثبت شد"
        );

        return redirect()
            ->route('landing.sme-registration')
            ->with('status', 'درخواست شما با موفقیت ثبت شد. همکاران ما به زودی با شما تماس می‌گیرند.');
    }
}
