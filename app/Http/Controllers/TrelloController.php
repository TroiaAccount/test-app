<?php

namespace App\Http\Controllers;

use App\Traits\TelegramTrait;
use Illuminate\Http\Request;

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

        if($user != null && $card_name != null && $before != null && $after != null) {
            $message = "Користувач $user перетягнув '$card_name' з '$before' до '$after'";
            $chat_id = "-4799335568";

            $this->sendMessage($chat_id, $message);
        }
    }

}
