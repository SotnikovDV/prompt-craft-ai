@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50">
    <!-- Левая панель - История сессий -->
    <div class="w-80 bg-white border-r border-gray-200 flex flex-col">
        <!-- Заголовок панели -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">История сессий</h2>
                <button onclick="createNewSession()"
                    class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                    title="Новая сессия">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Список сессий -->
        <div class="flex-1 overflow-y-auto">
            <div id="sessions-list" class="p-2 space-y-1">
                <!-- Загрузка сессий -->
                <div id="sessions-loading" class="p-4 text-center">
                    <svg class="w-6 h-6 animate-spin text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <p class="text-sm text-gray-500">Загрузка сессий...</p>
                </div>

                <!-- Пустое состояние -->
                <div id="sessions-empty" class="p-4 text-center hidden">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-sm text-gray-500">У вас пока нет сессий</p>
                    <p class="text-xs text-gray-400 mt-1">Создайте первый промпт!</p>
                </div>
            </div>
        </div>

        <!-- Нижняя панель -->
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-bold">{{ substr(Auth::user()->name ?? Auth::user()->email, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name ?? Auth::user()->email }}</p>
                    <p class="text-xs text-gray-500">Премиум аккаунт</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="flex-1 flex flex-col">
        <!-- Заголовок текущей сессии -->
        <div class="p-4 border-b border-gray-200 bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-3">
                        <h1 id="session-title" class="text-xl font-semibold text-gray-900">Текущая сессия</h1>
                        <span id="mode-indicator" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Новый запрос
                        </span>
                    </div>
                    <p id="session-description" class="text-sm text-gray-500">Создание промптов для ИИ</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="toggleMobileSidebar()"
                        class="md:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <button onclick="shareSession()"
                        class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                        title="Поделиться сессией">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Основной контент - функционал генерации промптов -->
        <div class="flex-1 overflow-y-auto">
            <!-- Форма создания промпта -->
            <div class="p-6">
                <div class="max-w-4xl mx-auto">
                    <!-- Градиентная панель ввода запроса -->
                    <div class="rounded-2xl p-[1px] shadow-lg bg-gradient-to-r from-blue-500/30 via-blue-300/20 to-orange-500/30">
                        <div class="bg-white/90 rounded-2xl border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Создание промпта</h2>
                            <p class="text-gray-600 mb-4">Напишите что хотите, а мы превратим это в шедевр для ИИ. Даже если вы пишете как первоклассник!</p>

                            <form id="prompt-form" class="space-y-6">
                                <div>
                                    <label for="prompt-input" class="block text-sm font-medium text-gray-700 mb-2">Ваш запрос</label>
                                    <textarea id="prompt-input" name="prompt" rows="6"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                        placeholder="Например: Я хочу написать техническую статью о нейросетях..." maxlength="3000"></textarea>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-sm text-gray-500">Минимум 10 символов</span>
                                        <span class="text-sm text-gray-500">
                                            <span id="char-count">0</span>/3000
                                        </span>
                                    </div>
                                </div>

                                <!-- Кнопка для раскрытия дополнительных параметров -->
                                <div class="flex items-center justify-between text-blue-600 hover:text-white px-4 py-3 rounded-lg cursor-pointer hover:bg-orange-600 transition-colors"
                                    onclick="toggleAdvancedOptions()">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-settings h-4 w-4">
                                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <span class="text-sm font-medium ml-2">Дополнительные параметры</span>
                                    </div>
                                    <svg id="toggle-icon" class="w-5 h-5 transition-transform duration-200 text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <!-- Панель дополнительных параметров (скрыта по умолчанию) -->
                                <div id="advanced-options" class="hidden bg-gray-50 rounded-lg py-2 space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Область знаний -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Область знаний</label>
                                            <select name="domain"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="Выберите область">Выберите область</option>
                                                <option value="Программирование">Программирование</option>
                                                <option value="Технологии">Технологии</option>
                                                <option value="Маркетинг">Маркетинг</option>
                                                <option value="Образование">Образование</option>
                                                <option value="Медицина">Медицина</option>
                                                <option value="Финансы">Финансы</option>
                                                <option value="Дизайн">Дизайн</option>
                                                <option value="Писательство">Писательство</option>
                                            </select>
                                        </div>

                                        <!-- Целевая модель -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Целевая модель</label>
                                            <select name="model"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="Выберите модель">Выберите модель</option>
                                                <option value="ChatGPT-4">ChatGPT-4</option>
                                                <option value="ChatGPT-3.5">ChatGPT-3.5</option>
                                                <option value="Claude">Claude</option>
                                                <option value="Gemini">Gemini</option>
                                                <option value="Llama">Llama</option>
                                                <option value="Perplexity">Perplexity</option>
                                                <option value="Универсальный">Универсальный</option>
                                            </select>
                                        </div>

                                        <!-- Стиль -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Стиль</label>
                                            <select name="style"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="Профессиональный">Профессиональный</option>
                                                <option value="Дружелюбный">Дружелюбный</option>
                                                <option value="Академический">Академический</option>
                                                <option value="Креативный">Креативный</option>
                                                <option value="Технический">Технический</option>
                                                <option value="Простой">Простой</option>
                                            </select>
                                        </div>

                                        <!-- Формат -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Формат</label>
                                            <select name="format"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="Текст">Текст</option>
                                                <option value="Список">Список</option>
                                                <option value="Структурированный">Структурированный</option>
                                                <option value="Диалог">Диалог</option>
                                                <option value="Инструкция">Инструкция</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Кнопка генерации -->
                                <div class="text-center">
                                    <button type="submit"
                                        class="bg-gradient-to-r from-blue-500 to-orange-500 text-white px-8 py-3 rounded-lg font-semibold hover:from-blue-600 hover:to-orange-600 transition-all duration-200 shadow-lg hover:shadow-xl">
                                        Создать промпт
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Секция результата (скрыта по умолчанию) -->
            <div id="result-section" class="py-16 bg-violet-300 bg-opacity-30 hidden">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Ваш промпт готов!</h2>
                        <p class="text-gray-600">Вот что получилось из вашего запроса:</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Ход рассуждений -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                    Ход рассуждений
                                </h3>
                                <div id="reasoning-content" class="text-sm text-gray-600">
                                    <p>Здесь будет показан процесс анализа вашего запроса...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Уточняющие вопросы -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Уточняющие вопросы
                                </h3>
                                <ul id="questions-list" class="space-y-2 text-sm text-gray-600">
                                    <li class="flex items-start space-x-2">
                                        <span class="text-green-600 font-medium">•</span>
                                        <span>Здесь будут вопросы для уточнения деталей...</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Сгенерированный промпт -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Готовый промпт
                                </h3>
                                <div id="generated-prompt" class="text-gray-700 leading-relaxed prose prose-sm max-w-none">
                                    <p>Здесь будет отображаться сгенерированный промпт...</p>
                                </div>

                                <!-- Кнопки действий в правом нижнем углу -->
                                <div class="absolute bottom-3 right-3 flex gap-2">
                                    <!-- Кнопка копирования -->
                                    <button onclick="copyToClipboard(event)"
                                        class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-lg transition-all duration-200 hover:shadow-xl group"
                                        title="Копировать промпт">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>

                                    <!-- Кнопка отправки в Telegram -->
                                    <button onclick="sendToTelegram(event)"
                                        class="p-2 bg-blue-400 hover:bg-blue-500 text-white rounded-lg shadow-lg transition-all duration-200 hover:shadow-xl group"
                                        title="Отправить в Telegram">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                        </svg>
                                    </button>

                                    <!-- Кнопка передачи в чат-бот -->
                                    <button onclick="sendToChatBot(event)"
                                        class="p-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow-lg transition-all duration-200 hover:shadow-xl group"
                                        title="Отправить в чат-бот">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Информация о параметрах -->
                    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                            <div class="text-gray-500">Область</div>
                            <div id="selected-domain" class="font-medium text-gray-900">-</div>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                            <div class="text-gray-500">Модель</div>
                            <div id="selected-model" class="font-medium text-gray-900">-</div>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                            <div class="text-gray-500">Стиль</div>
                            <div id="selected-style" class="font-medium text-gray-900">-</div>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                            <div class="text-gray-500">Формат</div>
                            <div id="selected-format" class="font-medium text-gray-900">-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Мобильная боковая панель (скрыта по умолчанию) -->
