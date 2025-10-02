<?php

declare(strict_types=1);

use App\Services\LLM\OpenRouterService;
use Illuminate\Support\Facades\Http;

uses(Tests\TestCase::class);

test('openrouter service implements llm service interface', function () {
    $service = new OpenRouterService(
        apiKey: 'test-key',
        baseUrl: 'https://openrouter.ai/api/v1',
        maxTokens: 1000,
        temperature: 0.7
    );

    expect($service)->toBeInstanceOf(\App\Services\LLM\LLMServiceInterface::class);
});

test('openrouter service sends correct request', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'choices' => [
                [
                    'message' => [
                        'content' => 'Test response',
                    ],
                ],
            ],
        ], 200),
    ]);

    $service = new OpenRouterService(
        apiKey: 'test-key',
        baseUrl: 'https://openrouter.ai/api/v1',
        maxTokens: 1000,
        temperature: 0.7
    );

    $response = $service->qwery(
        systemPrompt: 'You are a helpful assistant',
        prompt: 'Hello',
        model: 'openai/gpt-3.5-turbo'
    );

    expect($response)->toBe('Test response');

    Http::assertSent(function ($request) {
        return $request->hasHeader('Authorization', 'Bearer test-key')
            && $request->hasHeader('HTTP-Referer')
            && $request->hasHeader('X-Title')
            && $request->url() === 'https://openrouter.ai/api/v1/chat/completions'
            && $request['model'] === 'openai/gpt-3.5-turbo'
            && $request['max_tokens'] === 1000
            && $request['temperature'] === 0.7;
    });
});

test('openrouter service throws exception on failed request', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response('Error', 500),
    ]);

    $service = new OpenRouterService(
        apiKey: 'test-key',
        baseUrl: 'https://openrouter.ai/api/v1',
        maxTokens: 1000,
        temperature: 0.7
    );

    expect(fn () => $service->qwery('System', 'Hello', 'openai/gpt-4'))
        ->toThrow(RuntimeException::class);
});
