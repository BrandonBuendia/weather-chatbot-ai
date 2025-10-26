<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key
    |--------------------------------------------------------------------------
    |
    | Your OpenAI API Key. Get it from: https://platform.openai.com/api-keys
    | Or use OpenRouter API Key: https://openrouter.ai/keys
    |
    */
    'api_key' => env('OPENAI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | For OpenAI: https://api.openai.com/v1 (default)
    | For OpenRouter: https://openrouter.ai/api/v1
    |
    */
    'base_url' => env('OPENAI_API_BASE', 'https://api.openai.com/v1'),

    /*
    |--------------------------------------------------------------------------
    | Organization ID (Optional)
    |--------------------------------------------------------------------------
    |
    | Your OpenAI organization ID (optional)
    |
    */
    'organization' => env('OPENAI_ORGANIZATION'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | Timeout in seconds for API requests
    |
    */
    'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 30),
];

# cGFuZ29saW4=