<div id="mobile-sidebar" class="fixed inset-0 z-50 lg:hidden hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="toggleMobileSidebar()"></div>
    <div class="fixed left-0 top-0 h-full w-80 bg-white shadow-xl">
        <!-- Содержимое такое же, как в десктопной версии -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">История сессий</h2>
                <button onclick="toggleMobileSidebar()"
                    class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <!-- Здесь будет тот же список сессий -->
    </div>
</div>

<script>
// JavaScript функции для работы с сессиями
let currentSessions = [];
let currentSessionId = null;

// Загрузка сессий при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Загружаем сессии после полной загрузки DOM
    setTimeout(loadSessions, 100);
});

// Загрузка списка сессий
async function loadSessions() {
    try {
        const response = await fetch('/api/sessions', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load sessions');
        }

        const data = await response.json();

        // Проверяем, что sessions является массивом
        if (!Array.isArray(data.sessions)) {
            console.error('Sessions data is not an array:', data);
            showSessionsError();
            return;
        }

        currentSessions = data.sessions;
        renderSessions(data.sessions);

    } catch (error) {
        console.error('Error loading sessions:', error);
        showSessionsError();
    }
}

// Отображение списка сессий
function renderSessions(sessions) {
    const sessionsList = document.getElementById('sessions-list');
    const loadingElement = document.getElementById('sessions-loading');
    const emptyElement = document.getElementById('sessions-empty');

    // Проверяем, что sessions является массивом
    if (!Array.isArray(sessions)) {
        console.error('renderSessions received non-array data:', sessions);
        showSessionsError();
        return;
    }

    // Скрываем индикатор загрузки
    if (loadingElement) {
        loadingElement.classList.add('hidden');
    }

    if (sessions.length === 0) {
        // Показываем пустое состояние
        if (emptyElement) {
            emptyElement.classList.remove('hidden');
        }
        return;
    }

    // Скрываем пустое состояние
    if (emptyElement) {
        emptyElement.classList.add('hidden');
    }

    // Очищаем список
    sessionsList.innerHTML = '';

    // Добавляем сессии
    sessions.forEach((session, index) => {
        const isActive = currentSessionId === session.id || (!currentSessionId && index === 0);
        const sessionElement = createSessionElement(session, isActive);
        sessionsList.appendChild(sessionElement);
    });

    // Автоматически загружаем первую сессию только если нет текущей сессии
    if (sessions.length > 0 && !currentSessionId) {
        loadSession(sessions[0].id);
    }
}

