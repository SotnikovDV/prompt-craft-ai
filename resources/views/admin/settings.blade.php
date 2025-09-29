@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Заголовок страницы -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Настройки сервиса</h1>
            <p class="mt-2 text-gray-600">Управление конфигурацией ИИ-сервиса и системными параметрами</p>
        </div>

        <!-- Уведомления -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <div class="text-green-800">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <div class="text-red-800">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        <!-- Форма настроек -->
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8">
            @csrf

            <!-- Настройки ИИ -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Настройки ИИ</h2>
                    <p class="mt-1 text-sm text-gray-600">Параметры генерации промптов и лимиты запросов</p>
                </div>
                <div class="px-6 py-4 space-y-6">
                    <!-- Лимиты запросов -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ai_daily_limit_guest" class="block text-sm font-medium text-gray-700 mb-2">
                                Дневной лимит для гостей
                            </label>
                            <input type="number"
                                   id="ai_daily_limit_guest"
                                   name="ai[daily_limit_guest]"
                                   value="{{ old('ai.daily_limit_guest', $settings['ai']['daily_limit_guest'] ?? 3) }}"
                                   min="1" max="100"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('ai.daily_limit_guest')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ai_daily_limit_user" class="block text-sm font-medium text-gray-700 mb-2">
                                Дневной лимит для пользователей (null = без лимита)
                            </label>
                            <input type="number"
                                   id="ai_daily_limit_user"
                                   name="ai[daily_limit_user]"
                                   value="{{ old('ai.daily_limit_user', $settings['ai']['daily_limit_user'] ?? '') }}"
                                   min="1" max="1000"
                                   placeholder="Оставьте пустым для снятия лимита"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('ai.daily_limit_user')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Параметры генерации -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ai_max_tokens" class="block text-sm font-medium text-gray-700 mb-2">
                                Максимальное количество токенов
                            </label>
                            <input type="number"
                                   id="ai_max_tokens"
                                   name="ai[max_tokens]"
                                   value="{{ old('ai.max_tokens', $settings['ai']['max_tokens'] ?? 6000) }}"
                                   min="100" max="10000"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('ai.max_tokens')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ai_temperature" class="block text-sm font-medium text-gray-700 mb-2">
                                Температура (креативность)
                            </label>
                            <input type="number"
                                   id="ai_temperature"
                                   name="ai[temperature]"
                                   value="{{ old('ai.temperature', $settings['ai']['temperature'] ?? 0.5) }}"
                                   min="0" max="2" step="0.1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('ai.temperature')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Очистка данных -->
                    <div>
                        <label for="ai_cleanup_days_guest" class="block text-sm font-medium text-gray-700 mb-2">
                            Дни хранения запросов гостей
                        </label>
                        <input type="number"
                               id="ai_cleanup_days_guest"
                               name="ai[cleanup_days_guest]"
                               value="{{ old('ai.cleanup_days_guest', $settings['ai']['cleanup_days_guest'] ?? 3) }}"
                               min="1" max="30"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('ai.cleanup_days_guest')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Системный промпт -->
                    <div>
                        <label for="ai_system_prompt" class="block text-sm font-medium text-gray-700 mb-2">
                            Системный промпт
                        </label>
                        <textarea id="ai_system_prompt"
                                  name="ai[system_prompt]"
                                  rows="10"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('ai.system_prompt', $settings['ai']['system_prompt'] ?? '') }}</textarea>
                        @error('ai.system_prompt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Настройки LLM -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Настройки LLM</h2>
                    <p class="mt-1 text-sm text-gray-600">Конфигурация подключения к языковым моделям</p>
                </div>
                <div class="px-6 py-4 space-y-6">
                    <div>
                        <label for="llm_perplexity_api_key" class="block text-sm font-medium text-gray-700 mb-2">
                            API ключ Perplexity
                        </label>
                        <input type="password"
                               id="llm_perplexity_api_key"
                               name="llm[perplexity][api_key]"
                               value="{{ old('llm.perplexity.api_key', $settings['llm']['perplexity']['api_key'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('llm.perplexity.api_key')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="llm_perplexity_base_url" class="block text-sm font-medium text-gray-700 mb-2">
                            Базовый URL Perplexity
                        </label>
                        <input type="url"
                               id="llm_perplexity_base_url"
                               name="llm[perplexity][base_url]"
                               value="{{ old('llm.perplexity.base_url', $settings['llm']['perplexity']['base_url'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('llm.perplexity.base_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Настройки приложения -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Настройки приложения</h2>
                    <p class="mt-1 text-sm text-gray-600">Общие параметры системы</p>
                </div>
                <div class="px-6 py-4 space-y-6">
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Название приложения
                        </label>
                        <input type="text"
                               id="app_name"
                               name="app[name]"
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
                            @foreach(old('app.admin_emails', $settings['app']['admin_emails'] ?? ['admin@example.com']) as $index => $email)
                                <div class="flex items-center mb-2">
                                    <input type="email"
                                           name="app[admin_emails][]"
                                           value="{{ $email }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @if($index > 0)
                                        <button type="button"
                                                onclick="removeAdminEmail(this)"
                                                class="ml-2 p-2 text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <button type="button"
                                onclick="addAdminEmail()"
                                class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            Добавить администратора
                        </button>
                        @error('app.admin_emails')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('chat') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Отмена
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Сохранить настройки
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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
