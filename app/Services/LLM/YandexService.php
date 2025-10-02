<?php

declare(strict_types=1);

namespace App\Services\LLM;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use Illuminate\Support\Facades\Log;

/**
 * Сервис для работы с Yandex LLM API
 */
class YandexService implements LLMServiceInterface
{
    public function __construct(
        protected string $apiKey,
        protected string $folderId,
        protected string $baseUrl,
        protected int $maxTokens,
        protected float $temperature
    ) {}

    public function qwery(string $systemPrompt, string $prompt, string $model): string
    {
        // Формируем modelUri в формате Yandex
        $modelUri = "gpt://{$this->folderId}/{$model}";

        // Формируем массив сообщений
        $messages = [];
        if ($systemPrompt) {
            $messages[] = [
                'role' => 'system',
                'text' => $systemPrompt,
            ];
        }
        $messages[] = [
            'role' => 'user',
            'text' => $prompt,
        ];

        Log::info('YandexService called', [
            'modelUri' => $modelUri,
            'uri' => $this->baseUrl . '/foundationModels/v1/completion',
            'prompt' => $prompt,
        ]);

        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Authorization' => "Api-Key {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/foundationModels/v1/completion", [
                'modelUri' => $modelUri,
                'completionOptions' => [
                    'temperature' => $this->temperature,
                    'maxTokens' => $this->maxTokens,
                    'stream' => false,
                ],
                'messages' => $messages,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Yandex LLM error: '.$response->body());
        }

        $data = $response->json();
        $text = $data['result']['alternatives'][0]['message']['text'] ?? null;

        if (! $text) {
            throw new RuntimeException('Yandex LLM returned empty response');
        }

        return $text;
    }
}
