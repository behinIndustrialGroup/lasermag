<?php

namespace Behin\Sms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Melipayamak\MelipayamakApi;
use Illuminate\Support\Facades\Http;

class SmsController2 extends Controller
{
    private $url;
    private $user;
    private $pass;
    private $org;

    public function __construct() {
        
    }
    public static function send($to, $msg)
    {
        $response = Http::withHeaders([
            'X-API-KEY' => env('SMS_TOKEN'),
        ])->post('https://iran.altfuel.ir/sms/index.php', [
            'to' => $to,
            'message' => $msg
        ]);
        Log::info($response);
        if ($response->successful()) {
            echo $response->body(); // یا log کن یا ذخیره کن
        } else {
            echo "خطا در ارسال SMS";
        }
    }

    public static function sendByTemp($to, $tempCode, array $parameter)
    {
        $curl = curl_init();
        $postFields = array(
            "mobile" => $to,
            "templateId" => $tempCode,
            "parameters" => $parameter
        );
        $postFields = json_encode($postFields);

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.sms.ir/v1/send/verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: text/plain',
                'x-api-key: '.env('SMS_IR_API_KEY')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }


}
