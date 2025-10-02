<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PromptRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SessionController extends Controller
{
    /**
     * Получить историю сессий пользователя (до 10 сессий)
     */
    public function index(): JsonResponse
    {
        $userId = Auth::id();

        if (! $userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Получаем уникальные сессии пользователя
        // Получаем обычные сессии (с session_id)
        $regularSessions = PromptRequest::where('user_id', $userId)
            ->whereNotNull('session_id')
            ->select([
                'session_id',
                'domain',
                'model',
                'style',
                'format',
                DB::raw('COUNT(*) as requests_count'),
                DB::raw('MAX(original_request) as latest_request'),
                DB::raw('MAX(created_at) as latest_created_at'),
                DB::raw('DATE(MAX(created_at)) as session_date'),
            ])
            ->groupBy([
                'session_id',
                'domain',
                'model',
                'style',
                'format',
            ])
            ->get();

        // Получаем виртуальные сессии (без session_id, группируем по дате и домену)
        $virtualSessions = PromptRequest::where('user_id', $userId)
            ->whereNull('session_id')
            ->select([
                DB::raw('NULL as session_id'),
                'domain',
                'model',
                'style',
                'format',
                DB::raw('COUNT(*) as requests_count'),
                DB::raw('MAX(original_request) as latest_request'),
                DB::raw('MAX(created_at) as latest_created_at'),
                DB::raw('DATE(MAX(created_at)) as session_date'),
            ])
            ->groupBy([
                'domain',
                'model',
                'style',
                'format',
                DB::raw('DATE(created_at)'),
            ])
            ->get();

        // Объединяем и сортируем все сессии
        $sessions = $regularSessions->concat($virtualSessions)
            ->sortByDesc('latest_created_at')
            ->take(10)
            ->map(function ($session) {
                // Создаем виртуальный session_id если его нет
                $sessionId = $session->session_id ?: 'daily_'.$session->session_date.'_'.substr(md5($session->latest_request), 0, 8);

                // Определяем название сессии на основе домена или первого запроса
                $sessionName = $this->generateSessionName($session);

                // Определяем описание сессии
                $sessionDescription = $this->generateSessionDescription($session);

                return [
                    'id' => $sessionId,
                    'name' => $sessionName,
                    'description' => $sessionDescription,
                    'domain' => $session->domain,
                    'model' => $session->model,
                    'style' => $session->style,
                    'format' => $session->format,
                    'requests_count' => $session->requests_count,
                    'latest_request' => $session->latest_request,
                    'created_at' => $session->latest_created_at,
                    'updated_at' => $session->latest_created_at,
                ];
            });

        return response()->json([
            'sessions' => $sessions->values()->toArray(),
            'total' => $sessions->count(),
        ]);
    }

    /**
     * Получить детали сессии
     */
    public function show(string $sessionId): JsonResponse
    {
        $userId = Auth::id();

        if (! $userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Получаем все запросы в сессии
        $requests = PromptRequest::where('user_id', $userId);

        // Определяем тип сессии и строим соответствующий запрос
        if (str_starts_with($sessionId, 'daily_')) {
            // Виртуальная сессия по дате
            $date = substr($sessionId, 6, 10); // Извлекаем дату

            // Для виртуальных сессий нужно определить домен из ID
            // ID имеет формат: daily_YYYY-MM-DD_hash, где hash основан на latest_request
            // Нам нужно найти запросы с таким же доменом на эту дату

            // Сначала получаем все запросы без session_id на эту дату
            $allRequests = $requests->whereNull('session_id')
                ->whereDate('created_at', $date)
                ->orderBy('created_at', 'desc')
                ->get();

            // Группируем по домену и находим группу, которая соответствует данному sessionId
            $groupedRequests = $allRequests->groupBy('domain');

            // Находим группу, которая соответствует данному sessionId
            $requests = collect();
            foreach ($groupedRequests as $domain => $domainRequests) {
                // Создаем виртуальный ID для этой группы
                $virtualId = 'daily_'.$date.'_'.substr(md5($domainRequests->first()->original_request), 0, 8);

                if ($virtualId === $sessionId) {
                    $requests = $domainRequests;
                    break;
                }
            }

            // Если не нашли точное совпадение, берем все запросы на эту дату
            if ($requests->isEmpty()) {
                $requests = $allRequests;
            }
        } else {
            // Обычная сессия по session_id
            $requests = $requests->where('session_id', $sessionId)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        if ($requests->isEmpty()) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        // Группируем запросы и получаем статистику
        $sessionInfo = [
            'id' => $sessionId,
            'total_requests' => $requests->count(),
            'first_request_at' => $requests->min('created_at'),
            'last_request_at' => $requests->max('created_at'),
            'domains' => $requests->pluck('domain')->filter()->unique()->values(),
            'models' => $requests->pluck('model')->filter()->unique()->values(),
            'requests' => $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'original_request' => $request->original_request,
                    'domain' => $request->domain,
                    'model' => $request->model,
                    'style' => $request->style,
                    'format' => $request->format,
                    'generated_prompt' => $request->generated_prompt,
                    'reasoning' => $request->reasoning,
                    'questions' => $this->decodeQuestions($request->questions),
                    'created_at' => $request->created_at,
                ];
            }),
        ];

        return response()->json($sessionInfo);
    }

    /**
     * Создать новую сессию
     */
    public function store(Request $request): JsonResponse
    {
        $userId = Auth::id();

        if (! $userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'style' => 'nullable|string|max:255',
            'format' => 'nullable|string|max:255',
        ]);

        // Генерируем уникальный session_id
        $sessionId = Str::random(40);

        // Создаем запись о новой сессии (можно сохранить в отдельной таблице sessions)
        // Пока что просто возвращаем session_id для использования в prompt_requests

        return response()->json([
            'session_id' => $sessionId,
            'message' => 'Session created successfully',
        ], 201);
    }

    /**
     * Удалить сессию
     */
    public function destroy(string $sessionId): JsonResponse
    {
        $userId = Auth::id();

        if (! $userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Удаляем все запросы в сессии
        $deletedCount = PromptRequest::where('user_id', $userId)
            ->where(function ($query) use ($sessionId) {
                $query->where('session_id', $sessionId)
                    ->orWhere(function ($subQuery) use ($sessionId) {
                        // Для виртуальных сессий по дате
                        if (str_starts_with($sessionId, 'daily_')) {
                            $date = substr($sessionId, 6, 10);
                            $subQuery->whereNull('session_id')
                                ->whereDate('created_at', $date);
                        }
                    });
            })
            ->delete();

        if ($deletedCount === 0) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        return response()->json([
            'message' => 'Session deleted successfully',
            'deleted_requests' => $deletedCount,
        ]);
    }

    /**
     * Сгенерировать название сессии
     */
    private function generateSessionName($session): string
    {
        // Если есть домен, используем его
        if ($session->domain && $session->domain !== 'Выберите область') {
            return $session->domain;
        }

        // Если есть модель, используем её
        if ($session->model && $session->model !== 'Выберите модель') {
            return $session->model;
        }

        // Обрезаем первый запрос до 30 символов
        $request = $session->latest_request ?? 'Новая сессия';

        return mb_substr($request, 0, 30).(mb_strlen($request) > 30 ? '...' : '');
    }

    /**
     * Сгенерировать описание сессии
     */
    private function generateSessionDescription($session): string
    {
        $parts = [];

        if ($session->domain && $session->domain !== 'Выберите область') {
            $parts[] = $session->domain;
        }

        if ($session->model && $session->model !== 'Выберите модель') {
            $parts[] = $session->model;
        }

        if ($session->requests_count > 1) {
            $parts[] = "{$session->requests_count} запросов";
        }

        return implode(' • ', $parts) ?: 'Различные запросы';
    }

    /**
     * Безопасно декодировать поле questions
     */
    private function decodeQuestions($questions): array
    {
        if (empty($questions)) {
            return [];
        }

        // Если уже массив, возвращаем как есть
        if (is_array($questions)) {
            return $questions;
        }

        // Если строка, пытаемся декодировать JSON
        if (is_string($questions)) {
            $decoded = json_decode($questions, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }
}