// Создание элемента сессии
function createSessionElement(session, isActive = false) {
    const div = document.createElement('div');
    div.className = `session-item p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors ${isActive ? 'border-l-4 border-blue-500 bg-blue-50' : ''}`;
    div.onclick = () => loadSession(session.id);
    div.setAttribute('data-session-id', session.id);

    const timeAgo = formatTimeAgo(session.created_at);

    div.innerHTML = `
        <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-medium text-gray-900 truncate">${escapeHtml(session.name)}</h3>
                <p class="text-xs text-gray-500 mt-1">${escapeHtml(session.description)}</p>
                <p class="text-xs text-gray-400 mt-1">${timeAgo}</p>
            </div>
            <button onclick="event.stopPropagation(); deleteSession('${session.id}')"
                class="p-1 text-gray-400 hover:text-red-500 transition-colors"
                title="Удалить сессию">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;

    return div;
}

// Создание новой сессии
async function createNewSession() {
    try {
        const response = await fetch('/api/sessions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                name: 'Новая сессия',
                domain: 'Выберите область',
                model: 'Выберите модель',
                style: 'Профессиональный',
                format: 'Текст'
            })
        });

        if (!response.ok) {
            throw new Error('Failed to create session');
        }

        const data = await response.json();

        // Обновляем список сессий
        await loadSessions();

        // Загружаем новую сессию
        await loadSession(data.session_id);

    } catch (error) {
        console.error('Error creating session:', error);
        alert('Ошибка при создании сессии. Попробуйте еще раз.');
    }
}

// Загрузка деталей сессии
async function loadSession(sessionId) {
    try {
        // Обновляем активную сессию в UI
        updateActiveSession(sessionId);

        const response = await fetch(`/api/sessions/${sessionId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load session');
        }

        const sessionData = await response.json();
        currentSessionId = sessionId;

        // Обновляем заголовок текущей сессии
        updateSessionHeader(sessionData);

        // Загружаем последний запрос из сессии в форму
        if (sessionData.requests && sessionData.requests.length > 0) {
            const lastRequest = sessionData.requests[0];
            loadRequestIntoForm(lastRequest);
        } else {
            // Если нет запросов, очищаем форму
            clearForm();
        }

    } catch (error) {
        console.error('Error loading session:', error);
        alert('Ошибка при загрузке сессии. Попробуйте еще раз.');
    }
}

