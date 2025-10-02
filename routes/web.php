<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\YandexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Здесь определены все веб-маршруты для приложения. Эти маршруты
| загружаются RouteServiceProvider и все они будут назначены группе
| middleware "web". Создайте что-то великолепное!
|
*/

// ============================================================================
// ПУБЛИЧНЫЕ МАРШРУТЫ (доступны всем пользователям)
// ============================================================================

/**
 * Главная страница приложения
 * Отображает welcome.blade.php с формой создания промптов
 */
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ============================================================================
// API МАРШРУТЫ ДЛЯ ИИ-ФУНКЦИОНАЛЬНОСТИ
// ============================================================================

/**
 * Создание промпта через ИИ для главной страницы
 * POST /generate-prompt
 * Обрабатывает запросы на генерацию промптов с учетом лимитов пользователей
 * Поддерживает уточнения через parent_id
 */
Route::post('/generate-prompt', [AIController::class, 'generatePromptForWelcomePage'])->name('generate-prompt');

/**
 * Создание промпта через ИИ для чата
 * POST /chat/generate-prompt
 * Обрабатывает запросы на генерацию промптов в режиме чата
 * Поддерживает продолжение диалога через session_id
 */
Route::post('/chat/generate-prompt', [AIController::class, 'generatePromptForChat'])->name('chat.generate-prompt');

/**
 * Отправка отредактированного промпта в LLM
 * POST /chat/send-edited-prompt
 * Отправляет отредактированный пользователем промпт в LLM
 */
Route::post('/chat/send-edited-prompt', [AIController::class, 'sendEditedPrompt'])->name('chat.send-edited-prompt');

/**
 * Получение информации о лимитах запросов
 * GET /api/limits
 * Возвращает JSON с информацией о доступных запросах для текущего пользователя/сессии
 */
Route::get('/api/limits', [AIController::class, 'getLimits'])->name('api.limits');

/**
 * API маршруты для работы с сессиями
 */
Route::middleware(['auth'])->prefix('api/sessions')->group(function () {
    Route::get('/', [SessionController::class, 'index'])->name('sessions.index');
    Route::get('/{sessionId}', [SessionController::class, 'show'])->name('sessions.show');
    Route::post('/', [SessionController::class, 'store'])->name('sessions.store');
    Route::delete('/{sessionId}', [SessionController::class, 'destroy'])->name('sessions.destroy');
});

// ============================================================================
// АУТЕНТИФИЦИРОВАННЫЕ МАРШРУТЫ
// ============================================================================

Route::get('/chat', function () {
    return view('chat');
})->middleware(['auth'])->name('chat');

Route::get('/chat/{id}', function ($id) {
    return view('chat', ['chatId' => $id]);
})->middleware(['auth'])->name('chat.show');

/**
 * Группа маршрутов для управления профилем пользователя
 * Все маршруты требуют аутентификации
 */
Route::middleware('auth')->group(function () {
    /**
     * Отображение формы редактирования профиля
     * GET /profile
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    /**
     * Обновление данных профиля
     * PATCH /profile
     */
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    /**
     * Удаление аккаунта пользователя
     * DELETE /profile
     */
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================================
// АДМИНИСТРАТИВНЫЕ МАРШРУТЫ
// ============================================================================

/**
 * Группа маршрутов для администраторов
 * Требуют аутентификации и проверки прав администратора
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    /**
     * Страница настроек сервиса
     * GET /admin/settings
     */
    Route::get('/settings', [AIController::class, 'settings'])->name('settings');

    /**
     * Обновление настроек сервиса
     * POST /admin/settings
     */
    Route::post('/settings', [AIController::class, 'updateSettings'])->name('settings.update');

    /**
     * Очистка кэша
     * POST /admin/clear-cache
     */
    Route::post('/clear-cache', [AIController::class, 'clearCache'])->name('clear-cache');

    /**
     * Очистка логов
     * POST /admin/clear-logs
     */
    Route::post('/clear-logs', [AIController::class, 'clearLogs'])->name('clear-logs');

    /**
     * Просмотр статистики запросов
     * GET /admin/statistics
     */
    Route::get('/statistics', [AIController::class, 'statistics'])->name('statistics');

    /**
     * Управление запросами пользователей
     * GET /admin/requests
     */
    Route::get('/requests', [AIController::class, 'requests'])->name('requests');
});

// ============================================================================
// СОЦИАЛЬНАЯ АУТЕНТИФИКАЦИЯ
// ============================================================================

/**
 * Google OAuth маршруты
 * Перенаправление на Google и обработка callback'а
 */
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

/**
 * Yandex OAuth маршруты
 * Перенаправление на Yandex и обработка callback'а
 */
Route::get('auth/yandex', [YandexController::class, 'redirectToYandex']);
Route::get('auth/yandex/callback', [YandexController::class, 'handleYandexCallback']);

// ============================================================================
// СТАНДАРТНЫЕ МАРШРУТЫ АУТЕНТИФИКАЦИИ
// ============================================================================

/**
 * Подключение стандартных маршрутов Laravel Breeze
 * Включает: login, register, password reset, email verification и т.д.
 */
require __DIR__.'/auth.php';
