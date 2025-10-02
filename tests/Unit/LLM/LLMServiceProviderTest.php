<?php

declare(strict_types=1);

use App\Services\LLM\LLMServiceInterface;
use App\Services\LLM\OpenRouterService;
use App\Services\LLM\PerplexityService;
use App\Services\LLM\YandexService;

uses(Tests\TestCase::class);

test('provider creates perplexity service when configured', function () {
    config(['llm.defaultLLM' => 'perplexity']);
    config(['llm.perplexity.api_key' => 'test-key']);
    config(['llm.perplexity.base_url' => 'https://api.perplexity.ai']);

    $service = app(LLMServiceInterface::class);

    expect($service)->toBeInstanceOf(PerplexityService::class);
});

test('provider creates openrouter service when configured', function () {
    config(['llm.defaultLLM' => 'openrouter']);
    config(['llm.openrouter.api_key' => 'test-key']);
    config(['llm.openrouter.base_url' => 'https://openrouter.ai/api/v1']);

    // Пересоздаём singleton для нового конфига
    app()->forgetInstance(LLMServiceInterface::class);

    $service = app(LLMServiceInterface::class);

    expect($service)->toBeInstanceOf(OpenRouterService::class);
});

test('provider creates yandex service when configured', function () {
    config(['llm.defaultLLM' => 'yandex']);
    config(['llm.yandex.api_key' => 'test-key']);
    config(['llm.yandex.folder_id' => 'test-folder']);
    config(['llm.yandex.base_url' => 'https://llm.api.cloud.yandex.net']);

    // Пересоздаём singleton для нового конфига
    app()->forgetInstance(LLMServiceInterface::class);

    $service = app(LLMServiceInterface::class);

    expect($service)->toBeInstanceOf(YandexService::class);
});

test('provider throws exception for unsupported provider', function () {
    config(['llm.defaultLLM' => 'unsupported']);

    // Пересоздаём singleton для нового конфига
    app()->forgetInstance(LLMServiceInterface::class);

    expect(fn () => app(LLMServiceInterface::class))
        ->toThrow(InvalidArgumentException::class);
});
