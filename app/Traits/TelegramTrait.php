<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait TelegramTrait
{

    protected $token;

    public function __construct()
    {
        $this->token = env('TELEGRAM_TOKEN');
    }

    private function sendQuery($query, $params)
    {
        return Http::post("https://api.telegram.org/bot{$this->token}/$query", $params);
    }

    public function sendMessage($chat_id, $message)
    {
        $this->sendQuery("sendMessage", ["chat_id" => $chat_id, "text" => $message]);
    }

}
