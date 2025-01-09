<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait TrelloTrait
{

    protected $board_id = "677e7678af3b05ca030d5372";
    protected $api_key = "4906b173b483503fd5e579b996b20bb3";
    protected $api_token = "ATTAe1177d665734d73f7a650edcf56be6674b89b09fd6d94751ede5ca5890ea3dd3B98F719D";
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
