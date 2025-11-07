<?php

use App\Http\Controllers\InstallerRegistrationController;
use App\Models\User;
use BaleBot\Controllers\BotController;
use Behin\SimpleWorkflow\Controllers\Core\CaseController;
use Behin\SimpleWorkflow\Controllers\Core\PushNotifications;
use Behin\SimpleWorkflow\Controllers\Core\VariableController;
use Behin\SimpleWorkflow\Jobs\SendPushNotification;
use BehinInit\App\Http\Middleware\Access;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmeRegistrationController;

Route::get('', function(){
    if(Auth::check()){
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::get('landing/sme-registration', [SmeRegistrationController::class, 'create'])->name('landing.sme-registration');
Route::post('landing/sme-registration', [SmeRegistrationController::class, 'store'])->name('landing.sme-registration');

Route::get('installers/apply', [InstallerRegistrationController::class, 'create'])->name('installers.apply');
Route::post('installers/apply', [InstallerRegistrationController::class, 'store'])->name('installers.store');


require __DIR__.'/auth.php';
require __DIR__.'/services.php';

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth', Access::class])->group(function(){
    Route::get('', function(){
        return view('admin.dashboard');
    })->name('dashboard');
});

Route::get('/pusher/beams-auth', function (Request $request) {
    $beamsClient = new PushNotifications([
        'instanceId' => config('broadcasting.pusher.instanceId'),
        'secretKey' => config('broadcasting.pusher.secretKey')
    ]);
    $userId = auth()->user()->id;
    $beamsToken = $beamsClient->generateToken('user-mobile-'.$userId);
    // $user = User::find($userId);
    return response()->json($beamsToken);
})->middleware('auth');

Route::get('send-notification', function () {
    SendPushNotification::dispatch(Auth::user()->id, 'test', 'test', route('admin.dashboard'));
    return 'تا دقایقی دیگر باید نوتیفیکیشن دریافت کنید';
})->name('send-notification');

Route::get('queue-work', function () {
    $limit = 5; // تعداد jobهای پردازش شده در هر درخواست
    $jobs = DB::table('jobs')->orderBy('id')->limit($limit)->get();

    foreach ($jobs as $job) {
        try {
            // دیکد کردن محتوای job
            $payload = json_decode($job->payload, true);
            $command = unserialize($payload['data']['command']);

            // اجرای job
            $command->handle();

            // حذف job پس از اجرا
            DB::table('jobs')->where('id', $job->id)->delete();

            // return 'Job processed: ' . $job->id;
        } catch (Exception $e) {
            // در صورت خطا، job را به جدول failed_jobs منتقل کنید
            DB::table('failed_jobs')->insert([
                'connection' => $job->connection ?? 'database',
                'queue' => $job->queue,
                'payload' => $job->payload,
                'exception' => (string) $e,
                'failed_at' => now()
            ]);

            DB::table('jobs')->where('id', $job->id)->delete();

            return 'Job failed: ' . $e->getMessage();
        }
    }
});

Route::get('build-app', function(){
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('migrate');
    return redirect()->back();
});



