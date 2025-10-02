<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\LLM\LLMServiceInterface;
use App\Services\LLM\OpenRouterService;
use App\Services\LLM\PerplexityService;
use App\Services\LLM\YandexService;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class LLMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LLMServiceInterface::class, function ($app) {
            $provider = config('llm.defaultLLM');
            $maxTokens = (int) config('ai.max_tokens', 4000);
            $temperature = (float) config('ai.temperature', 0.7);

            return match ($provider) {
                'perplexity' => new PerplexityService(
                    apiKey: env('PERPLEXITY_API_KEY'),
                    baseUrl: config('llm.perplexity.base_url'),
                    maxTokens: $maxTokens,
                    temperature: $temperature
                ),
                'openrouter' => new OpenRouterService(
                    apiKey: env('OPENROUTER_API_KEY'),
                    baseUrl: config('llm.openrouter.base_url'),
                    maxTokens: $maxTokens,
                    temperature: $temperature
                ),
                'yandex' => new YandexService(
                    apiKey: env('YANDEX_LLM_API_KEY'),
                    folderId: env('YANDEX_FOLDER_ID'),
                    baseUrl: config('llm.yandex.base_url'),
                    maxTokens: $maxTokens,
                    temperature: $temperature
                ),
                default => throw new InvalidArgumentException("Неподдерживаемый LLM провайдер: {$provider}"),
            };
        });
    }
}
