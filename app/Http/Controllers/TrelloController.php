<?php

namespace App\Http\Controllers;

use App\Traits\TelegramTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TrelloController extends Controller
{
    use TelegramTrait;
    public function webhook(Request $request)
    {
        $action = $request->all()['action'] ?? null;
        $display = $action['display'] ?? null;
        $card_name = $display['entities']['card']['text'] ?? null;
        $before = $display['entities']['listBefore']['text'] ?? null;
        $after = $display['entities']['listAfter']['text'] ?? null;
        $user = $display['entities']['memberCreator']['text'] ?? null;
        $uniqueKey = md5($action['date']);

        // Проверяем, обрабатывали ли мы это событие ранее
        if (Cache::has($uniqueKey)) {
            return response()->json(['status' => 'duplicate']);
        }

        Cache::put($uniqueKey, true, now()->addMinutes(5));

        if($user != null && $card_name != null && $before != null && $after != null) {
            $message = "Користувач $user перетягнув '$card_name' з '$before' до '$after'";
            $chat_id = "-4676508435";

            $this->sendMessage($chat_id, $message);
        }

    }

}