// Удаление сессии
async function deleteSession(sessionId) {
    if (!confirm('Вы уверены, что хотите удалить эту сессию? Все запросы в ней будут удалены.')) {
        return;
    }

    try {
        const response = await fetch(`/api/sessions/${sessionId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            throw new Error('Failed to delete session');
        }

        const data = await response.json();

        // Обновляем список сессий
        await loadSessions();

        // Если удалили текущую сессию, очищаем форму
        if (currentSessionId === sessionId) {
            currentSessionId = null;
            clearSessionHeader();
            clearForm();
        }

        console.log(`Session deleted: ${data.deleted_requests} requests removed`);

    } catch (error) {
        console.error('Error deleting session:', error);
        alert('Ошибка при удалении сессии. Попробуйте еще раз.');
    }
}

function toggleMobileSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    sidebar.classList.toggle('hidden');
}

function shareSession() {
    console.log('Поделиться сессией');
    // Здесь будет логика шаринга сессии
}

// Вспомогательные функции
function updateActiveSession(sessionId) {
    // Убираем активный класс со всех сессий
    document.querySelectorAll('.session-item').forEach(item => {
        item.classList.remove('border-l-4', 'border-blue-500', 'bg-blue-50');
    });

    // Добавляем активный класс к выбранной сессии
    const activeItem = document.querySelector(`[data-session-id="${sessionId}"]`);
    if (activeItem) {
        activeItem.classList.add('border-l-4', 'border-blue-500', 'bg-blue-50');
    }
}

function updateSessionHeader(sessionData) {
    const sessionTitle = document.getElementById('session-title');
    const sessionDescription = document.getElementById('session-description');
    const modeIndicator = document.getElementById('mode-indicator');

    if (sessionTitle && sessionDescription && modeIndicator) {
        // Определяем название сессии
        let sessionName = 'Текущая сессия';
        if (sessionData.requests && sessionData.requests.length > 0) {
            const firstRequest = sessionData.requests[0];
            if (firstRequest.domain && firstRequest.domain !== 'Выберите область') {
                sessionName = firstRequest.domain;
            } else if (firstRequest.original_request) {
                // Берем первые 30 символов из первого запроса
                sessionName = firstRequest.original_request.substring(0, 30) +
                             (firstRequest.original_request.length > 30 ? '...' : '');
            }
        }

        sessionTitle.textContent = sessionName;

        // Формируем описание сессии
        let description = '';
        if (sessionData.total_requests > 0) {
            description = `${sessionData.total_requests} запросов`;
            if (sessionData.domains && sessionData.domains.length > 0) {
                const uniqueDomains = [...new Set(sessionData.domains.filter(d => d && d !== 'Выберите область'))];
                if (uniqueDomains.length > 0) {
                    description += ` • ${uniqueDomains.join(', ')}`;
                }
            }
        } else {
            description = 'Создание промптов для ИИ';
        }

        sessionDescription.textContent = description;

        // Обновляем индикатор режима
        modeIndicator.textContent = 'Запрос из истории';
        modeIndicator.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
    }
}

function clearSessionHeader() {
    const sessionTitle = document.getElementById('session-title');
    const sessionDescription = document.getElementById('session-description');
    const modeIndicator = document.getElementById('mode-indicator');

    if (sessionTitle && sessionDescription && modeIndicator) {
        sessionTitle.textContent = 'Текущая сессия';
        sessionDescription.textContent = 'Создание промптов для ИИ';

        // Сбрасываем индикатор режима на "Новый запрос"
        modeIndicator.textContent = 'Новый запрос';
        modeIndicator.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
    }
}

function loadRequestIntoForm(request) {
    // Загружаем данные последнего запроса в форму
    const promptInput = document.getElementById('prompt-input');
    const domainSelect = document.querySelector('select[name="domain"]');
    const modelSelect = document.querySelector('select[name="model"]');
    const styleSelect = document.querySelector('select[name="style"]');
    const formatSelect = document.querySelector('select[name="format"]');

    if (promptInput) {
        promptInput.value = request.original_request || '';

        // Обновляем счетчик символов
        const charCount = document.getElementById('char-count');
        if (charCount) {
            charCount.textContent = promptInput.value.length;
        }
    }

    // Загружаем параметры только если они не пустые
    if (domainSelect && request.domain && request.domain !== 'Выберите область') {
        domainSelect.value = request.domain;
    }

    if (modelSelect && request.model && request.model !== 'Выберите модель') {
        modelSelect.value = request.model;
    }

    if (styleSelect && request.style && request.style !== 'Выберите стиль') {
        styleSelect.value = request.style;
    }

    if (formatSelect && request.format && request.format !== 'Выберите формат') {
        formatSelect.value = request.format;
    }

    // Показываем результат если есть сгенерированный промпт
    if (request.generated_prompt) {
        showSessionResult(request);
    } else {
        // Скрываем секцию результата если нет сгенерированного промпта
        hideResultSection();
    }
}

function clearForm() {
    const promptInput = document.getElementById('prompt-input');
    const charCount = document.getElementById('char-count');

    if (promptInput) {
        promptInput.value = '';
    }

    if (charCount) {
        charCount.textContent = '0';
    }

    // Скрываем секцию результата
    hideResultSection();

    // Переключаемся в режим "Новый запрос"
    switchToNewRequestMode();
}

function switchToNewRequestMode() {
    const modeIndicator = document.getElementById('mode-indicator');
    if (modeIndicator) {
        modeIndicator.textContent = 'Новый запрос';
        modeIndicator.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
    }
}

function showSessionResult(request) {
    // Показываем секцию результата
    const resultSection = document.getElementById('result-section');
    if (resultSection) {
        resultSection.classList.remove('hidden');
    }

    // Заполняем данные результата
    const reasoningContent = document.getElementById('reasoning-content');
    const questionsList = document.getElementById('questions-list');
    const promptContent = document.getElementById('generated-prompt');

    if (reasoningContent) {
        reasoningContent.textContent = request.reasoning || 'Ход рассуждений не доступен';
    }

    if (questionsList) {
        if (request.questions && request.questions.length > 0) {
            questionsList.innerHTML = request.questions.map(q =>
                `<li class="flex items-start space-x-2">
                    <span class="text-green-600 font-medium">•</span>
                    <span class="text-gray-700">${escapeHtml(q)}</span>
                </li>`
            ).join('');
        } else {
            questionsList.innerHTML = '<li class="text-gray-500 italic">Дополнительных вопросов нет.</li>';
        }
    }

    if (promptContent) {
        const promptParagraph = promptContent.querySelector('p');
        if (promptParagraph) {
            promptParagraph.textContent = request.generated_prompt || '';
        } else {
            promptContent.innerHTML = `<p>${escapeHtml(request.generated_prompt || '')}</p>`;
        }
    }

    // Обновляем информацию о параметрах в нижней панели
    updateParametersInfo(request);
}

function hideResultSection() {
    const resultSection = document.getElementById('result-section');
    if (resultSection) {
        resultSection.classList.add('hidden');
    }
}

function updateParametersInfo(request) {
    // Обновляем информацию о параметрах в нижней панели
    const selectedDomain = document.getElementById('selected-domain');
    const selectedModel = document.getElementById('selected-model');
    const selectedStyle = document.getElementById('selected-style');
    const selectedFormat = document.getElementById('selected-format');

    if (selectedDomain) {
        selectedDomain.textContent = (request.domain && request.domain !== 'Выберите область') ? request.domain : '-';
    }

    if (selectedModel) {
        selectedModel.textContent = (request.model && request.model !== 'Выберите модель') ? request.model : '-';
    }

    if (selectedStyle) {
        selectedStyle.textContent = (request.style && request.style !== 'Выберите стиль') ? request.style : '-';
    }

    if (selectedFormat) {
        selectedFormat.textContent = (request.format && request.format !== 'Выберите формат') ? request.format : '-';
    }
}

function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);

    if (diffInSeconds < 60) {
        return 'Только что';
    } else if (diffInSeconds < 3600) {
        const minutes = Math.floor(diffInSeconds / 60);
        return `${minutes} мин назад`;
    } else if (diffInSeconds < 86400) {
        const hours = Math.floor(diffInSeconds / 3600);
        return `${hours} ч назад`;
    } else if (diffInSeconds < 604800) {
        const days = Math.floor(diffInSeconds / 86400);
        return `${days} дн назад`;
    } else {
        return date.toLocaleDateString('ru-RU');
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showSessionsError() {
    const sessionsList = document.getElementById('sessions-list');
    const loadingElement = document.getElementById('sessions-loading');

    if (loadingElement) {
        loadingElement.classList.add('hidden');
    }

    if (sessionsList) {
        sessionsList.innerHTML = `
            <div class="p-4 text-center">
                <svg class="w-12 h-12 text-red-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <p class="text-sm text-red-500">Ошибка загрузки сессий</p>
                <button onclick="loadSessions()" class="mt-2 text-xs text-blue-500 hover:text-blue-700">
                    Попробовать снова
                </button>
            </div>
        `;
    }
}

// Функции для работы с формой генерации промптов (скопированы с главной страницы)
document.addEventListener('DOMContentLoaded', function() {
    const promptInput = document.getElementById('prompt-input');
    const charCount = document.getElementById('char-count');
    const form = document.getElementById('prompt-form');

    // Подсчет символов и переключение в режим "Новый запрос"
    if (promptInput && charCount) {
        promptInput.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;

            if (count < 10) {
                charCount.classList.add('text-red-500');
                charCount.classList.remove('text-gray-500');
            } else {
                charCount.classList.remove('text-red-500');
                charCount.classList.add('text-gray-500');
            }

            // Переключаемся в режим "Новый запрос" при изменении текста
            switchToNewRequestMode();
        });
    }

    // Обработка отправки формы
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            generatePrompt();
        });

        // Добавляем обработчики для всех селектов
        const selects = form.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                // Переключаемся в режим "Новый запрос" при изменении параметров
                switchToNewRequestMode();
            });
        });
    }
});

// Функция переключения дополнительных параметров
function toggleAdvancedOptions() {
    const optionsPanel = document.getElementById('advanced-options');
    const toggleIcon = document.getElementById('toggle-icon');

    if (optionsPanel.classList.contains('hidden')) {
        optionsPanel.classList.remove('hidden');
        toggleIcon.style.transform = 'rotate(180deg)';
    } else {
        optionsPanel.classList.add('hidden');
        toggleIcon.style.transform = 'rotate(0deg)';
    }
}

// Функция для копирования промпта в буфер обмена
function copyToClipboard(event) {
    const promptElement = document.getElementById('generated-prompt').querySelector('p');
    const promptText = promptElement.textContent || promptElement.innerText;
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;

    // Функция для обновления кнопки при успешном копировании
    function updateButtonOnSuccess() {
        button.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        `;
        button.classList.add('bg-green-500', 'hover:bg-green-600');
        button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
        button.title = 'Скопировано!';

        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('bg-green-500', 'hover:bg-green-600');
            button.classList.add('bg-blue-500', 'hover:bg-blue-600');
            button.title = 'Копировать промпт';
        }, 2000);
    }

    // Пытаемся скопировать через Clipboard API
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(promptText).then(() => {
            updateButtonOnSuccess();
        }).catch(() => {
            // Fallback к старому методу
            copyWithFallback(promptText, updateButtonOnSuccess);
        });
    } else {
        // Используем fallback метод
        copyWithFallback(promptText, updateButtonOnSuccess);
    }
}

