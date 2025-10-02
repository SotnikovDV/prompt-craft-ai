# Интеграция OpenRouter

## Описание

OpenRouter - это универсальный провайдер LLM, который предоставляет доступ к различным языковым моделям через единый API. Поддерживаются модели от OpenAI, Anthropic, Meta, Mistral и других.

## Настройка

### 1. Получение API ключа

Зарегистрируйтесь на [OpenRouter](https://openrouter.ai/) и получите API ключ в разделе "Keys".

### 2. Конфигурация .env

Добавьте в ваш `.env` файл следующие переменные:

```env
# Конфигурация LLM провайдера
LLM_PROVIDER=openrouter  # или 'perplexity' для использования Perplexity

# Конфигурация OpenRouter
OPENROUTER_API_KEY=ваш-api-ключ
OPENROUTER_BASE_URL=https://openrouter.ai/api/v1
```

### 3. Доступные модели

OpenRouter предоставляет доступ к множеству моделей. Конфигурация находится в `config/llm.php`:

```php
'openrouter' => [
    'models' => [
        'gpt-4' => 'openai/gpt-4',
        'gpt-4-turbo' => 'openai/gpt-4-turbo',
        'gpt-3.5-turbo' => 'openai/gpt-3.5-turbo',
        'claude-3-opus' => 'anthropic/claude-3-opus',
        'claude-3-sonnet' => 'anthropic/claude-3-sonnet',
        'claude-3-haiku' => 'anthropic/claude-3-haiku',
        'llama-3-70b' => 'meta-llama/llama-3-70b-instruct',
        'mistral-large' => 'mistralai/mistral-large',
    ],
],
```

Вы можете добавить дополнительные модели в конфигурацию. Полный список доступных моделей можно найти на [OpenRouter Models](https://openrouter.ai/models).

## Использование

### Переключение между провайдерами

Чтобы переключиться между Perplexity и OpenRouter, измените переменную `LLM_PROVIDER` в `.env`:

```env
# Для использования Perplexity
LLM_PROVIDER=perplexity

# Для использования OpenRouter
LLM_PROVIDER=openrouter
```

После изменения конфигурации выполните:

```bash
php artisan config:cache
```

### Программное использование

В вашем коде сервис LLM доступен через интерфейс `LLMServiceInterface`:

```php
use App\Services\LLM\LLMServiceInterface;

class MyController extends Controller
{
    public function __construct(protected LLMServiceInterface $llm)
    {
    }

    public function generate(Request $request)
    {
        $response = $this->llm->qwery(
            systemPrompt: 'Ты полезный ассистент',
            prompt: 'Привет, мир!',
            model: $this->getModel('gpt-4')
        );
        
        return response()->json(['response' => $response]);
    }
    
    protected function getModel(string $modelKey): string
    {
        $provider = config('llm.defaultLLM');
        return config("llm.{$provider}.models.{$modelKey}");
    }
}
```

## Стоимость

OpenRouter работает по модели pay-as-you-go (оплата по факту использования). Каждая модель имеет свою стоимость за токен. Проверьте текущие цены на [OpenRouter Pricing](https://openrouter.ai/models).

## Ограничения и квоты

- Лимиты запросов зависят от выбранной модели
- Рекомендуется настроить бюджетные лимиты в панели управления OpenRouter
- Некоторые модели могут требовать предварительного пополнения баланса

## Тестирование

Для тестирования интеграции выполните:

```bash
php artisan test --filter=LLM
```

## Поддержка

- OpenRouter Discord: https://discord.gg/fVyRaUDgxW
- Документация API: https://openrouter.ai/docs
- GitHub Issues: создайте issue в репозитории проекта

