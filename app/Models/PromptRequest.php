<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromptRequest extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'parent_id',
        'original_request',
        'clarification',
        'full_request',
        'domain',
        'model',
        'style',
        'format',
        'reasoning',
        'questions',
        'generated_prompt',
        'execution_time',
        'tokens_in',
        'tokens_out',
    ];

    protected $casts = [
        'questions' => 'array',
        'execution_time' => 'integer',
        'tokens_in' => 'integer',
        'tokens_out' => 'integer',
    ];

    /**
     * Пользователь, создавший запрос
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Родительский запрос (для уточнений)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(PromptRequest::class, 'parent_id');
    }

    /**
     * Дочерние запросы (уточнения)
     */
    public function children(): HasMany
    {
        return $this->hasMany(PromptRequest::class, 'parent_id');
    }

    /**
     * Получить всю историю запроса (включая уточнения)
     */
    public function getFullHistory(): \Illuminate\Database\Eloquent\Collection
    {
        $rootRequest = $this->parent_id ? $this->parent : $this;

        return PromptRequest::where(function ($query) use ($rootRequest) {
            $query->where('id', $rootRequest->id)
                  ->orWhere('parent_id', $rootRequest->id);
        })->orderBy('created_at')->get();
    }

    /**
     * Проверить, является ли запрос первоначальным
     */
    public function isInitial(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Получить корневой запрос
     */
    public function getRoot(): PromptRequest
    {
        return $this->parent_id ? $this->parent : $this;
    }

    /**
     * Проверить лимит запросов для незарегистрированного пользователя
     */
    public static function checkDailyLimit(string $sessionId, int $limit = 5): bool
    {
        $today = now()->startOfDay();
        $count = self::where('session_id', $sessionId)
            ->where('created_at', '>=', $today)
            ->count();

        return $count < $limit;
    }

    /**
     * Получить количество запросов за сегодня для сессии
     */
    public static function getTodayCount(string $sessionId): int
    {
        $today = now()->startOfDay();
        return self::where('session_id', $sessionId)
            ->where('created_at', '>=', $today)
            ->count();
    }

    /**
     * Получить историю запросов для пользователя или сессии
     */
    public static function getHistoryForUser(?int $userId = null, ?string $sessionId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = self::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Очистить старые запросы незарегистрированных пользователей
     */
    public static function cleanupOldRequests(int $days = 3): int
    {
        $cutoffDate = now()->subDays($days);

        return self::whereNull('user_id')
            ->where('created_at', '<', $cutoffDate)
            ->delete();
    }
}