// Fallback функция для копирования текста
function copyWithFallback(text, onSuccess) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.top = '0';
    textArea.style.left = '0';
    textArea.style.width = '2em';
    textArea.style.height = '2em';
    textArea.style.padding = '0';
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';
    textArea.style.background = 'transparent';
    textArea.style.opacity = '0';
    textArea.style.zIndex = '-1';

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        const successful = document.execCommand('copy');
        if (successful) {
            onSuccess();
        }
    } catch (err) {
        console.error('Ошибка копирования:', err);
    }

    document.body.removeChild(textArea);
}

// Функция для отправки промпта в Telegram
function sendToTelegram(event) {
    const promptElement = document.getElementById('generated-prompt').querySelector('p');
    const promptText = promptElement.textContent || promptElement.innerText;
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;

    // Кодируем промпт для передачи в URL
    const encodedPrompt = encodeURIComponent(promptText);

    // Создаем модальное окно для выбора способа отправки в Telegram
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Отправить в Telegram</h3>
            <div class="space-y-3">
                <button onclick="openTelegramWeb('${encodedPrompt}')"
                    class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-400 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="font-medium">Telegram Web</span>
                            <p class="text-sm text-gray-500">Открыть в браузере</p>
                        </div>
                    </div>
                </button>
                <button onclick="openTelegramApp('${encodedPrompt}')"
                    class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-400 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="font-medium">Telegram App</span>
                            <p class="text-sm text-gray-500">Открыть в приложении</p>
                        </div>
                    </div>
                </button>
                <button onclick="copyTelegramLink('${encodedPrompt}')"
                    class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="font-medium">Скопировать ссылку</span>
                            <p class="text-sm text-gray-500">Для вставки в любой чат</p>
                        </div>
                    </div>
                </button>
            </div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeTelegramModal()"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                    Отмена
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Функция для открытия Telegram Web
    window.openTelegramWeb = function(encodedPrompt) {
        const url = `https://web.telegram.org/k/#@?text=${encodedPrompt}`;
        window.open(url, '_blank');
        closeTelegramModal();
    };

    // Функция для открытия Telegram App
    window.openTelegramApp = function(encodedPrompt) {
        const url = `tg://msg?text=${encodedPrompt}`;
        window.location.href = url;
        closeTelegramModal();
    };

    // Функция для копирования ссылки
    window.copyTelegramLink = function(encodedPrompt) {
        const url = `https://t.me/share/url?url=&text=${encodedPrompt}`;
        navigator.clipboard.writeText(url).then(() => {
            // Показываем уведомление об успешном копировании
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            notification.textContent = 'Ссылка скопирована!';
            document.body.appendChild(notification);

            setTimeout(() => {
                document.body.removeChild(notification);
            }, 2000);
        }).catch(() => {
            // Fallback для старых браузеров
            const textArea = document.createElement('textarea');
            textArea.value = url;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
        });
        closeTelegramModal();
    };

    // Функция для закрытия модального окна
    window.closeTelegramModal = function() {
        document.body.removeChild(modal);
        delete window.openTelegramWeb;
        delete window.openTelegramApp;
        delete window.copyTelegramLink;
        delete window.closeTelegramModal;
    };

    // Закрытие по клику на фон
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            window.closeTelegramModal();
        }
    });
}

