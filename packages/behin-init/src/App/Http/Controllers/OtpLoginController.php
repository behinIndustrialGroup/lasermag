<?php

namespace BehinInit\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Behin\Sms\Controllers\SmsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtpLoginController extends Controller
{
    public function view($phone, $error = null)
    {
        return view('auth.verify-otp')->with(['phone' => $phone, 'error' => $error]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'phone' => ['required','string'],
        ]);
        $phone = convertPersianToEnglish($request->phone);
        $user = User::firstOrCreate(
            ['email' => $phone],
            [
                'name' => $phone,
                'password' => bcrypt(str()->random(12)),
                'role_id' => 3
            ]
        );
        $otp = random_int(100000, 999999);
        $user->reset_password_code = $otp;
        $user->save();
        $params = array([
            'name' => 'CODE',
            'value' => $otp
        ],
        [
            'name' => 'CODE',
            'value' => $otp
        ]);
        SmsController::sendByTemp($user->email, 755370, $params);
        
        return $this->view($user->email);
        return response()->json(['message' => 'کد ارسال شد.']);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'phone' => ['required','string'],
            'otp' => ['required','string'],
        ]);

        $otp = convertPersianToEnglish($request->otp);
        $user = User::where('email', $request->phone)->first();
        if(!$user){
            return $this->view($user->email, trans('auth.user not found'));
        }

        if ($user->reset_password_code == $otp) {
            $user->password = bcrypt(str()->random(12));
            $user->save();
            Auth::login($user, true);
            return redirect()->route('admin.dashboard');
            return view('admin.dashboard');
        }
        return $this->view($user->email, 'کد نامعتبر یا منقضی است');
    }
}
