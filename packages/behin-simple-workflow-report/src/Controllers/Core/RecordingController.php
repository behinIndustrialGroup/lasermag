<?php

namespace Behin\SimpleWorkflowReport\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecordingController extends Controller
{
    public function streamRecording($uniqueid)
    {
        // 1️⃣ پیدا کردن رکورد در دیتابیس CDR ایزابل
        $record = DB::connection('asterisk')->table('cdr')->where('uniqueid', $uniqueid)->first();
        if (!$record || empty($record->recordingfile)) {
            return response()->json([
                'success' => false,
                'message' => 'رکورد یا فایل ضبط یافت نشد.'
            ], 404);
        }

        $recordingFile = $record->recordingfile;
        $fileName = basename($recordingFile);

        $recordingsConfig = config('behin-ami.recordings', []);
        $downloadConfig = $recordingsConfig['download'] ?? [];

        // 2️⃣ ساخت URL کامل فایل با استفاده از اسکریپت دانلود جدید یا مسیر قبلی
        if (!empty($downloadConfig['url'])) {
            $downloadUrl = rtrim($downloadConfig['url'], '/');
            $separator = str_contains($downloadUrl, '?') ? '&' : '?';
            $url = $downloadUrl . $separator . 'file=' . urlencode($fileName);
        } else {
            $baseUrl = $recordingsConfig['base_url'] ?? null; // مثلاً https://91.247.171.3/recordings/
            if (!$baseUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'آدرس دانلود فایل در تنظیمات تعریف نشده است.'
                ], 500);
            }
            $url = rtrim($baseUrl, '/') . '/' . ltrim($recordingFile, '/');
        }

        // 3️⃣ اگر ایزابل نیاز به Basic Auth دارد
        $username = $downloadConfig['username'] ?? env('AMI_WEB_USER', null);
        $password = $downloadConfig['password'] ?? env('AMI_WEB_PASSWORD', null);

        $verifySsl = $downloadConfig['verify_ssl'] ?? false;
        if (!is_bool($verifySsl)) {
            $filteredVerify = filter_var($verifySsl, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $verifySsl = $filteredVerify ?? false;
        }

        $httpOptions = ['verify' => $verifySsl];
        if (!empty($downloadConfig['timeout'])) {
            $httpOptions['timeout'] = (int) $downloadConfig['timeout'];
        }

        try {
            $http = Http::withOptions($httpOptions);
            if ($username && $password) {
                $http = $http->withBasicAuth($username, $password);
            }

            $response = $http->get($url);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطا در دانلود فایل از ایزابل.'
                ], 404);
            }

            // 4️⃣ استریم فایل به مرورگر
            return new StreamedResponse(function () use ($response) {
                echo $response->body();
            }, 200, [
                'Content-Type' => 'audio/wav',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در دانلود یا استریم فایل',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
