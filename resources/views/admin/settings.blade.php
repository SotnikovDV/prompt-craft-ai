@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <!-- Левая панель навигации -->
            <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 overflow-y-auto">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900">Админ-панель</h2>
                    <p class="text-sm text-gray-500 mt-1">Управление сервисом</p>
                </div>

                <nav class="px-3 pb-6">
                    <!-- Настройки -->
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Настройки</p>
                        <a href="#" onclick="showTab('ai-settings'); return false;"
                            class="nav-tab flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors active">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            Настройки ИИ
                        </a>
                        <a href="#" onclick="showTab('llm-settings'); return false;"
                            class="nav-tab flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Настройки LLM
                        </a>
                        <a href="#" onclick="showTab('app-settings'); return false;"
                            class="nav-tab flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Приложение
                        </a>
                    </div>

                    <!-- Инструменты -->
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Инструменты</p>
                        <a href="#" onclick="showTab('tools'); return false;"
                            class="nav-tab flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            Инструменты
                        </a>
                        <a href="{{ route('admin.statistics') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Статистика
                        </a>
                        <a href="{{ route('admin.requests') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Запросы
                        </a>
                    </div>

                    <!-- Прочее -->
                    <div>
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Прочее</p>
                        <a href="{{ route('chat') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Вернуться в чат
                        </a>
                    </div>
                </nav>
            </aside>

            <!-- Основной контент -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-5xl mx-auto px-8 py-8">
                    <!-- Заголовок страницы -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900">Настройки сервиса</h1>
                        <p class="mt-2 text-gray-600">Управление конфигурацией ИИ-сервиса и системными параметрами</p>
                    </div>

            <!-- Уведомления -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <div class="text-green-800">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <div class="text-red-800">{{ session('error') }}</div>
                    </div>
                </div>
            @endif

            <!-- Форма настроек -->
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf

                <!-- Вкладка: Настройки ИИ -->
                <div id="ai-settings" class="tab-content">
                    <div class="space-y-6">
                        <!-- Настройки для гостей -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="card-header-brand px-6 py-4">
                                <h2 class="text-xl font-semibold text-gray-900">Настройки для гостей</h2>
                                <p class="mt-1 text-sm text-gray-600">Лимиты и хранение данных неавторизованных пользователей</p>
                            </div>
                            <div class="px-6 py-4 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="ai_daily_limit_guest" class="block text-sm font-medium text-gray-700 mb-2">
                                            Дневной лимит запросов
                                        </label>
                                        <input type="number" id="ai_daily_limit_guest" name="ai[daily_limit_guest]"
                                            value="{{ old('ai.daily_limit_guest', $settings['ai']['daily_limit_guest'] ?? 3) }}"
                                            min="1" max="100"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @error('ai.daily_limit_guest')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="ai_cleanup_days_guest" class="block text-sm font-medium text-gray-700 mb-2">
                                            Дни хранения запросов
                                        </label>
                                        <input type="number" id="ai_cleanup_days_guest" name="ai[cleanup_days_guest]"
                                            value="{{ old('ai.cleanup_days_guest', $settings['ai']['cleanup_days_guest'] ?? 3) }}"
                                            min="1" max="30"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @error('ai.cleanup_days_guest')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Настройки для авторизованных пользователей -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="card-header-brand px-6 py-4">
                                <h2 class="text-xl font-semibold text-gray-900">Настройки для авторизованных пользователей</h2>
                                <p class="mt-1 text-sm text-gray-600">Лимиты для зарегистрированных пользователей</p>
                            </div>
                            <div class="px-6 py-4">
                                <div>
                                    <label for="ai_daily_limit_user" class="block text-sm font-medium text-gray-700 mb-2">
                                        Дневной лимит запросов
                                        <span class="text-gray-500 font-normal">(оставьте пустым для снятия лимита)</span>
                                    </label>
                                    <input type="number" id="ai_daily_limit_user" name="ai[daily_limit_user]"
                                        value="{{ old('ai.daily_limit_user', $settings['ai']['daily_limit_user'] ?? '') }}"
                                        min="1" max="1000" placeholder="Без лимита"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('ai.daily_limit_user')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Общие настройки ИИ -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="card-header-brand px-6 py-4">
                                <h2 class="text-xl font-semibold text-gray-900">Общие настройки ИИ</h2>
                                <p class="mt-1 text-sm text-gray-600">Параметры генерации и системный промпт</p>
                            </div>
                            <div class="px-6 py-4 space-y-6">
                                <!-- Параметры генерации -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="ai_max_tokens" class="block text-sm font-medium text-gray-700 mb-2">
                                            Максимальное количество токенов
                                        </label>
                                        <input type="number" id="ai_max_tokens" name="ai[max_tokens]"
                                            value="{{ old('ai.max_tokens', $settings['ai']['max_tokens'] ?? 6000) }}" min="100"
                                            max="10000"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @error('ai.max_tokens')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="ai_temperature" class="block text-sm font-medium text-gray-700 mb-2">
                                            Температура (креативность)
                                        </label>
                                        <input type="number" id="ai_temperature" name="ai[temperature]"
                                            value="{{ old('ai.temperature', $settings['ai']['temperature'] ?? 0.5) }}"
                                            min="0" max="2" step="0.1"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @error('ai.temperature')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Системный промпт -->
                                <div>
                                    <label for="ai_system_prompt" class="block text-sm font-medium text-gray-700 mb-2">
                                        Системный промпт
                                    </label>
                                    <textarea id="ai_system_prompt" name="ai[system_prompt]" rows="15"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('ai.system_prompt', $settings['ai']['system_prompt'] ?? '') }}</textarea>
                                    @error('ai.system_prompt')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопка сохранения -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium shadow-sm">
                            Сохранить настройки ИИ
                        </button>
                    </div>
                </div>

                <!-- Вкладка: Настройки LLM -->
                <div id="llm-settings" class="tab-content hidden">
                    <div class="space-y-6">
                        <!-- Общие настройки LLM -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="card-header-brand px-6 py-4">
                                <h2 class="text-xl font-semibold text-gray-900">Общие настройки LLM</h2>
                                <p class="mt-1 text-sm text-gray-600">Выбор активного провайдера языковой модели</p>
                            </div>
                            <div class="px-6 py-4">
                                <div>
                                    <label for="llm_default" class="block text-sm font-medium text-gray-700 mb-2">
                                        Провайдер LLM по умолчанию
                                    </label>
                                    <select id="llm_default" name="llm[defaultLLM]"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="perplexity"
                                            {{ old('llm.defaultLLM', $settings['llm']['defaultLLM'] ?? '') == 'perplexity' ? 'selected' : '' }}>
                                            Perplexity
                                        </option>
                                        <option value="openrouter"
                                            {{ old('llm.defaultLLM', $settings['llm']['defaultLLM'] ?? '') == 'openrouter' ? 'selected' : '' }}>
                                            OpenRouter
                                        </option>
                                        <option value="yandex"
                                            {{ old('llm.defaultLLM', $settings['llm']['defaultLLM'] ?? '') == 'yandex' ? 'selected' : '' }}>
                                            Yandex LLM
                                        </option>
                                    </select>
                                    @error('llm.defaultLLM')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Настройки Perplexity -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="card-header-brand px-6 py-4">
                                <h2 class="text-xl font-semibold text-gray-900">Настройки Perplexity</h2>
                                <p class="mt-1 text-sm text-gray-600">Конфигурация подключения к Perplexity AI</p>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <!-- Информационный блок -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-sm text-blue-800">
                                            <p class="font-semibold mb-1">API ключи редактируются в .env файле</p>
                                            <p>Для изменения ключей отредактируйте переменные <code class="bg-blue-100 px-1 py-0.5 rounded">PERPLEXITY_API_KEY</code> в файле <code class="bg-blue-100 px-1 py-0.5 rounded">.env</code></p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="llm_perplexity_api_key"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        API ключ <span class="text-gray-500 font-normal">(только для чтения)</span>
                                    </label>
                                    <input type="text" id="llm_perplexity_api_key"
                                        value="{{ $settings['api_keys']['perplexity'] ?: 'Не установлен' }}"
                                        readonly
                                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
                                    <p class="mt-1 text-xs text-gray-500">Редактируется в .env файле (PERPLEXITY_API_KEY)</p>
                                </div>

                                <div>
                                    <label for="llm_perplexity_base_url"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Базовый URL
                                    </label>
                                    <input type="url" id="llm_perplexity_base_url" name="llm[perplexity][base_url]"
                                        value="{{ old('llm.perplexity.base_url', $settings['llm']['perplexity']['base_url'] ?? 'https://api.perplexity.ai') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('llm.perplexity.base_url')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="llm_perplexity_default_model"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Модель по умолчанию
                                    </label>
                                    <select id="llm_perplexity_default_model" name="llm[perplexity][defaultModel]"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @foreach ($settings['llm']['perplexity']['models'] ?? [] as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ old('llm.perplexity.defaultModel', $settings['llm']['perplexity']['defaultModel'] ?? '') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('llm.perplexity.defaultModel')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Настройки OpenRouter -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="card-header-brand px-6 py-4">
                                <h2 class="text-xl font-semibold text-gray-900">Настройки OpenRouter</h2>
                                <p class="mt-1 text-sm text-gray-600">Конфигурация подключения к OpenRouter</p>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <!-- Информационный блок -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-sm text-blue-800">
                                            <p class="font-semibold mb-1">API ключи редактируются в .env файле</p>
                                            <p>Для изменения ключей отредактируйте переменные <code class="bg-blue-100 px-1 py-0.5 rounded">OPENROUTER_API_KEY</code> в файле <code class="bg-blue-100 px-1 py-0.5 rounded">.env</code></p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="llm_openrouter_api_key"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        API ключ <span class="text-gray-500 font-normal">(только для чтения)</span>
                                    </label>
                                    <input type="text" id="llm_openrouter_api_key"
                                        value="{{ $settings['api_keys']['openrouter'] ?: 'Не установлен' }}"
                                        readonly
                                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
                                    <p class="mt-1 text-xs text-gray-500">Редактируется в .env файле (OPENROUTER_API_KEY)</p>
                                </div>

                                <div>
                                    <label for="llm_openrouter_base_url"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Базовый URL
                                    </label>
                                    <input type="url" id="llm_openrouter_base_url" name="llm[openrouter][base_url]"
                                        value="{{ old('llm.openrouter.base_url', $settings['llm']['openrouter']['base_url'] ?? 'https://openrouter.ai/api/v1') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('llm.openrouter.base_url')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="llm_openrouter_default_model"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Модель по умолчанию
                                    </label>
                                    <select id="llm_openrouter_default_model" name="llm[openrouter][defaultModel]"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @foreach ($settings['llm']['openrouter']['models'] ?? [] as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ old('llm.openrouter.defaultModel', $settings['llm']['openrouter']['defaultModel'] ?? '') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('llm.openrouter.defaultModel')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Настройки Yandex LLM -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="card-header-brand px-6 py-4">
                                <h2 class="text-xl font-semibold text-gray-900">Настройки Yandex LLM</h2>
                                <p class="mt-1 text-sm text-gray-600">Конфигурация подключения к Yandex Cloud AI</p>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <!-- Информационный блок -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-sm text-blue-800">
                                            <p class="font-semibold mb-1">API ключи редактируются в .env файле</p>
                                            <p>Для изменения ключей отредактируйте переменные <code class="bg-blue-100 px-1 py-0.5 rounded">YANDEX_LLM_API_KEY</code> и <code class="bg-blue-100 px-1 py-0.5 rounded">YANDEX_FOLDER_ID</code> в файле <code class="bg-blue-100 px-1 py-0.5 rounded">.env</code></p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="llm_yandex_api_key"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        API ключ <span class="text-gray-500 font-normal">(только для чтения)</span>
                                    </label>
                                    <input type="text" id="llm_yandex_api_key"
                                        value="{{ $settings['api_keys']['yandex'] ?: 'Не установлен' }}"
                                        readonly
                                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
                                    <p class="mt-1 text-xs text-gray-500">Редактируется в .env файле (YANDEX_LLM_API_KEY)</p>
                                </div>

                                <div>
                                    <label for="llm_yandex_folder_id"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Folder ID <span class="text-gray-500 font-normal">(только для чтения)</span>
                                    </label>
                                    <input type="text" id="llm_yandex_folder_id"
                                        value="{{ $settings['api_keys']['yandex_folder_id'] ?: 'Не установлен' }}"
                                        readonly
                                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
                                    <p class="mt-1 text-xs text-gray-500">Редактируется в .env файле (YANDEX_FOLDER_ID)</p>
                                </div>

                                <div>
                                    <label for="llm_yandex_base_url"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Базовый URL
                                    </label>
                                    <input type="url" id="llm_yandex_base_url" name="llm[yandex][base_url]"
                                        value="{{ old('llm.yandex.base_url', $settings['llm']['yandex']['base_url'] ?? 'https://llm.api.cloud.yandex.net') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('llm.yandex.base_url')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="llm_yandex_default_model"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Модель по умолчанию
                                    </label>
                                    <select id="llm_yandex_default_model" name="llm[yandex][defaultModel]"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @foreach ($settings['llm']['yandex']['models'] ?? [] as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ old('llm.yandex.defaultModel', $settings['llm']['yandex']['defaultModel'] ?? '') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('llm.yandex.defaultModel')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопка сохранения -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium shadow-sm">
                            Сохранить настройки LLM
                        </button>
                    </div>
                </div>

                <!-- Вкладка: Настройки приложения -->
                <div id="app-settings" class="tab-content hidden">
                    <!-- Настройки приложения -->
                <div class="bg-white shadow rounded-lg">
                    <div class="card-header-brand px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-900">Настройки приложения</h2>
                        <p class="mt-1 text-sm text-gray-600">Общие параметры системы</p>
                    </div>
                    <div class="px-6 py-4 space-y-6">
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Название приложения
                            </label>
                            <input type="text" id="app_name" name="app[name]"
                                value="{{ old('app.name', $settings['app']['name'] ?? '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('app.name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email адреса администраторов
                            </label>
                            <div id="admin-emails-container">
                                @foreach (old('app.admin_emails', $settings['app']['admin_emails'] ?? ['admin@example.com']) as $index => $email)
                                    <div class="flex items-center mb-2">
                                        <input type="email" name="app[admin_emails][]" value="{{ $email }}"
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @if ($index > 0)
                                            <button type="button" onclick="removeAdminEmail(this)"
                                                class="ml-2 p-2 text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addAdminEmail()"
                                class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                Добавить администратора
                            </button>
                            @error('app.admin_emails')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    </div>

                    <!-- Кнопка сохранения -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium shadow-sm">
                            Сохранить настройки приложения
                        </button>
                    </div>
                </div>
            </form>

            <!-- Вкладка: Инструменты -->
            <div id="tools" class="tab-content hidden">
                <div class="space-y-6">
                    <!-- Управление кэшем -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="card-header-brand px-6 py-4">
                            <h2 class="text-xl font-semibold text-gray-900">Управление кэшем</h2>
                            <p class="mt-1 text-sm text-gray-600">Очистка различных типов кэша приложения</p>
                        </div>
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button type="button" onclick="clearCache('config')"
                                    class="flex items-center justify-between px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors border border-blue-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            </svg>
                                        <span class="font-medium">Очистить кэш конфигурации</span>
                                    </div>
                                </button>

                                <button type="button" onclick="clearCache('view')"
                                    class="flex items-center justify-between px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors border border-purple-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span class="font-medium">Очистить кэш представлений</span>
                                    </div>
                                </button>

                                <button type="button" onclick="clearCache('route')"
                                    class="flex items-center justify-between px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors border border-green-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                        <span class="font-medium">Очистить кэш маршрутов</span>
                                    </div>
                                </button>

                                <button type="button" onclick="clearCache('all')"
                                    class="flex items-center justify-between px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors border border-red-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span class="font-medium">Очистить весь кэш</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Управление логами -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="card-header-brand px-6 py-4">
                            <h2 class="text-xl font-semibold text-gray-900">Управление логами</h2>
                            <p class="mt-1 text-sm text-gray-600">Просмотр и очистка логов приложения</p>
                        </div>
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <a href="{{ route('admin.requests') }}"
                                    class="flex items-center justify-between px-4 py-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="font-medium">Просмотр логов запросов</span>
                                    </div>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>

                                <button type="button" onclick="clearLogs()"
                                    class="flex items-center justify-between px-4 py-3 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition-colors border border-orange-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span class="font-medium">Очистить все логи</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Системная информация -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="card-header-brand px-6 py-4">
                            <h2 class="text-xl font-semibold text-gray-900">Системная информация</h2>
                            <p class="mt-1 text-sm text-gray-600">Информация о сервере и приложении</p>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Версия PHP</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ PHP_VERSION }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Версия Laravel</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ app()->version() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Окружение</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ config('app.env') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Debug режим</dt>
                                    <dd class="mt-1 text-sm {{ config('app.debug') ? 'text-orange-600' : 'text-green-600' }} font-semibold">
                                        {{ config('app.debug') ? 'Включен' : 'Выключен' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </main>
        </div>
    </div>

    <style>
        .nav-tab.active {
            background-color: rgb(243 244 246);
            color: rgb(29 78 216);
            font-weight: 600;
        }
    </style>

    <script>
        // Переключение вкладок
        function showTab(tabId) {
            // Скрываем все вкладки
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Показываем нужную вкладку
            document.getElementById(tabId).classList.remove('hidden');

            // Обновляем активный пункт меню
            document.querySelectorAll('.nav-tab').forEach(link => {
                link.classList.remove('active');
            });
            event.target.closest('.nav-tab').classList.add('active');
        }

        // Очистка кэша
        async function clearCache(type) {
            try {
                const response = await fetch('/admin/clear-cache', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ type })
                });

                const data = await response.json();

                if (data.success) {
                    alert('✓ Кэш успешно очищен!');
                } else {
                    alert('✗ Ошибка при очистке кэша: ' + (data.message || 'Неизвестная ошибка'));
                }
            } catch (error) {
                alert('✗ Ошибка: ' + error.message);
            }
        }

        // Очистка логов
        async function clearLogs() {
            if (!confirm('Вы уверены, что хотите очистить все логи?')) {
                return;
            }

            try {
                const response = await fetch('/admin/clear-logs', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('✓ Логи успешно очищены!');
                } else {
                    alert('✗ Ошибка при очистке логов: ' + (data.message || 'Неизвестная ошибка'));
                }
            } catch (error) {
                alert('✗ Ошибка: ' + error.message);
            }
        }

        // Функции для управления email адресами администраторов
        function addAdminEmail() {
            const container = document.getElementById('admin-emails-container');
            const div = document.createElement('div');
            div.className = 'flex items-center mb-2';
            div.innerHTML = `
        <input type="email"
               name="app[admin_emails][]"
               placeholder="admin@example.com"
               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="button"
                onclick="removeAdminEmail(this)"
                class="ml-2 p-2 text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;
            container.appendChild(div);
        }

        function removeAdminEmail(button) {
            button.parentElement.remove();
        }
    </script>
@endsection
