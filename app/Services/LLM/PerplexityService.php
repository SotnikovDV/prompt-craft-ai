<?php

namespace App\Services\LLM;

use Illuminate\Support\Facades\Http;

// сервис для работы с Perplexity
class PerplexityService implements LLMServiceInterface
{
    protected string $apiKey;
    protected string $baseUrl;
    protected int $maxTokens;
    protected float $temperature;

    public function __construct()
    {
        $this->apiKey  = config('llm.perplexity.api_key');
        $this->baseUrl = config('llm.perplexity.base_url');
        $this->maxTokens = config('ai.max_tokens', 4000);
        $this->temperature = config('ai.temperature', 0.7);
    }

    public function qwery(string $systemPrompt, string $prompt, string $model): string
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type'  => 'application/json',
        ])->post("{$this->baseUrl}/chat/completions", [
            'model'    => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt
                ],
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => $this->maxTokens,
            'temperature' => $this->temperature,
        ]);

        if (! $response->successful()) {
            throw new \RuntimeException("Perplexity error: " . $response->body());
        }

        return $response->json('choices.0.message.content');
    }
}
