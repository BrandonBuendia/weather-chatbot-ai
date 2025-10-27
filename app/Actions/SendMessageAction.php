<?php

declare(strict_types=1);

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

        $conversationHistory = $this->getConversationHistory($conversation);

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

    private function getConversationHistory(Conversation $conversation): array
    {
        $historyLimit = config('chat.history_limit', 20);

        return $conversation->messages()
            ->orderBy('created_at', 'desc')
            ->limit($historyLimit)
            ->get()
            ->reverse()
            ->values()
            ->map(fn($msg) => [
                'role' => $msg->role->value,
                'content' => $msg->content,
            ])
            ->toArray();
    }
}

# cGFuZ29saW4=
