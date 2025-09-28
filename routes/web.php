<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AIController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\YandexController;

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
});

// ============================================================================
// API МАРШРУТЫ ДЛЯ ИИ-ФУНКЦИОНАЛЬНОСТИ
// ============================================================================

/**
 * Создание промпта через ИИ
 * POST /generate-prompt
 * Обрабатывает запросы на генерацию промптов с учетом лимитов пользователей
 */
Route::post('/generate-prompt', [AIController::class, 'generatePrompt'])->name('generate-prompt');

/**
 * Получение информации о лимитах запросов
 * GET /api/limits
 * Возвращает JSON с информацией о доступных запросах для текущего пользователя/сессии
 */
Route::get('/api/limits', [AIController::class, 'getLimits'])->name('api.limits');

// ============================================================================
// АУТЕНТИФИЦИРОВАННЫЕ МАРШРУТЫ
// ============================================================================

/**
 * Панель управления пользователя
 * Доступна только аутентифицированным и верифицированным пользователям
 */
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
 * API маршруты для работы с сессиями
 */
Route::middleware(['auth'])->prefix('api/sessions')->group(function () {
    Route::get('/', [App\Http\Controllers\SessionController::class, 'index'])->name('sessions.index');
    Route::get('/{sessionId}', [App\Http\Controllers\SessionController::class, 'show'])->name('sessions.show');
    Route::post('/', [App\Http\Controllers\SessionController::class, 'store'])->name('sessions.store');
    Route::delete('/{sessionId}', [App\Http\Controllers\SessionController::class, 'destroy'])->name('sessions.destroy');
});

// ============================================================================

/**
 * Подключение стандартных маршрутов Laravel Breeze
 * Включает: login, register, password reset, email verification и т.д.
 */
require __DIR__.'/auth.php';