// Функция для передачи промпта в чат-бот
function sendToChatBot(event) {
    const promptElement = document.getElementById('generated-prompt').querySelector('p');
    const promptText = promptElement.textContent || promptElement.innerText;
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;

    // Кодируем промпт для передачи в URL
    const encodedPrompt = encodeURIComponent(promptText);

    // Список популярных чат-ботов с их URL-схемами
    const chatBots = {
        'chatgpt': `https://chat.openai.com/?prompt=${encodedPrompt}`,
        'claude': `https://claude.ai/?prompt=${encodedPrompt}`,
        'gemini': `https://gemini.google.com/?prompt=${encodedPrompt}`,
        'perplexity': `https://www.perplexity.ai/?q=${encodedPrompt}`
    };

    // Создаем модальное окно для выбора чат-бота
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Выберите чат-бот</h3>
            <div class="space-y-3">
                <button onclick="openChatBot('chatgpt', '${encodedPrompt}')"
                    class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white text-sm font-bold">C</span>
                        </div>
                        <span class="font-medium">ChatGPT</span>
                    </div>
                </button>
                <button onclick="openChatBot('claude', '${encodedPrompt}')"
                    class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white text-sm font-bold">C</span>
                        </div>
                        <span class="font-medium">Claude</span>
                    </div>
                </button>
                <button onclick="openChatBot('gemini', '${encodedPrompt}')"
                    class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white text-sm font-bold">G</span>
                        </div>
                        <span class="font-medium">Gemini</span>
                    </div>
                </button>
                <button onclick="openChatBot('perplexity', '${encodedPrompt}')"
                    class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white text-sm font-bold">P</span>
                        </div>
                        <span class="font-medium">Perplexity</span>
                    </div>
                </button>
            </div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeChatBotModal()"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                    Отмена
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Функция для открытия чат-бота
    window.openChatBot = function(bot, encodedPrompt) {
        const url = chatBots[bot];
        if (url) {
            window.open(url, '_blank');
        }
        closeChatBotModal();
    };

    // Функция для закрытия модального окна
    window.closeChatBotModal = function() {
        document.body.removeChild(modal);
        delete window.openChatBot;
        delete window.closeChatBotModal;
    };

    // Закрытие по клику на фон
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            window.closeChatBotModal();
        }
    });
}

