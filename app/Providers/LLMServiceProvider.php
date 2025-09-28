<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LLM\LLMServiceInterface;
use App\Services\LLM\PerplexityService;

class LLMServiceProvider extends ServiceProvider
{
    public function register()
    {
        // сейчас используем Perplexity
        $this->app->singleton(LLMServiceInterface::class, function () {
            return new PerplexityService();
        });
    }
}
