<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstallerApplicationRequest;
use App\Models\InstallerApplication;
use BaleBot\Controllers\BotController;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InstallerRegistrationController extends Controller
{
    public function create(): View
    {
        return view('installer.apply');
    }

    public function store(StoreInstallerApplicationRequest $request): RedirectResponse
    {
        InstallerApplication::create($request->validated());
        BotController::send(
            $request->first_name . ' ' . $request->last_name . ' با شماره موبایل ' . $request->phone . ' ثبت نام کرد.', 
        );
        return redirect()
            ->route('installers.apply')
            ->with('status', 'درخواست شما با موفقیت ثبت شد. همکاران ما به زودی با شما تماس می‌گیرند.');
    }
}
