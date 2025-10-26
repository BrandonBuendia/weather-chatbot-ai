<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use OpenAI;
use OpenAI\Contracts\ClientContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Override OpenAI client to support OpenRouter headers
        $this->app->singleton(ClientContract::class, function () {
            $apiKey = config('openai.api_key');
            $baseUri = env('OPENAI_BASE_URI');

            $headers = [];

            // Add OpenRouter-specific headers if using OpenRouter
            if (str_contains($baseUri ?? '', 'openrouter.ai')) {
                $headers['HTTP-Referer'] = config('app.url', 'http://localhost');
                $headers['X-Title'] = config('app.name', 'Weather Chatbot AI');
            }

            $client = OpenAI::factory()
                ->withApiKey($apiKey)
                ->withHttpHeader('HTTP-Referer', $headers['HTTP-Referer'] ?? config('app.url'))
                ->withHttpHeader('X-Title', $headers['X-Title'] ?? config('app.name'))
                ->withHttpClient(new \GuzzleHttp\Client([
                    'timeout' => config('openai.request_timeout', 30)
                ]));

            if (config('openai.organization')) {
                $client->withOrganization(config('openai.organization'));
            }

            if ($baseUri) {
                $client->withBaseUri($baseUri);
            }

            return $client->make();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}

# cGFuZ29saW4=
