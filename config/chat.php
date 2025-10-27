<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Conversation History Configuration
    |--------------------------------------------------------------------------
    |
    | This value determines how many recent messages should be included
    | in the conversation history when sending requests to the AI model.
    | Limiting history helps reduce token usage and API costs.
    |
    */

    'history_limit' => env('CHAT_HISTORY_LIMIT', 20),

    /*
    |--------------------------------------------------------------------------
    | Message Validation
    |--------------------------------------------------------------------------
    |
    | These values define the validation rules for user messages.
    |
    */

    'message_max_length' => env('CHAT_MESSAGE_MAX_LENGTH', 1000),

    /*
    |--------------------------------------------------------------------------
    | Pagination Configuration
    |--------------------------------------------------------------------------
    |
    | Number of conversations to display per page in the conversation list.
    |
    */

    'conversations_per_page' => env('CHAT_CONVERSATIONS_PER_PAGE', 10),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure the rate limiting for message sending endpoints.
    |
    */

    'rate_limit' => [
        'max_attempts' => env('CHAT_RATE_LIMIT_ATTEMPTS', 20),
        'decay_minutes' => env('CHAT_RATE_LIMIT_DECAY', 1),
    ],

];

# cGFuZ29saW4=
