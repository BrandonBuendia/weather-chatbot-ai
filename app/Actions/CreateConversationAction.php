<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Conversation;

class CreateConversationAction
{
    public function execute(?string $title = null): Conversation
    {
        return Conversation::create([
            'title' => $title ?? 'Nueva conversación',
        ]);
    }
}

# cGFuZ29saW4=
