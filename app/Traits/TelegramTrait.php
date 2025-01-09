<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait TelegramTrait
{


    private function sendQuery($query, $params)
    {
        $token = env('TELEGRAM_TOKEN');
        return Http::post("https://api.telegram.org/bot{$token}/$query", $params);
    }

    public function sendMessage($chat_id, $message)
    {
        $this->sendQuery("sendMessage", ["chat_id" => $chat_id, "text" => $message]);
    }

}
