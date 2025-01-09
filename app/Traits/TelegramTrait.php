<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait TelegramTrait
{

    protected $token = "7668828231:AAFXm94vjZU2-iQ9hTVMrX_KNr0h9_d07Oo";

    private function sendQuery($query, $params)
    {
        return Http::post("https://api.telegram.org/bot{$this->token}/$query", $params);
    }

    public function sendMessage($chat_id, $message)
    {
        $this->sendQuery("sendMessage", ["chat_id" => $chat_id, "text" => $message]);
    }

}
