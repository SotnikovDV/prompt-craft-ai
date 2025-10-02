<?php

return array (
  'defaultLLM' => 'perplexity',
  'perplexity' => 
  array (
    'base_url' => 'https://api.perplexity.ai',
    'defaultModel' => 'sonar',
    'models' => 
    array (
      'sonar' => 'sonar',
      'sonarpro' => 'sonar-pro',
    ),
  ),
  'openrouter' => 
  array (
    'base_url' => 'https://openrouter.ai/api/v1',
    'defaultModel' => 'claude-3.5-sonnet',
    'models' => 
    array (
      'claude-3.5-sonnet' => 'anthropic/claude-3.5-sonnet',
      'gemma-3-27b-it-free' => 'google/gemma-3-27b-it:free',
      'llama-3.3-70b-instruct-free' => 'meta-llama/llama-3.3-70b-instruct:free',
      'mistral-7b-instruct-free' => 'mistralai/mistral-7b-instruct:free',
      'mistral-small-3.2-24b-instruct-free' => 'mistralai/mistral-small-3.2-24b-instruct:free',
      'mistral-small-3.1-24b-instruct-free' => 'mistralai/mistral-small-3.1-24b-instruct:free',
      'deepseek-r1-free' => 'deepseek/deepseek-r1:free',
      'deepseek-chat-v3-0324-free' => 'deepseek/deepseek-chat-v3-0324:free',
      'deepseek-r1-0528-qwen3-8b-free' => 'deepseek/deepseek-r1-0528-qwen3-8b:free',
      'deepseek-r1-distill-llama-70b-free' => 'deepseek/deepseek-r1-distill-llama-70b:free',
      'qwen3-coder-free' => 'qwen/qwen3-coder:free',
      'qwen2.5-vl-72b-instruct-free' => 'qwen/qwen2.5-vl-72b-instruct:free',
      'qwen3-14b-free' => 'qwen/qwen3-14b:free',
      'qwen-2.5-coder-32b-instruct-free' => 'qwen/qwen-2.5-coder-32b-instruct:free',
    ),
  ),
  'yandex' => 
  array (
    'base_url' => 'https://llm.api.cloud.yandex.net',
    'defaultModel' => 'yandexgpt-lite',
    'models' => 
    array (
      'yandexgpt-lite' => 'yandexgpt-lite/latest',
      'yandexgpt-pro' => 'yandexgpt-pro/latest',
    ),
  ),
);
