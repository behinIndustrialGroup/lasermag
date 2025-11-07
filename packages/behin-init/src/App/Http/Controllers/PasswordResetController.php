<?php

namespace BehinInit\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Behin\Sms\Controllers\SmsController;
use Behin\Sms\Controllers\SmsController2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * Display the form to request a password reset code.
     */
    public function request()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle sending of the verification code via SMS.
     */
    public function sendCode(Request $request)
    {
        $validated = $request->validate([
            'mobile' => ['required', 'digits:11'],
        ], [
            'mobile.required' => trans('auth.Mobile is required'),
            'mobile.digits' => trans('auth.Mobile must be 11 digits'),
        ]);

        $code = rand(100000, 999999);
        $msg = "Code: " . $code;
        $msg .= "\n";
        $msg .= "کد بازیابی رمز عبور شما: ". $code;
        SmsController2::send($validated['mobile'], $msg);
        $user = User::where('email', $validated['mobile'])->first();
        if(!$user){
            return redirect()->back()->with('error', trans('auth.user not found'));
        }
        $user->reset_password_code = $code;
        $user->save();
        $request->session()->put('password_reset_mobile', $validated['mobile']);
        return redirect()->route('password.reset')->with('success', trans('auth.A verification code has been sent to your mobile'));
    }

    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request)
    {
        if (! $request->session()->has('password_reset_mobile')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    /**
     * Reset the user's password after verifying the code.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'code.required' => trans('auth.Code is required'),
            'password.required' => trans('auth.Password is required'),
            'password.confirmed' => trans('auth.Passwords must match'),
            'password.min' => trans('auth.Password must be at least 8 characters'),
        ]);

        $user = User::where('email', $request->session()->get('password_reset_mobile'))->first();
        if(!$user){
            return redirect()->back()->with('error', trans('auth.user not found'));
        }

        if ($request->code != $user->reset_password_code) {
            return redirect()->back()->with('error', trans('auth.The provided code is invalid'));
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $request->session()->forget(['password_reset_mobile', 'password_reset_code']);

        return redirect()->route('login')->with('success', trans('auth.Password reset successful'));
    }
}