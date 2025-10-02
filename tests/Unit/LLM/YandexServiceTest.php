<?php

declare(strict_types=1);

use App\Services\LLM\YandexService;
use Illuminate\Support\Facades\Http;

uses(Tests\TestCase::class);

test('yandex service implements llm service interface', function () {
    $service = new YandexService(
        apiKey: 'test-key',
        folderId: 'test-folder',
        baseUrl: 'https://llm.api.cloud.yandex.net',
        maxTokens: 1000,
        temperature: 0.6
    );

    expect($service)->toBeInstanceOf(\App\Services\LLM\LLMServiceInterface::class);
});

test('yandex service sends correct request with system prompt', function () {
    Http::fake([
        'llm.api.cloud.yandex.net/*' => Http::response([
            'result' => [
                'alternatives' => [
                    [
                        'message' => [
                            'text' => 'Test response from Yandex',
                        ],
                    ],
                ],
            ],
        ], 200),
    ]);

    $service = new YandexService(
        apiKey: 'test-key',
        folderId: 'test-folder-id',
        baseUrl: 'https://llm.api.cloud.yandex.net',
        maxTokens: 1000,
        temperature: 0.6
    );

    $response = $service->qwery(
        systemPrompt: 'Ты полезный ассистент',
        prompt: 'Привет',
        model: 'yandexgpt-lite/latest'
    );

    expect($response)->toBe('Test response from Yandex');

    Http::assertSent(function ($request) {
        return $request->hasHeader('Authorization', 'Api-Key test-key')
            && $request->hasHeader('Content-Type', 'application/json')
            && str_contains($request->url(), 'foundationModels/v1/completion')
            && $request['modelUri'] === 'gpt://test-folder-id/yandexgpt-lite/latest'
            && $request['completionOptions']['maxTokens'] === 1000
            && $request['completionOptions']['temperature'] === 0.6
            && $request['completionOptions']['stream'] === false
            && count($request['messages']) === 2
            && $request['messages'][0]['role'] === 'system'
            && $request['messages'][0]['text'] === 'Ты полезный ассистент'
            && $request['messages'][1]['role'] === 'user'
            && $request['messages'][1]['text'] === 'Привет';
    });
});

test('yandex service sends correct request without system prompt', function () {
    Http::fake([
        'llm.api.cloud.yandex.net/*' => Http::response([
            'result' => [
                'alternatives' => [
                    [
                        'message' => [
                            'text' => 'Test response',
                        ],
                    ],
                ],
            ],
        ], 200),
    ]);

    $service = new YandexService(
        apiKey: 'test-key',
        folderId: 'test-folder-id',
        baseUrl: 'https://llm.api.cloud.yandex.net',
        maxTokens: 2000,
        temperature: 0.7
    );

    $response = $service->qwery(
        systemPrompt: '',
        prompt: 'Привет',
        model: 'yandexgpt-pro/latest'
    );

    expect($response)->toBe('Test response');

    Http::assertSent(function ($request) {
        return $request['modelUri'] === 'gpt://test-folder-id/yandexgpt-pro/latest'
            && count($request['messages']) === 1
            && $request['messages'][0]['role'] === 'user'
            && $request['messages'][0]['text'] === 'Привет';
    });
});

test('yandex service throws exception on failed request', function () {
    Http::fake([
        'llm.api.cloud.yandex.net/*' => Http::response('Error', 500),
    ]);

    $service = new YandexService(
        apiKey: 'test-key',
        folderId: 'test-folder',
        baseUrl: 'https://llm.api.cloud.yandex.net',
        maxTokens: 1000,
        temperature: 0.6
    );

    expect(fn () => $service->qwery('System', 'Hello', 'yandexgpt-lite/latest'))
        ->toThrow(RuntimeException::class);
});

test('yandex service throws exception on empty response', function () {
    Http::fake([
        'llm.api.cloud.yandex.net/*' => Http::response([
            'result' => [
                'alternatives' => [],
            ],
        ], 200),
    ]);

    $service = new YandexService(
        apiKey: 'test-key',
        folderId: 'test-folder',
        baseUrl: 'https://llm.api.cloud.yandex.net',
        maxTokens: 1000,
        temperature: 0.6
    );

    expect(fn () => $service->qwery('System', 'Hello', 'yandexgpt-pro/latest'))
        ->toThrow(RuntimeException::class, 'Yandex LLM returned empty response');
});
