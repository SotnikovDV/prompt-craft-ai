# Устранение неполадок административной панели

## Проблема: TypeError при сохранении настроек

### Описание ошибки
```
TypeError: Cannot assign array to property `App\Services\LLM\PerplexityService::$apiKey` of type string
```

### Причина
Ошибка возникает при сохранении настроек в административной панели, когда значения конфигурации (например, `api_key`, `base_url`) сохраняются как массивы вместо строк. Это происходит из-за неправильного использования `array_merge_recursive()` в методе `updateConfigFile()`.

### Решение

#### 1. Исправление метода слияния массивов
Заменили `array_merge_recursive()` на кастомный метод `arrayMergeDeep()`, который не создает массивы из строк:

```php
private function arrayMergeDeep(array $array1, array $array2): array
{
    $result = $array1;
    
    foreach ($array2 as $key => $value) {
        if (isset($result[$key]) && is_array($result[$key]) && is_array($value)) {
            $result[$key] = $this->arrayMergeDeep($result[$key], $value);
        } else {
            $result[$key] = $value;
        }
    }
    
    return $result;
}
```

#### 2. Дополнительная валидация данных
Добавили метод `validateConfigData()` для проверки типов данных перед сохранением:

```php
private function validateConfigData(array $aiData, array $llmData, array $appData): void
{
    // Проверяем, что строковые значения не являются массивами
    if (isset($aiData['system_prompt']) && is_array($aiData['system_prompt'])) {
        throw new \Exception('Системный промпт не может быть массивом');
    }
    
    if (isset($llmData['perplexity']['api_key']) && is_array($llmData['perplexity']['api_key'])) {
        throw new \Exception('API ключ не может быть массивом');
    }
    
    // ... другие проверки
}
```

#### 3. Улучшенная валидация форм
Добавили детальные сообщения об ошибках валидации:

```php
$request->validate([
    'llm.perplexity.api_key' => 'required|string|min:10',
    'llm.perplexity.base_url' => 'required|url',
    // ... другие правила
], [
    'llm.perplexity.api_key.required' => 'API ключ Perplexity обязателен',
    'llm.perplexity.api_key.string' => 'API ключ Perplexity должен быть текстом',
    'llm.perplexity.api_key.min' => 'API ключ Perplexity должен содержать не менее 10 символов',
    // ... другие сообщения
]);
```

### Восстановление поврежденных конфигурационных файлов

#### config/llm.php
```php
<?php

return [
    'perplexity' => [
        'api_key' => env('PERPLEXITY_API_KEY', 'your-api-key'),
        'base_url' => env('PERPLEXITY_BASE_URL', 'https://api.perplexity.ai'),
        'models' => [
            'sonar' => 'sonar',
            'sonarpro' => 'sonar-pro',
        ],
    ],
];
```

#### config/ai.php
```php
<?php

return [
    'system_prompt' => 'Ваш системный промпт...',
    'max_tokens' => 6000,
    'temperature' => 0.5,
    'daily_limit_guest' => 3,
    'daily_limit_user' => null,
    'cleanup_days_guest' => 3,
];
```

### Команды для восстановления

1. **Очистка кэша конфигурации:**
   ```bash
   php artisan config:clear
   ```

2. **Проверка конфигурации:**
   ```bash
   php artisan tinker
   ```
   ```php
   // Проверяем типы данных
   config('llm.perplexity.api_key'); // должен быть string
   config('ai.max_tokens'); // должен быть numeric
   ```

3. **Тестирование сервиса:**
   ```php
   // В tinker
   $service = app(\App\Services\LLM\PerplexityService::class);
   // Должно создаться без ошибок
   ```

### Профилактика

1. **Всегда используйте правильные типы данных** в конфигурационных файлах
2. **Не используйте `array_merge_recursive()`** для слияния конфигураций
3. **Добавляйте валидацию типов** перед сохранением конфигурации
4. **Тестируйте изменения** в конфигурации после сохранения

### Мониторинг

- Проверяйте логи Laravel на наличие ошибок: `storage/logs/laravel.log`
- Используйте `php artisan config:cache` для кэширования конфигурации в продакшене
- Регулярно проверяйте целостность конфигурационных файлов

### Контакты

При возникновении проблем обращайтесь к разработчику или создайте issue в репозитории проекта.
