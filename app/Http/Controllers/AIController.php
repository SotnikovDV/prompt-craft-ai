<?php

namespace App\Http\Controllers;

use App\Models\PromptRequest;
use App\Services\LLM\LLMServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AIController extends Controller
{
    protected LLMServiceInterface $llm;

    public function __construct(LLMServiceInterface $llm)
    {
        $this->llm = $llm;
    }

    /**
     * Получить модель для текущего провайдера
     */
    protected function getModel(string $modelKey): string
    {
        $provider = config('llm.defaultLLM');
        $models = config("llm.{$provider}.models", []);

        if (! isset($models[$modelKey])) {
            throw new \InvalidArgumentException("Модель '{$modelKey}' не найдена для провайдера '{$provider}'");
        }

        return $models[$modelKey];
    }

    public function ask(Request $request)
    {
        $prompt = $request->input('prompt');
        $systemPrompt = config('ai.system_prompt');

        // Используем модель из запроса или модель по умолчанию для текущего провайдера
        $provider = config('llm.defaultLLM');
        $defaultModelKey = config("llm.{$provider}.defaultModel");
        $modelKey = $request->input('model', $defaultModelKey);

        $model = $this->getModel($modelKey);

        $answer = $this->llm->qwery($systemPrompt, $prompt, $model);

        return response()->json(['answer' => $answer]);
    }

    /**
     * Генерация промпта для главной страницы (с поддержкой уточнений через parent_id)
     */
    public function generatePromptForWelcomePage(Request $request)
    {
        Log::info('GeneratePromptForWelcomePage called', [
            'request_data' => $request->all(),
        ]);

        // Валидация входных данных
        $request->validate([
            'prompt' => 'required|string|min:10|max:3000',
            'domain' => 'nullable|string',
            'model' => 'nullable|string',
            'style' => 'nullable|string',
            'format' => 'nullable|string',
            'parent_id' => 'nullable|integer|exists:prompt_requests,id', // для уточнений
        ]);

        // Получаем данные из формы
        $userPrompt = $request->input('prompt');
        $domain = $request->input('domain', 'Выберите область');
        $targetModel = $request->input('model', 'Выберите модель');
        $style = $request->input('style', 'Выберите стиль');
        $format = $request->input('format', 'Выберите формат');
        $parentId = $request->input('parent_id');

        // Нормализуем значения по умолчанию
        if ($domain === 'Выберите область') {
            $domain = null;
        }
        if ($targetModel === 'Выберите модель') {
            $targetModel = null;
        }
        if ($style === 'Выберите стиль') {
            $style = null;
        }
        if ($format === 'Выберите формат') {
            $format = null;
        }

        // Определяем пользователя и сессию
        $userId = Auth::id();
        $sessionId = session()->getId(); // Используем сессию браузера

        // Проверяем лимиты
        $limitCheck = $this->checkLimits($userId, $sessionId);
        if ($limitCheck !== true) {
            return $limitCheck; // Возвращаем ответ с ошибкой лимита
        }

        // Определяем, является ли это уточнением
        $isClarification = ! empty($parentId);
        $originalRequest = $userPrompt;
        $clarification = null;

        if ($isClarification) {
            // Для уточнений извлекаем оригинальный запрос и уточнение
            $parentRequest = PromptRequest::find($parentId);
            if ($parentRequest) {
                $originalRequest = $parentRequest->original_request;
                // Извлекаем уточнение из полного запроса
                if (str_contains($userPrompt, 'Дополнительные уточнения:')) {
                    $parts = explode('Дополнительные уточнения:', $userPrompt, 2);
                    $clarification = trim($parts[1] ?? '');
                }
            }
        }

        // Вызываем базовую функцию генерации
        return $this->processPromptGeneration(
            $userPrompt,
            $domain,
            $targetModel,
            $style,
            $format,
            $userId,
            $sessionId,
            $parentId,
            $originalRequest,
            $clarification,
            $isClarification,
            false // не является продолжением чата
        );
    }

    /**
     * Генерация промпта для чата (с поддержкой продолжения через session_id)
     */
    public function generatePromptForChat(Request $request)
    {
        Log::info('GeneratePromptForChat called', [
            'request_data' => $request->all(),
        ]);

        // Валидация входных данных
        $request->validate([
            'prompt' => 'required|string|min:10|max:3000',
            'domain' => 'nullable|string',
            'model' => 'nullable|string',
            'style' => 'nullable|string',
            'format' => 'nullable|string',
            'session_id' => 'nullable|string', // для чата - может быть строкой (UUID)
        ]);

        // Получаем данные из формы
        $userPrompt = $request->input('prompt');
        $domain = $request->input('domain', 'Выберите область');
        $targetModel = $request->input('model', 'Выберите модель');
        $style = $request->input('style', 'Выберите стиль');
        $format = $request->input('format', 'Выберите формат');
        $chatSessionId = $request->input('session_id'); // ID сессии чата

        // Нормализуем значения по умолчанию
        if ($domain === 'Выберите область') {
            $domain = null;
        }
        if ($targetModel === 'Выберите модель') {
            $targetModel = null;
        }
        if ($style === 'Профессиональный') {
            $style = null;
        }
        if ($format === 'Текст') {
            $format = null;
        }

        // Определяем пользователя и сессию
        $userId = Auth::id();

        // Если передан session_id из чата, используем его, иначе создаем новую сессию
        if ($chatSessionId) {
            $sessionId = $chatSessionId;
        } else {
            // Для новых чатов генерируем уникальный session_id
            $sessionId = Str::random(40);
        }

        // Проверяем лимиты
        $limitCheck = $this->checkLimits($userId, $sessionId);
        if ($limitCheck !== true) {
            return $limitCheck; // Возвращаем ответ с ошибкой лимита
        }

        // Определяем, является ли это продолжением чата
        $isChatContinuation = ! empty($chatSessionId);
        $originalRequest = $userPrompt;

        if ($isChatContinuation) {
            // Для продолжения чата получаем последний запрос пользователя из этой сессии
            $lastRequest = PromptRequest::where('session_id', $chatSessionId)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastRequest) {
                // Объединяем последний запрос пользователя с новым сообщением
                $originalRequest = $lastRequest->original_request;
                $userPrompt = $lastRequest->original_request.' '.$userPrompt;
                Log::info('Chat continuation mode', [
                    'session_id' => $chatSessionId,
                    'last_request' => $lastRequest->original_request,
                    'new_message' => $request->input('prompt'),
                    'combined_prompt' => $userPrompt,
                ]);
            }
        }

        // Вызываем базовую функцию генерации
        return $this->processPromptGeneration(
            $userPrompt,
            $domain,
            $targetModel,
            $style,
            $format,
            $userId,
            $sessionId,
            null, // parentId
            $originalRequest,
            null, // clarification
            false, // не является уточнением
            $isChatContinuation
        );
    }

    /**
     * Базовая функция обработки генерации промпта
     */
    private function processPromptGeneration(
        $userPrompt,
        $domain,
        $targetModel,
        $style,
        $format,
        $userId,
        $sessionId,
        $parentId,
        $originalRequest,
        $clarification,
        $isClarification,
        $isChatContinuation
    ) {
        // Формируем контекстный промпт с дополнительными параметрами
        $contextualPrompt = $this->buildContextualPrompt($userPrompt, $domain, $targetModel, $style, $format);

        // Получаем системный промпт
        $systemPrompt = config('ai.system_prompt');

        // Модель для LLM - используем модель по умолчанию для текущего провайдера
        $provider = config('llm.defaultLLM');
        $modelKey = config("llm.{$provider}.defaultModel");
        $model = $this->getModel($modelKey);

        // Начинаем измерение времени
        $startTime = microtime(true);

        try {
            // Отправляем запрос к LLM
            $llmResponse = $this->llm->qwery($systemPrompt, $contextualPrompt, $model);

            // Завершаем измерение времени
            $executionTime = round((microtime(true) - $startTime) * 1000); // в миллисекундах

            // Парсим структурированный ответ
            $parsedResponse = $this->parseStructuredResponse($llmResponse);

            // Сохраняем запрос в БД
            $promptRequest = PromptRequest::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'parent_id' => $parentId,
                'original_request' => $originalRequest,
                'clarification' => $clarification,
                'full_request' => $userPrompt,
                'domain' => $domain,
                'gen_provider' => $provider,
                'gen_model' => $model,
                'model' => $targetModel,
                'style' => $style,
                'format' => $format,
                'reasoning' => $parsedResponse['reasoning'],
                'questions' => $parsedResponse['questions'],
                'generated_prompt' => $parsedResponse['prompt'],
                'execution_time' => $executionTime,
                'tokens_in' => $this->estimateTokens($systemPrompt.$contextualPrompt),
                'tokens_out' => $this->estimateTokens($llmResponse),
            ]);

            Log::info('Prompt request saved', [
                'id' => $promptRequest->id,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'execution_time' => $executionTime,
            ]);

            // Возвращаем результат с параметрами и флагом для редактирования
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
                'session_id' => $promptRequest->session_id, // Возвращаем session_id
                'execution_time' => $executionTime,
                'is_clarification' => $isClarification,
                'is_chat_continuation' => $isChatContinuation,
                'allow_edit' => true, // Флаг, что промпт можно редактировать
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating prompt', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);

            // Определяем тип ошибки для более понятного сообщения
            $errorMessage = 'Произошла ошибка при генерации промпта. Попробуйте еще раз.';
            $errorType = 'general';

            $message = $e->getMessage();

            // Логируем для отладки
            Log::info('Обработка ошибки', [
                'original_message' => $message,
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);

            if (str_contains($message, '401') || str_contains($message, 'Authorization') || str_contains($message, 'Unauthorized')) {
                $errorMessage = 'Ошибка авторизации API. Проверьте настройки API ключа.';
                $errorType = 'auth';
            } elseif (str_contains($message, '429') || str_contains($message, 'rate limit') || str_contains($message, 'Too Many Requests')) {
                $errorMessage = 'Превышен лимит запросов к API. Попробуйте позже.';
                $errorType = 'rate_limit';
            } elseif (str_contains($message, 'timeout') || str_contains($message, 'Connection timed out') || str_contains($message, 'cURL error 28')) {
                $errorMessage = 'Превышено время ожидания ответа от API. Попробуйте еще раз.';
                $errorType = 'timeout';
            } elseif (str_contains($message, 'cURL error 6') || str_contains($message, 'Could not resolve host')) {
                $errorMessage = 'Ошибка подключения к серверу API. Проверьте интернет-соединение.';
                $errorType = 'connection';
            } elseif (str_contains($message, 'cURL error 7') || str_contains($message, 'Failed to connect')) {
                $errorMessage = 'Не удалось подключиться к серверу API. Сервер может быть недоступен.';
                $errorType = 'connection';
            } elseif (str_contains($message, '500') || str_contains($message, 'Internal Server Error')) {
                $errorMessage = 'Внутренняя ошибка сервера API. Попробуйте позже.';
                $errorType = 'server_error';
            } elseif (str_contains($message, '403') || str_contains($message, 'Forbidden')) {
                $errorMessage = 'Доступ к API запрещен. Проверьте настройки API ключа.';
                $errorType = 'forbidden';
            } elseif (str_contains($message, '404') || str_contains($message, 'Not Found')) {
                $errorMessage = 'API endpoint не найден. Возможно, изменился адрес API.';
                $errorType = 'not_found';
            } elseif (str_contains($message, 'cURL error 28') || str_contains($message, 'Operation timed out')) {
                // Специфичная обработка для таймаута
                $errorMessage = 'Превышено время ожидания ответа от API (30 секунд). Сервер может быть перегружен. Попробуйте еще раз.';
                $errorType = 'timeout';
            } elseif (str_contains($message, 'cURL error')) {
                // Общая обработка для всех остальных cURL ошибок
                $errorMessage = 'Ошибка сетевого подключения к API. Проверьте интернет-соединение и попробуйте позже.';
                $errorType = 'connection';
            }

            // Логируем результат обработки ошибки
            Log::info('Результат обработки ошибки', [
                'error_type' => $errorType,
                'error_message' => $errorMessage,
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);

            return response()->json([
                'success' => false,
                'error' => $errorMessage,
                'error_type' => $errorType,
                'message' => $e->getMessage(),
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Проверка лимитов для пользователя или сессии
     * Возвращает true если лимит не превышен, или JsonResponse с ошибкой
     */
    private function checkLimits($userId, $sessionId)
    {
        Log::info('Checking limits', [
            'user_id' => $userId,
            'session_id' => $sessionId,
            'is_authenticated' => Auth::check(),
        ]);

        // Проверяем лимиты для незарегистрированных пользователей
        if (! $userId && $sessionId) {
            $dailyLimit = config('ai.daily_limit_guest', 5);
            $todayCount = PromptRequest::getTodayCount($sessionId);
            $canMakeRequest = PromptRequest::checkDailyLimit($sessionId, $dailyLimit);

            Log::info('Limit check result', [
                'session_id' => $sessionId,
                'today_count' => $todayCount,
                'daily_limit' => $dailyLimit,
                'can_make_request' => $canMakeRequest,
            ]);

            if (! $canMakeRequest) {
                Log::warning('Request limit exceeded', [
                    'session_id' => $sessionId,
                    'today_count' => $todayCount,
                    'daily_limit' => $dailyLimit,
                ]);

                return response()->json([
                    'success' => false,
                    'error' => "Превышен лимит запросов. Вы использовали {$todayCount} из {$dailyLimit} запросов сегодня. Зарегистрируйтесь для увеличения лимита.",
                    'limit_reached' => true,
                ], 429);
            }
        }

        // Проверяем лимиты для зарегистрированных пользователей (если установлен)
        if ($userId) {
            $dailyLimit = config('ai.daily_limit_user');
            if ($dailyLimit !== null) {
                $todayCount = PromptRequest::where('user_id', $userId)
                    ->whereDate('created_at', today())
                    ->count();

                if ($todayCount >= $dailyLimit) {
                    Log::warning('User request limit exceeded', [
                        'user_id' => $userId,
                        'today_count' => $todayCount,
                        'daily_limit' => $dailyLimit,
                    ]);

                    return response()->json([
                        'success' => false,
                        'error' => "Превышен дневной лимит запросов. Вы использовали {$todayCount} из {$dailyLimit} запросов сегодня.",
                        'limit_reached' => true,
                    ], 429);
                }
            }
        }

        return true;
    }

    private function buildContextualPrompt($userPrompt, $domain, $targetModel, $style, $format)
    {
        $context = "Исходный запрос пользователя: \"$userPrompt\"\n\n";

        if ($domain) {
            $context .= "Область знаний: $domain\n";
        }

        if ($targetModel) {
            $context .= "Целевая модель: $targetModel\n";
        }

        if ($style) {
            $context .= "Стиль промпта: $style\n";
        }

        if ($format) {
            $context .= "Формат результата: $format\n";
        }

        return $context;
    }

    // парсим структурированный ответ
    private function parseStructuredResponse($response)
    {
        Log::info('Parsing markdown response', ['response_length' => strlen($response)]);

        // Инициализируем результат
        $result = [
            'reasoning' => '',
            'questions' => [],
            'prompt' => '',
        ];

        try {
            // Парсим новый формат с разделителями
            $sections = $this->parseMarkdownSections($response);

            if (! empty($sections)) {
                Log::info('Successfully parsed markdown sections');

                $result['reasoning'] = $sections['reasoning'] ?? '';
                $result['questions'] = $sections['questions'] ?? [];
                $result['prompt'] = $sections['prompt'] ?? '';

                Log::info('Markdown parse result', [
                    'reasoning_length' => strlen($result['reasoning']),
                    'questions_count' => count($result['questions']),
                    'prompt_length' => strlen($result['prompt']),
                ]);
            } else {
                Log::info('Markdown parse failed, trying JSON fallback');
                // Fallback к JSON парсеру для совместимости
                $result = $this->parseJsonFallback($response);
            }
        } catch (\Exception $e) {
            Log::info('Markdown parse exception', ['error' => $e->getMessage()]);
            // Fallback к JSON парсеру для совместимости
            $result = $this->parseJsonFallback($response);
        }

        // Если ничего не получилось, используем весь ответ как промпт
        if (empty($result['prompt'])) {
            $result['prompt'] = $response;
            Log::info('Using fallback - full response as prompt');
        }

        return $result;
    }

    /**
     * Парсинг markdown секций с разделителями
     */
    private function parseMarkdownSections($response)
    {
        $sections = [];

        // Разбиваем по заголовкам секций
        $pattern = '/##\s*(ХОД РАССУЖДЕНИЙ|ВОПРОСЫ|СГЕНЕРИРОВАННЫЙ ПРОМПТ)\s*\n(.*?)(?=##\s*(?:ХОД РАССУЖДЕНИЙ|ВОПРОСЫ|СГЕНЕРИРОВАННЫЙ ПРОМПТ)|$)/s';

        if (preg_match_all($pattern, $response, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $sectionName = trim($match[1]);
                $content = trim($match[2]);

                switch ($sectionName) {
                    case 'ХОД РАССУЖДЕНИЙ':
                        $sections['reasoning'] = $content;
                        break;
                    case 'ВОПРОСЫ':
                        $sections['questions'] = $this->parseQuestionsList($content);
                        break;
                    case 'СГЕНЕРИРОВАННЫЙ ПРОМПТ':
                        $sections['prompt'] = $content;
                        break;
                }
            }
        }

        return $sections;
    }

    /**
     * Парсинг списка вопросов из markdown
     */
    private function parseQuestionsList($content)
    {
        $questions = [];

        // Ищем строки, начинающиеся с - или *
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^[-*]\s*(.+)$/', $line, $matches)) {
                $questions[] = trim($matches[1]);
            }
        }

        return $questions;
    }

    /**
     * Fallback парсер для JSON (для совместимости)
     */
    private function parseJsonFallback($response)
    {
        $result = [
            'reasoning' => '',
            'questions' => [],
            'prompt' => '',
        ];

        try {
            $decoded = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $result['reasoning'] = $decoded['reasoning'] ?? '';
                $result['questions'] = is_array($decoded['questions'] ?? null) ? $decoded['questions'] : [];
                $result['prompt'] = $decoded['generated_prompt'] ?? '';
            }
        } catch (\Exception $e) {
            Log::info('JSON fallback failed', ['error' => $e->getMessage()]);
        }

        return $result;
    }

    private function parseLegacyResponse($response)
    {
        Log::info('Using legacy parser');

        $result = [
            'reasoning' => '',
            'questions' => [],
            'prompt' => '',
        ];

        // Простой парсер для старого формата
        $patterns = [
            'reasoning' => '/\*\*ХОД РАССУЖДЕНИЙ\*\*:\s*(.*?)(?=\*\*УТОЧНЯЮЩИЕ ВОПРОСЫ\*\*:|\*\*СГЕНЕРИРОВАННЫЙ ПРОМПТ\*\*:|$)/s',
            'questions' => '/\*\*УТОЧНЯЮЩИЕ ВОПРОСЫ\*\*:\s*(.*?)(?=\*\*СГЕНЕРИРОВАННЫЙ ПРОМПТ\*\*:|$)/s',
            'prompt' => '/\*\*СГЕНЕРИРОВАННЫЙ ПРОМПТ\*\*:\s*(.*?)$/s',
        ];

        foreach ($patterns as $key => $pattern) {
            if (preg_match($pattern, $response, $matches)) {
                $content = trim($matches[1]);

                if ($key === 'questions') {
                    $questions = array_filter(array_map('trim', preg_split('/\n|\r\n?/', $content)));
                    $result['questions'] = array_values($questions);
                } else {
                    $result[$key] = $content;
                }
            }
        }

        return $result;
    }

    /**
     * Примерная оценка количества токенов в тексте
     * (простая формула: ~4 символа = 1 токен)
     */
    private function estimateTokens(string $text): int
    {
        return (int) ceil(strlen($text) / 4);
    }

    /**
     * Получить информацию о лимитах запросов для текущего пользователя/сессии
     */
    public function getLimits(Request $request)
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        $result = [
            'is_authenticated' => Auth::check(),
            'daily_limit_guest' => config('ai.daily_limit_guest', 5),
            'daily_limit_user' => config('ai.daily_limit_user'),
            'today_count' => 0,
            'remaining_requests' => null,
            'limit_reached' => false,
        ];

        if ($userId) {
            // Зарегистрированный пользователь
            $dailyLimit = config('ai.daily_limit_user');
            if ($dailyLimit !== null) {
                $todayCount = PromptRequest::where('user_id', $userId)
                    ->whereDate('created_at', today())
                    ->count();

                $result['today_count'] = $todayCount;
                $result['remaining_requests'] = max(0, $dailyLimit - $todayCount);
                $result['limit_reached'] = $todayCount >= $dailyLimit;
            } else {
                // Без лимита
                $result['remaining_requests'] = null;
            }
        } else {
            // Незарегистрированный пользователь
            $dailyLimit = config('ai.daily_limit_guest', 5);
            $todayCount = PromptRequest::getTodayCount($sessionId);

            $result['today_count'] = $todayCount;
            $result['remaining_requests'] = max(0, $dailyLimit - $todayCount);
            $result['limit_reached'] = $todayCount >= $dailyLimit;
        }

        return response()->json($result);
    }

    /**
     * Отображение страницы настроек сервиса
     */
    public function settings()
    {
        $settings = [
            'ai' => config('ai'),
            'llm' => config('llm'),
            'app' => [
                'name' => config('app.name'),
                'admin_emails' => config('app.admin_emails'),
            ],
            'api_keys' => [
                'perplexity' => env('PERPLEXITY_API_KEY'),
                'openrouter' => env('OPENROUTER_API_KEY'),
                'yandex' => env('YANDEX_LLM_API_KEY'),
                'yandex_folder_id' => env('YANDEX_FOLDER_ID'),
            ],
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Обновление настроек сервиса
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'ai.daily_limit_guest' => 'required|integer|min:1|max:100',
            'ai.daily_limit_user' => 'nullable|integer|min:1|max:1000',
            'ai.max_tokens' => 'required|integer|min:100|max:10000',
            'ai.temperature' => 'required|numeric|min:0|max:2',
            'ai.cleanup_days_guest' => 'required|integer|min:1|max:30',
            'ai.system_prompt' => 'required|string|min:100',
            'llm.defaultLLM' => 'required|string|in:perplexity,openrouter,yandex',
            // API ключи не валидируются - они редактируются только в .env файле
            'llm.perplexity.base_url' => 'required|url',
            'llm.perplexity.defaultModel' => 'required|string',
            'llm.openrouter.base_url' => 'nullable|url',
            'llm.openrouter.defaultModel' => 'nullable|string',
            'llm.yandex.base_url' => 'nullable|url',
            'llm.yandex.defaultModel' => 'nullable|string',
            'app.name' => 'required|string|max:255',
            'app.admin_emails' => 'required|array|min:1',
            'app.admin_emails.*' => 'required|email',
        ], [
            'ai.daily_limit_guest.required' => 'Дневной лимит для гостей обязателен',
            'ai.daily_limit_guest.integer' => 'Дневной лимит для гостей должен быть числом',
            'ai.daily_limit_guest.min' => 'Дневной лимит для гостей должен быть не менее 1',
            'ai.daily_limit_guest.max' => 'Дневной лимит для гостей должен быть не более 100',
            'ai.max_tokens.required' => 'Максимальное количество токенов обязательно',
            'ai.max_tokens.integer' => 'Максимальное количество токенов должно быть числом',
            'ai.max_tokens.min' => 'Максимальное количество токенов должно быть не менее 100',
            'ai.max_tokens.max' => 'Максимальное количество токенов должно быть не более 10000',
            'ai.temperature.required' => 'Температура обязательна',
            'ai.temperature.numeric' => 'Температура должна быть числом',
            'ai.temperature.min' => 'Температура должна быть не менее 0',
            'ai.temperature.max' => 'Температура должна быть не более 2',
            'ai.system_prompt.required' => 'Системный промпт обязателен',
            'ai.system_prompt.string' => 'Системный промпт должен быть текстом',
            'ai.system_prompt.min' => 'Системный промпт должен содержать не менее 100 символов',
            'llm.defaultLLM.required' => 'Выбор провайдера LLM обязателен',
            'llm.defaultLLM.in' => 'Провайдер LLM должен быть perplexity, openrouter или yandex',
            'llm.perplexity.base_url.required' => 'Базовый URL Perplexity обязателен',
            'llm.perplexity.base_url.url' => 'Базовый URL Perplexity должен быть валидным URL',
            'llm.perplexity.defaultModel.required' => 'Модель по умолчанию для Perplexity обязательна',
            'llm.openrouter.base_url.url' => 'Базовый URL OpenRouter должен быть валидным URL',
            'llm.yandex.base_url.url' => 'Базовый URL Yandex должен быть валидным URL',
            'app.name.required' => 'Название приложения обязательно',
            'app.name.string' => 'Название приложения должно быть текстом',
            'app.name.max' => 'Название приложения должно содержать не более 255 символов',
            'app.admin_emails.required' => 'Список администраторов обязателен',
            'app.admin_emails.array' => 'Список администраторов должен быть массивом',
            'app.admin_emails.min' => 'Должен быть указан хотя бы один администратор',
            'app.admin_emails.*.required' => 'Email администратора обязателен',
            'app.admin_emails.*.email' => 'Email администратора должен быть валидным',
        ]);

        try {
            // Подготавливаем данные для сохранения
            $aiData = $request->input('ai');
            $llmData = $request->input('llm');
            $appData = $request->input('app');

            // Дополнительная валидация типов данных
            $this->validateConfigData($aiData, $llmData, $appData);

            // Обновляем конфигурационные файлы
            $this->updateConfigFile('ai', $aiData);
            $this->updateConfigFile('llm', $llmData);
            $this->updateAppConfig($appData);

            // Очищаем кэш конфигурации
            Artisan::call('config:clear');

            return redirect()->route('admin.settings')
                ->with('success', 'Настройки успешно обновлены!');

        } catch (\Exception $e) {
            Log::error('Ошибка обновления настроек: '.$e->getMessage());

            return redirect()->route('admin.settings')
                ->with('error', 'Ошибка при обновлении настроек: '.$e->getMessage());
        }
    }

    /**
     * Просмотр статистики запросов
     */
    public function statistics()
    {
        $stats = [
            'total_requests' => PromptRequest::count(),
            'today_requests' => PromptRequest::whereDate('created_at', today())->count(),
            'this_week_requests' => PromptRequest::where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month_requests' => PromptRequest::where('created_at', '>=', now()->startOfMonth())->count(),
            'registered_users_requests' => PromptRequest::whereNotNull('user_id')->count(),
            'guest_requests' => PromptRequest::whereNull('user_id')->count(),
            'average_execution_time' => PromptRequest::avg('execution_time'),
            'total_tokens_in' => PromptRequest::sum('tokens_in'),
            'total_tokens_out' => PromptRequest::sum('tokens_out'),
        ];

        // Статистика по дням за последние 30 дней
        $dailyStats = PromptRequest::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Топ доменов
        $topDomains = PromptRequest::selectRaw('domain, COUNT(*) as count')
            ->whereNotNull('domain')
            ->groupBy('domain')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('admin.statistics', compact('stats', 'dailyStats', 'topDomains'));
    }

    /**
     * Управление запросами пользователей
     */
    public function requests(Request $request)
    {
        $query = PromptRequest::with('user')->orderBy('created_at', 'desc');

        // Фильтрация
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('domain')) {
            $query->where('domain', $request->input('domain'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $requests = $query->paginate(20);

        // Получаем уникальные домены для фильтра
        $domains = PromptRequest::select('domain')
            ->whereNotNull('domain')
            ->distinct()
            ->pluck('domain');

        return view('admin.requests', compact('requests', 'domains'));
    }

    /**
     * Обновление конфигурационного файла
     */
    private function updateConfigFile(string $configName, array $data)
    {
        $configPath = config_path("{$configName}.php");

        if (! file_exists($configPath)) {
            throw new \Exception("Конфигурационный файл {$configName}.php не найден");
        }

        $config = include $configPath;

        // Фильтруем API ключи - они редактируются только в .env файле
        if ($configName === 'llm') {
            // Удаляем API ключи из исходной конфигурации
            $config = $this->filterApiKeys($config);
            // Удаляем API ключи из новых данных
            $data = $this->filterApiKeys($data);
        }

        // Рекурсивно обновляем конфигурацию, но не используем array_merge_recursive
        // чтобы избежать создания массивов из строк
        $config = $this->arrayMergeDeep($config, $data);

        // Генерируем PHP код для конфигурации
        $phpCode = "<?php\n\nreturn ".var_export($config, true).";\n";

        if (file_put_contents($configPath, $phpCode) === false) {
            throw new \Exception("Не удалось записать в файл {$configPath}");
        }
    }

    /**
     * Фильтрация API ключей из конфигурации LLM
     * API ключи хранятся только в .env файле для безопасности
     */
    private function filterApiKeys(array $data): array
    {
        // Удаляем API ключи из всех провайдеров
        $sensitiveKeys = ['api_key', 'folder_id'];

        foreach ($data as $provider => &$config) {
            if (is_array($config)) {
                foreach ($sensitiveKeys as $key) {
                    unset($config[$key]);
                }
            }
        }

        return $data;
    }

    /**
     * Глубокое слияние массивов без создания массивов из строк
     */
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

    /**
     * Дополнительная валидация конфигурационных данных
     */
    private function validateConfigData(array $aiData, array $llmData, array $appData): void
    {
        // Проверяем, что строковые значения не являются массивами
        if (isset($aiData['system_prompt']) && is_array($aiData['system_prompt'])) {
            throw new \Exception('Системный промпт не может быть массивом');
        }

        if (isset($llmData['perplexity']['base_url']) && is_array($llmData['perplexity']['base_url'])) {
            throw new \Exception('Базовый URL не может быть массивом');
        }

        if (isset($appData['name']) && is_array($appData['name'])) {
            throw new \Exception('Название приложения не может быть массивом');
        }

        // Проверяем, что числовые значения корректны
        if (isset($aiData['max_tokens']) && ! is_numeric($aiData['max_tokens'])) {
            throw new \Exception('Максимальное количество токенов должно быть числом');
        }

        if (isset($aiData['temperature']) && ! is_numeric($aiData['temperature'])) {
            throw new \Exception('Температура должна быть числом');
        }
    }

    /**
     * Обновление конфигурации приложения
     */
    private function updateAppConfig(array $data)
    {
        $configPath = config_path('app.php');
        $config = include $configPath;

        // Обновляем только разрешенные поля
        if (isset($data['name'])) {
            $config['name'] = $data['name'];
        }

        if (isset($data['admin_emails'])) {
            $config['admin_emails'] = $data['admin_emails'];
        }

        $phpCode = "<?php\n\nreturn ".var_export($config, true).";\n";

        if (file_put_contents($configPath, $phpCode) === false) {
            throw new \Exception("Не удалось записать в файл {$configPath}");
        }
    }

    /**
     * Очистка кэша
     */
    public function clearCache(Request $request): JsonResponse
    {
        try {
            $type = $request->input('type', 'all');

            match ($type) {
                'config' => Artisan::call('config:clear'),
                'view' => Artisan::call('view:clear'),
                'route' => Artisan::call('route:clear'),
                'all' => Artisan::call('optimize:clear'),
                default => throw new \InvalidArgumentException('Неизвестный тип кэша'),
            };

            Log::info('Кэш очищен', ['type' => $type, 'user_id' => Auth::id()]);

            return response()->json([
                'success' => true,
                'message' => 'Кэш успешно очищен',
            ]);
        } catch (\Exception $e) {
            Log::error('Ошибка очистки кэша: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Очистка логов
     */
    public function clearLogs(Request $request): JsonResponse
    {
        try {
            $logPath = storage_path('logs/laravel.log');

            if (file_exists($logPath)) {
                file_put_contents($logPath, '');
            }

            $browserLogPath = storage_path('logs/browser.log');
            if (file_exists($browserLogPath)) {
                file_put_contents($browserLogPath, '');
            }

            Log::info('Логи очищены', ['user_id' => Auth::id()]);

            return response()->json([
                'success' => true,
                'message' => 'Логи успешно очищены',
            ]);
        } catch (\Exception $e) {
            Log::error('Ошибка очистки логов: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Сохранение отредактированного промпта (без отправки в LLM)
     */
    public function sendEditedPrompt(Request $request)
    {
        $request->validate([
            'request_id' => 'required|integer|exists:prompt_requests,id',
            'edited_prompt' => 'required|string|max:10000',
        ]);

        try {
            $userId = \Illuminate\Support\Facades\Auth::id();
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Пользователь не авторизован',
                ], 401);
            }

            $promptRequest = PromptRequest::findOrFail($request->request_id);

            // Проверяем, что запрос принадлежит текущему пользователю
            if ($promptRequest->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Доступ запрещен',
                ], 403);
            }

            // Обновляем промпт в существующем запросе
            $promptRequest->update([
                'generated_prompt' => $request->edited_prompt,
                'reasoning' => 'Отредактированный промпт пользователем',
            ]);

            Log::info('Edited prompt saved locally', [
                'request_id' => $promptRequest->id,
                'user_id' => $userId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Промпт успешно сохранен',
                'request_id' => $promptRequest->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving edited prompt', [
                'error' => $e->getMessage(),
                'user_id' => $userId ?? null,
                'request_id' => $request->request_id ?? null,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Ошибка при сохранении отредактированного промпта: '.$e->getMessage(),
            ], 500);
        }
    }
}
