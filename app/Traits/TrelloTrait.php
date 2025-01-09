<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait TrelloTrait
{
    protected $board_id, $api_key, $api_token;
    public function __construct()
    {
        $this->board_id = env('TRELLO_BOARD_ID');
        $this->api_key = env('TRELLO_API_KEY');
        $this->api_token = env('TRELLO_API_TOKEN');
    }

    public function addMemberToBoard($email)
    {
        Http::put("https://api.trello.com/1/boards/$this->board_id/members", [
            'email' => $email,
            'key' => $this->api_key,
            'token' => $this->api_token,
        ]);
    }

    public function setWebhook()
    {
        $query = array(
            'callbackURL' => 'https://b13b-188-163-54-2.ngrok-free.app/api/trello/webhook',
            'idModel' => $this->board_id,
            'key' => $this->api_key,
            'token' => $this->api_token
        );

        Http::post('https://api.trello.com/1/webhooks/', $query);
    }

}
