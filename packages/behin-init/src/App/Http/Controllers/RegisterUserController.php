<?php

namespace BehinInit\App\Http\Controllers;

use App\Http\Controllers\Controller;
use BaleBot\Controllers\BotController;
use BehinUserRoles\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'digits:11', 'lowercase', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
        ], [
            'name.required' => 'نام الزامی است.',
            'email.required' => 'شماره موبایل الزامی است.',
            'email.digits' => 'شماره موبایل باید ۱۱ رقم باشد.',
            'email.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
            'password.required' => 'رمز عبور الزامی است.',
            'password.min' => 'رمز عبور حداقل باید 8 کارکتر باشد'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 3
            ]);
            BotController::send("کاربر جدید ثبت نام کرد: {$user->name}");
        } catch (\Throwable $e) {
            BotController::send("خطایی در ثبت نام رخ داده است: {$e->getMessage()}
            شماره کاربر: {$request->email}");
            return back()->withErrors(['register' => 'خطایی در ثبت نام رخ داده است.'])->withInput();
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('admin.dashboard', absolute: false));
    }
}
