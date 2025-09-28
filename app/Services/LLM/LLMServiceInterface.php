<?php

namespace App\Services\LLM;

// интерфейс для сервиса LLM
interface LLMServiceInterface
{
    public function qwery(string $systemPrompt, string $prompt, string $model): string;
}
