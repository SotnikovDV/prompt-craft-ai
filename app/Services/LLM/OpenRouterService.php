<?php

declare(strict_types=1);

namespace App\Services\LLM;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Сервис для работы с OpenRouter API
 */
class OpenRouterService implements LLMServiceInterface
{
    public function __construct(
        protected string $apiKey,
        protected string $baseUrl,
        protected int $maxTokens,
        protected float $temperature
    ) {}

    public function qwery(string $systemPrompt, string $prompt, string $model): string
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ])->post("{$this->baseUrl}/chat/completions", [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'max_tokens' => $this->maxTokens,
            'temperature' => $this->temperature,
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('OpenRouter error: '.$response->body());
        }

        return $response->json('choices.0.message.content');
    }
}