// Функция для генерации промпта (заглушка - нужно будет подключить к API)
async function generatePrompt() {
    const form = document.getElementById('prompt-form');
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;

    console.log('Запуск генерации промпта');

    // Показываем индикатор загрузки
    submitButton.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>Создаем промпт...';
    submitButton.disabled = true;

    try {
        // Здесь будет реальный API вызов
        // Пока что показываем заглушку
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Показываем результат
        showResult({
            reasoning: "Анализируя ваш запрос, я определил основные темы и структуру для создания эффективного промпта.",
            questions: [
                "Какой уровень детализации вы предпочитаете?",
                "Есть ли специфические требования к формату ответа?"
            ],
            generated_prompt: "Вы - эксперт в области [тема]. Создайте [тип контента], который будет [цель]. Учтите следующие аспекты: [детали]. Предоставьте результат в формате [формат].",
            parameters: {
                domain: form.querySelector('select[name="domain"]').value,
                model: form.querySelector('select[name="model"]').value,
                style: form.querySelector('select[name="style"]').value,
                format: form.querySelector('select[name="format"]').value
            },
            request_id: 'demo_' + Date.now()
        });

    } catch (error) {
        console.error('Ошибка при генерации промпта:', error);
        alert('Произошла ошибка при генерации промпта. Попробуйте еще раз.');
    } finally {
        // Восстанавливаем кнопку
        submitButton.innerHTML = originalButtonText;
        submitButton.disabled = false;
    }
}

