<?php

namespace BaleBot\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use BaleBot\Models\BaleUser;
use Mkhodroo\AltfuelTicket\Controllers\LangflowController;

class BotController extends Controller
{
    public static function send($msg, $userId = 681208098){
        $telegram = new TelegramController(env('BALE_BOT_TOKEN'));
        $telegram->sendMessage([
            'chat_id' => $userId,
            'text' => $msg
        ]);
    }

    public function chat()
    {
        Log::info("Receive Message");
        $content = file_get_contents('php://input');
        $update = json_decode($content, true);
        if (isset($update['callback_query'])) {
            return $this->handleCallback($update);
        }

        $telegram = new TelegramController(env('BALE_BOT_TOKEN'));

        $message = $update['message'] ?? null;
        $chat_id = $message['chat']['id'] ?? null;
        $text = $message['text'] ?? null;
        $contact = $message['contact'] ?? null;

        if (!$chat_id) return;

        $user = BaleUser::firstOrCreate(['chat_id' => $chat_id]);

        // اگر نام کاربر وجود ندارد
        if (!$user->name) {
            // اگر متن پیام حاوی نام باشد
            if ($text !== '/start') {
                $user->name = $text;
                $user->save();

                $telegram->sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "مرسی {$text} 🙏\nحالا لطفاً شماره تماس خود را ارسال کن:",
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [['text' => '📞 ارسال شماره من', 'request_contact' => true]]
                        ],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ])
                ]);
                return;
            }

            // اگر هنوز نامی وارد نکرده، بپرس
            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => "سلام! من پاکو هستم 🤖\nدستیار هوش مصنوعی شما در بله.\nبرای شروع لطفاً نام خود را وارد کن."
            ]);
            return;
        }

        // اگر شماره تماس کاربر وجود ندارد
        if (!$user->phone) {
            // اگه کاربر شماره فرستاده
            if ($contact && isset($contact['phone_number'])) {
                $user->phone = $contact['phone_number'];
                $user->save();
            } elseif (preg_match('/^09\d{9}$/', $text)) {
                $user->phone = $text;
                $user->save();
            } else {
                $telegram->sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "❗ لطفاً شماره تلفن معتبر وارد کن یا با دکمه زیر ارسال کن:",
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [['text' => '📞 ارسال شماره من', 'request_contact' => true]]
                        ],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ])
                ]);
                return;
            }

            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => "✅ اطلاعاتت ثبت شد. حالا سوالت رو بپرس ✨"
            ]);
            return;
        }

        // اگه نام و شماره کامل بود، بفرست به Langflow
        if ($text && $text !== '/start') {
            $botResponse = LangflowController::run($text, $chat_id);

            $messageId = DB::table('bale_messages')->insertGetId([
                'user_id' => $chat_id,
                'user_message' => $text,
                'bot_response' => $botResponse,
                'feedback' => 'none',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => '👍', 'callback_data' => "like:$messageId"],
                        ['text' => '👎', 'callback_data' => "dislike:$messageId"],
                    ]
                ]
            ];

            $response = $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $botResponse ,
                'reply_markup' => json_encode($keyboard)
            ]);

            $responseData = json_decode($response, true);
            $msgTelegramId = $responseData['result']['message_id'] ?? null;

            DB::table('bale_messages')->where('id', $messageId)->update([
                'telegram_message_id' => $msgTelegramId
            ]);
            return;
        }

        // فقط /start زده شده؟ معرفی کن و تمام
        if ($text === '/start') {
            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => "سلام {$user->name} ! من پاکو هستم 🤖\nدستیار هوش مصنوعی شما در بله.\nچه کمکی از دستم بر میاد"
            ]);
            return;
        }

    }

    public function handleCallback()
    {
        Log::info("Receive Callback");
        $content = file_get_contents("php://input");
        $update = json_decode($content, true);

        if (isset($update['callback_query'])) {
            Log::info($update);
            $callbackData = $update['callback_query']['data'];
            $chatId = $update['callback_query']['message']['chat']['id'];
            $msgTelegramId = $update['callback_query']['message']['message_id'];

            list($action, $msgId) = explode(':', $callbackData);

            DB::table('bale_messages')->where('id', $msgId)->update([
                'feedback' => $action,
                'updated_at' => now()
            ]);

            $telegram = new TelegramController(config('bale_bot_config.TOKEN'));

            // حذف دکمه‌ها
            $telegram->editMessageReplyMarkup([
                'chat_id' => $chatId,
                'message_id' => $msgTelegramId,
                'reply_markup' => json_encode(['inline_keyboard' => []])
            ]);

            // ارسال پیام تشکر
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'ممنون بابت بازخورد شما 🙏'
            ]);
        }
    }


}
