# Анализ потока выполнения при нажатии кнопки "Создать промпт"

**Дата анализа:** 2025-01-20  
**Проект:** PromptCraft AI

## 🎯 ПОЛНЫЙ ПОТОК ВЫПОЛНЕНИЯ

### 1. **HTML КНОПКА** (resources/views/components/prompts/prompt-form.blade.php:157)
```html
<button type="submit" data-prompt-submit
    class="btn-brand w-full py-3 px-6 text-lg flex items-center justify-center">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
    </svg>
    Создать промпт
</button>
```

### 2. **ОБРАБОТЧИК СОБЫТИЯ** (PromptFormManager.js:42-45)
```javascript
// Обработка отправки формы
if (this.form) {
    this.form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleSubmit();
    });
}
```

### 3. **МЕТОД handleSubmit** (PromptFormManager.js:99-144)
```javascript
async handleSubmit() {
    // 1. Валидация формы
    if (!this.validateForm()) {
        return;
    }

    // 2. Скрытие панели результатов при начале новой генерации
    if (window.promptResultManager) {
        console.log('Скрываем панель результата');
        window.promptResultManager.hide();
    }

    // 3. Подготовка данных
    const formData = new FormData(this.form);
    this.setLoadingState(true);

    try {
        // 4. AJAX запрос к серверу
        const response = await fetch('/generate-prompt', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        // 5. Обработка ответа
        if (!response.ok) {
            if (response.status === 429) {
                throw new Error('429: Превышен дневной лимит запросов');
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            // 6. Генерация события успеха
            this.container.dispatchEvent(new CustomEvent('prompt-generated', {
                detail: data
            }));
        } else {
            throw new Error(data.error || 'Неизвестная ошибка');
        }

    } catch (error) {
        // 7. Обработка ошибок
        console.error('Ошибка при генерации промпта:', error);
        this.container.dispatchEvent(new CustomEvent('prompt-error', {
            detail: { error: error.message }
        }));
    } finally {
        // 8. Сброс состояния загрузки
        this.setLoadingState(false);
    }
}
```

### 4. **МАРШРУТ** (routes/web.php:41)
```php
Route::post('/generate-prompt', [AIController::class, 'generatePrompt'])->name('generate-prompt');
```

### 5. **КОНТРОЛЛЕР** (AIController.php:34-224)
```php
public function generatePrompt(Request $request)
{
    // 1. Логирование запроса
    Log::info('GeneratePrompt called', [
        'request_data' => $request->all(),
        'headers' => $request->headers->all()
    ]);

    // 2. Валидация входных данных
    $request->validate([
        'prompt' => 'required|string|min:10|max:3000',
        'domain' => 'nullable|string',
        'model' => 'nullable|string',
        'style' => 'nullable|string',
        'format' => 'nullable|string',
        'parent_id' => 'nullable|integer|exists:prompt_requests,id',
    ]);

    // 3. Получение данных из формы
    $userPrompt = $request->input('prompt');
    $domain = $request->input('domain', 'Выберите область');
    $targetModel = $request->input('model', 'Выберите модель');
    $style = $request->input('style', 'Выберите стиль');
    $format = $request->input('format', 'Выберите формат');
    $parentId = $request->input('parent_id');

    // 4. Проверка лимитов пользователя
    // ... код проверки лимитов ...

    // 5. Формирование контекстного промпта
    $contextualPrompt = $this->buildContextualPrompt($userPrompt, $domain, $targetModel, $style, $format);

    // 6. Получение системного промпта
    $systemPrompt = config('ai.system_prompt');

    // 7. Запрос к LLM (Perplexity)
    $llmResponse = $this->llm->qwery($systemPrompt, $contextualPrompt, $model);

    // 8. Парсинг ответа
    $parsedResponse = $this->parseStructuredResponse($llmResponse);

    // 9. Сохранение в БД
    $promptRequest = PromptRequest::create([
        'user_id' => $userId,
        'session_id' => $sessionId,
        'parent_id' => $parentId,
        'original_request' => $originalRequest,
        'clarification' => $clarification,
        'full_request' => $userPrompt,
        'domain' => $domain,
        'model' => $targetModel,
        'style' => $style,
        'format' => $format,
        'reasoning' => $parsedResponse['reasoning'],
        'questions' => $parsedResponse['questions'],
        'generated_prompt' => $parsedResponse['prompt'],
        'execution_time' => $executionTime,
        'tokens_in' => $this->estimateTokens($systemPrompt . $contextualPrompt),
        'tokens_out' => $this->estimateTokens($llmResponse),
    ]);

    // 10. Возврат JSON ответа
    return response()->json([
        'success' => true,
        'reasoning' => $parsedResponse['reasoning'],
        'questions' => $parsedResponse['questions'],
        'generated_prompt' => $parsedResponse['prompt'],
        'parameters' => [
            'domain' => $domain,
            'model' => $targetModel,
            'style' => $style,
            'format' => $format,
        ],
        'request_id' => $promptRequest->id,
        'execution_time' => $executionTime,
        'is_clarification' => $isClarification
    ]);
}
```

### 6. **ОБРАБОТКА РЕЗУЛЬТАТА** (PromptResultManager.js)
```javascript
// Слушатель события 'prompt-generated'
container.addEventListener('prompt-generated', (event) => {
    const data = event.detail;
    this.displayResult(data);
});
```

## 📊 СХЕМА ПОТОКА

```
[Кнопка "Создать промпт"] 
    ↓ (click/submit)
[PromptFormManager.handleSubmit()]
    ↓ (AJAX POST)
[Маршрут: /generate-prompt]
    ↓
[AIController.generatePrompt()]
    ↓ (валидация, лимиты)
[LLM Service (Perplexity)]
    ↓ (ответ)
[Парсинг ответа]
    ↓
[Сохранение в БД]
    ↓
[JSON ответ]
    ↓
[PromptResultManager.displayResult()]
    ↓
[Отображение результата пользователю]
```

## 🔍 КЛЮЧЕВЫЕ КОМПОНЕНТЫ

### Frontend:
- **PromptFormManager.js** - управление формой и отправкой
- **PromptResultManager.js** - отображение результатов
- **welcome.blade.php** - инициализация компонентов

### Backend:
- **AIController.php** - основная логика обработки
- **LLMServiceInterface** - интерфейс для работы с LLM
- **PerplexityService.php** - реализация для Perplexity API
- **PromptRequest.php** - модель для сохранения запросов

### Маршруты:
- **POST /generate-prompt** - основной endpoint

## ✅ ВЫВОД

Код при нажатии кнопки "Создать промпт" работает **корректно** и использует современную архитектуру:

1. **Модульная структура** - разделение на компоненты
2. **AJAX запросы** - без перезагрузки страницы  
3. **Валидация** - на frontend и backend
4. **Обработка ошибок** - с пользовательскими сообщениями
5. **Логирование** - для отладки и мониторинга
6. **Лимиты** - защита от злоупотреблений
7. **Сохранение в БД** - для аналитики и истории

**Старая функция `generatePrompt()` в welcome.blade.php НЕ ИСПОЛЬЗУЕТСЯ** - это legacy код, который можно удалить.