// Функция для отображения результата
function showResult(data) {
    // Заполняем ход рассуждений
    const reasoningContent = document.getElementById('reasoning-content').querySelector('p');
    reasoningContent.innerHTML = data.reasoning || 'Ход рассуждений не предоставлен.';

    // Заполняем уточняющие вопросы
    const questionsList = document.getElementById('questions-list');
    questionsList.innerHTML = '';

    if (data.questions && data.questions.length > 0) {
        data.questions.forEach(question => {
            const li = document.createElement('li');
            li.className = 'flex items-start space-x-2';
            li.innerHTML = `
                <span class="text-green-600 font-medium">•</span>
                <span class="text-gray-700">${question}</span>
            `;
            questionsList.appendChild(li);
        });
    } else {
        const li = document.createElement('li');
        li.className = 'text-gray-500 italic';
        li.textContent = 'Дополнительных вопросов нет.';
        questionsList.appendChild(li);
    }

    // Заполняем сгенерированный промпт
    const promptContent = document.getElementById('generated-prompt').querySelector('p');
    promptContent.innerHTML = data.generated_prompt;

    // Заполняем параметры
    document.getElementById('selected-domain').textContent = data.parameters.domain || '-';
    document.getElementById('selected-model').textContent = data.parameters.model || '-';
    document.getElementById('selected-style').textContent = data.parameters.style || '-';
    document.getElementById('selected-format').textContent = data.parameters.format || '-';

    // Сохраняем request_id для будущих уточнений
    if (data.request_id) {
        window.lastRequestId = data.request_id;
        console.log('Сохранен request_id:', data.request_id);
    }

    // Показываем секцию результата
    document.getElementById('result-section').classList.remove('hidden');

    // Плавно прокручиваем к результату
    document.getElementById('result-section').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}
</script>
@endsection
