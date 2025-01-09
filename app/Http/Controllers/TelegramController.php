<?php

namespace App\Http\Controllers;

use App\Models\Telegram;
use App\Traits\TelegramTrait;
use App\Traits\TrelloTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    use TelegramTrait, TrelloTrait;

    private $chatId, $name, $message, $userChatId;
    /**
     * Handle the webhook request.
     */
    public function webhook(Request $request)
    {
        // Extract data from the request
        $data = $request->input('message');

        // Ignore messages related to adding new members to the chat
        if (isset($data['new_chat_member']) || isset($data['new_chat_members'])) {
            return response()->json(['status' => 'ignored'], 200);
        }

        if (!$this->validateRequest($data)) {
            Log::error('Invalid request data', ['request' => $request->all()]);
            return response()->json(['error' => 'Invalid request data']);
        }

        $this->userChatId = $data['from']['id'];
        $this->name = $data['from']['first_name'];
        $this->message = $data['text'] ?? null;
        $this->chatId = $data['chat']['id'];

        if ($this->message === '/start') {
            $this->handleStartCommand();
        } else {
            $this->handleUserMessage();
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Validate the incoming request data.
     */
    private function validateRequest($data): bool
    {
        return isset($data['chat']['id'], $data['from']['first_name'], $data['text']);
    }

    /**
     * Handle the "/start" command.
     */
    private function handleStartCommand(): void
    {
        if (!Telegram::query()->where('chat_id', $this->userChatId)->exists()) {
            Telegram::query()->create([
                'name' => $this->name,
                'chat_id' => $this->userChatId,
            ]);
            $this->sendMessage(
                $this->chatId,
                "Добрий день, {$this->name}\n\nНадішліть ваш email для підключення до Trello"
            );
        }
    }

    /**
     * Handle user messages.
     */
    private function handleUserMessage(): void
    {
        $user = Telegram::query()->where('chat_id', $this->userChatId)->first();

        if (!$user) {
            $this->sendMessage($this->chatId, "Будь ласка, почніть з команди /start");
            return;
        }

        if ($this->isValidEmail($this->message) && $user->email == null) {
            $this->addMemberToBoard($this->message);
            $user->update(['email' => $this->message]);
            $this->sendMessage(
                $this->chatId,
                "Додано до Trello\n\nПосилання: https://trello.com/invite/b/677e7678af3b05ca030d5372/ATTI75bc7c36843fe0b01f22bd4ab206fbf8B012AF69/test"
            );
        }
    }

    /**
     * Validate email address.
     */
    private function isValidEmail(?string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
