<?php

namespace App\Actions;

use App\Enums\MessageRole;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\OpenAIService;

class SendMessageAction
{
    public function __construct(
        private readonly OpenAIService $openAIService
    ) {
    }

    public function execute(Conversation $conversation, string $content): Message
    {
        $userMessage = Message::create([
            'conversation_id' => $conversation->id,
            'role' => MessageRole::USER,
            'content' => $content,
        ]);

        $conversationHistory = $conversation->messages()
            ->orderBy('created_at')
            ->get()
            ->map(fn($msg) => [
                'role' => $msg->role->value,
                'content' => $msg->content,
            ])
            ->toArray();

        $assistantResponse = $this->openAIService->chat(
            $conversationHistory,
            $conversation->id
        );

        $assistantMessage = Message::create([
            'conversation_id' => $conversation->id,
            'role' => MessageRole::ASSISTANT,
            'content' => $assistantResponse,
        ]);

        return $assistantMessage;
    }
}

# cGFuZ29saW4=
