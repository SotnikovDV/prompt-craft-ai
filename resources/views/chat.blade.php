@extends('layouts.chatapp')

@section('content')
<!-- Дополнительные стили для форматирования -->
<style>
    .formatted-content h1,
    .formatted-content h2,
    .formatted-content h3,
    .formatted-content h4 {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }

    .formatted-content ul,
    .formatted-content ol {
        margin: 0.75rem 0;
        padding-left: 1.5rem;
    }

    .formatted-content li {
        margin: 0.25rem 0;
    }

    .formatted-content blockquote {
        margin: 1rem 0;
        padding-left: 1rem;
        border-left: 4px solid #d1d5db;
    }

    .formatted-content code {
        font-family: 'Courier New', monospace;
        background-color: #f3f4f6;
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }

    .formatted-content hr {
        margin: 1.5rem 0;
        border: none;
        border-top: 1px solid #d1d5db;
    }

    .formatted-content a {
        color: #2563eb;
        text-decoration: underline;
    }

    .formatted-content a:hover {
        color: #1d4ed8;
    }

    .formatted-content strong {
        font-weight: 600;
        color: #111827;
    }

    .formatted-content em {
        font-style: italic;
        color: #374151;
    }

    .formatted-content {
        font-weight: normal !important;
    }

    .formatted-content p {
        font-weight: normal !important;
    }

    .formatted-content h1,
    .formatted-content h2,
    .formatted-content h3,
    .formatted-content h4 {
        color: #1e40af !important;
        border-left: 4px solid #3b82f6 !important;
        padding-left: 12px !important;
    }

    .formatted-content ul,
    .formatted-content ol {
        margin: 16px 0 !important;
        padding-left: 24px !important;
    }

    .formatted-content li {
        margin: 8px 0 !important;
        line-height: 1.6 !important;
    }

    .formatted-content blockquote {
        background: #f8fafc !important;
        border-left: 4px solid #e2e8f0 !important;
        padding: 16px !important;
        margin: 16px 0 !important;
        border-radius: 6px !important;
    }

    .formatted-content code {
        background: #f1f5f9 !important;
        color: #0f172a !important;
        padding: 4px 8px !important;
        border-radius: 4px !important;
        font-size: 0.9em !important;
    }
</style>
    <!-- Скрытый элемент с инициалом пользователя для JavaScript -->
    <div id="user-initial" class="hidden">{{ substr(Auth::user()->name ?? Auth::user()->email, 0, 1) }}</div>
    <!-- Заголовок чата -->
    <header
        class="md:absolute md:top-0 md:left-0 md:right-0 md:z-20 p-3 bg-white/90 border-b border-gray-200 md:bg-transparent md:border-none">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <!-- Логотип для мобильных (заменяет гамбургер) -->
                <button onclick="toggleSidebar()"
                    class="md:hidden flex items-center space-x-2 p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                    title="Открыть меню">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="p-1 lucide lucide-sparkles h-8 w-8 rounded-xl bg-gradient-to-br from-blue-500 to-orange-500 text-blue-700">
                            <path
                                d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z">
                            </path>
                            <path d="M20 3v4"></path>
                            <path d="M22 5h-4"></path>
                            <path d="M4 17v2"></path>
                            <path d="M5 18H3"></path>
                        </svg>
                    </div>
                    {{-- <span class="text-sm font-medium">Толкователь ИИ</span> --}}
                    <span class="hidden md:block text-xl font-bold text-gradient-hero">Толкователь
                        ИИ</span>
                </button>
                {{-- <div>

                    </div>
                     --}}
                <!-- Информация о провайдере LLM (только для десктопа) -->
                <div class="text-left ml-2 bg-transparent">
                    <div class="text-lg font-bold text-gray-800">
                        {{ ucfirst(config('llm.defaultLLM')) }}
                    </div>
                    <div class="text-sm text-gray-600">
                        @php
                            $provider = config('llm.defaultLLM');
                            $modelKey = config("llm.{$provider}.defaultModel");
                            $models = config("llm.{$provider}.models", []);
                            $modelName = $models[$modelKey] ?? $modelKey;
                        @endphp
                        {{ $modelName }}
                    </div>
                </div>
            </div>



            <div class="flex items-center space-x-2 mr-2 ">
                <button onclick="deleteChat()"
                    class="p-2 text-gray-500 hover:text-red-600 hover:bg-white/20 rounded-lg transition-colors"
                    title="Удалить чат">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
                <button onclick="shareChat()"
                    class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white/20 rounded-lg transition-colors"
                    title="Поделиться чатом">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Область чата -->
    <div class="flex-1 overflow-y-auto bg-white bg-opacity-10 md:pt-16 md:pb-24">
        <div class="max-w-sm md:max-w-4xl mx-auto px-1 md:px-0">
            <!-- Приветственное сообщение -->
            <x-chat.welcome-message />

            <!-- История сообщений чата -->
            <div id="chat-messages" class="space-y-6 px-6 pt-6 pb-12 hidden">
                <!-- Сообщения будут добавляться динамически -->
            </div>
        </div>
    </div>

    <!-- Поле ввода внизу страницы -->
    <div class="md:absolute md:bottom-0 md:left-0 md:right-0 md:z-20 backdrop-blur-sm bg-transparent p-4">
        <div class="max-w-sm md:max-w-3xl mx-auto px-1 md:px-0">
            <div class="relative flex items-center">
                <textarea id="message-input" placeholder="Напишите ваш запрос..."
                    class="w-full px-4 pt-3 pb-8 pr-20 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                    rows="2" maxlength="3000"></textarea>
                <button id="send-button" onclick="sendMessage()"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
                <!-- Кнопки дополнительных параметров -->
                <div class="absolute bottom-1 left-6 flex flex-wrap gap-3">
                    <!-- Кнопка "Область знаний" -->
                    <div class="relative">
                        <button id="domain-button" onclick="toggleDropdown('domain')"
                            class="flex items-center px-1 py-1 text-sm bg-white rounded-xl hover:bg-gray-100 transition-colors"
                            title="Область знаний">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span id="domain-text" class="hidden">Область</span>
                        </button>
                        <div id="domain-dropdown"
                            class="hidden absolute bottom-full left-0 mb-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <div class="py-1">
                                <div class="px-3 py-2 text-xs text-gray-500 border-b">Область знаний</div>
                                <button onclick="selectOption('domain', 'Выберите область')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Выберите область</span>
                                </button>
                                <button onclick="selectOption('domain', 'Программирование')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Программирование</span>
                                </button>
                                <button onclick="selectOption('domain', 'Технологии')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Технологии</span>
                                </button>
                                <button onclick="selectOption('domain', 'Маркетинг')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Маркетинг</span>
                                </button>
                                <button onclick="selectOption('domain', 'Образование')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Образование</span>
                                </button>
                                <button onclick="selectOption('domain', 'Медицина')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Медицина</span>
                                </button>
                                <button onclick="selectOption('domain', 'Финансы')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Финансы</span>
                                </button>
                                <button onclick="selectOption('domain', 'Дизайн')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Дизайн</span>
                                </button>
                                <button onclick="selectOption('domain', 'Писательство')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Писательство</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопка "Модель" -->
                    <div class="relative">
                        <button id="model-button" onclick="toggleDropdown('model')"
                            class="flex items-center px-1 py-1 text-sm bg-white rounded-xl hover:bg-gray-100 transition-colors"
                            title="Модель ИИ">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span id="model-text" class="hidden">Модель</span>
                        </button>
                        <div id="model-dropdown"
                            class="hidden absolute bottom-full left-0 mb-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <div class="py-1">
                                <div class="px-3 py-2 text-xs text-gray-500 border-b">Целевая модель</div>
                                <button onclick="selectOption('model', 'Выберите модель')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Выберите модель</span>
                                </button>
                                <button onclick="selectOption('model', 'ChatGPT-4')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>ChatGPT-4</span>
                                </button>
                                <button onclick="selectOption('model', 'ChatGPT-3.5')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>ChatGPT-3.5</span>
                                </button>
                                <button onclick="selectOption('model', 'Claude')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Claude</span>
                                </button>
                                <button onclick="selectOption('model', 'Gemini')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Gemini</span>
                                </button>
                                <button onclick="selectOption('model', 'Llama')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Llama</span>
                                </button>
                                <button onclick="selectOption('model', 'Perplexity')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Perplexity</span>
                                </button>
                                <button onclick="selectOption('model', 'Универсальный')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Универсальный</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопка "Стиль" -->
                    <div class="relative">
                        <button id="style-button" onclick="toggleDropdown('style')"
                            class="flex items-center px-1 py-1 text-sm bg-white rounded-xl hover:bg-gray-100 transition-colors"
                            title="Стиль промпта">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
                            </svg>
                            <span id="style-text" class="hidden">Стиль</span>
                        </button>
                        <div id="style-dropdown"
                            class="hidden absolute bottom-full left-0 mb-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <div class="py-1">
                                <div class="px-3 py-2 text-xs text-gray-500 border-b">Стиль промпта</div>
                                <button onclick="selectOption('style', 'Выберите стиль')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Выберите стиль</span>
                                </button>
                                <button onclick="selectOption('style', 'Формальный')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Формальный</span>
                                </button>
                                <button onclick="selectOption('style', 'Дружелюбный')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Дружелюбный</span>
                                </button>
                                <button onclick="selectOption('style', 'Технический')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Технический</span>
                                </button>
                                <button onclick="selectOption('style', 'Креативный')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Креативный</span>
                                </button>
                                <button onclick="selectOption('style', 'Академический')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Академический</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопка "Формат" -->
                    <div class="relative">
                        <button id="format-button" onclick="toggleDropdown('format')"
                            class="flex items-center px-1 py-1 text-sm bg-white rounded-xl hover:bg-gray-100 transition-colors"
                            title="Формат результата">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span id="format-text" class="hidden">Формат</span>
                        </button>
                        <div id="format-dropdown"
                            class="hidden absolute bottom-full left-0 mb-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <div class="py-1">
                                <div class="px-3 py-2 text-xs text-gray-500 border-b">Формат результата</div>
                                <button onclick="selectOption('format', 'Выберите формат')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Выберите формат</span>
                                </button>
                                <button onclick="selectOption('format', 'Текст')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Текст</span>
                                </button>
                                <button onclick="selectOption('format', 'Список')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Список</span>
                                </button>
                                <button onclick="selectOption('format', 'Таблица')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Таблица</span>
                                </button>
                                <button onclick="selectOption('format', 'JSON')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>JSON</span>
                                </button>
                                <button onclick="selectOption('format', 'Markdown')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Markdown</span>
                                </button>
                                <button onclick="selectOption('format', 'Изображение')"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 hidden mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Изображение</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center items-center mt-1 text-sm text-gray-400">
                <span id="char-count">0/3000</span>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Инициализация ChatDisplayManager
        let chatDisplayManager = null;
        let isContinuationMode = false;
        let lastUserMessage = '';
        // currentChatId уже объявлен в chatapp.blade.php

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            if (window.ChatDisplayManager) {
                chatDisplayManager = new window.ChatDisplayManager();
                window.chatDisplayManager = chatDisplayManager;
            }

            // Загружаем чат по ID, если он передан в URL
            @if (isset($chatId))
                currentChatId = '{{ $chatId }}'; // Устанавливаем ID чата из URL
                loadChatFromUrl('{{ $chatId }}');
            @endif
        });

        // Отправка сообщения
        async function sendMessage() {
            console.log('sendMessage вызвана');
            const messageInput = document.getElementById('message-input');
            const sendButton = document.getElementById('send-button');

            if (!messageInput || isGenerating) {
                console.log('Выход из функции: messageInput =', !!messageInput, 'isGenerating =', isGenerating);
                return;
            }

            const message = messageInput.value.trim();
            if (message.length < 10) {
                if (window.infoModalManager) {
                    window.infoModalManager.showWarning('Слишком короткий запрос',
                        'Минимальная длина запроса - 10 символов');
                }
                return;
            }

            // Определяем режим: продолжение или новое сообщение
            let finalMessage = message;
            if (isContinuationMode && lastUserMessage) {
                // В режиме продолжения дописываем к последнему сообщению
                finalMessage = lastUserMessage + ' ' + message;
                console.log('Режим продолжения: объединяем сообщения:', finalMessage);
            } else {
                // Новое сообщение
                lastUserMessage = message;
                console.log('Новое сообщение:', message);
            }

            isGenerating = true;
            sendButton.disabled = true;

            // Очищаем поле ввода
            messageInput.value = '';
            messageInput.style.height = 'auto';

            // Сбрасываем счетчик символов
            const charCount = document.getElementById('char-count');
            if (charCount) {
                charCount.textContent = '0/3000';
            }

            // Сразу отображаем сообщение пользователя
            displayUserMessageImmediately(message);

            // Добавляем индикатор "ИИ печатает..."
            const loadingId = addTypingIndicator();

            try {
                // Получаем значения дополнительных параметров из кнопок
                const domain = getSelectedValue('domain') || 'Выберите область';
                const model = getSelectedValue('model') || 'Выберите модель';
                const style = getSelectedValue('style') || 'Выберите стиль';
                const format = getSelectedValue('format') || 'Выберите формат';

                console.log('Отправляем запрос на /chat/generate-prompt с данными:', {
                    prompt: message,
                    domain: domain,
                    model: model,
                    style: style,
                    format: format,
                    session_id: currentChatId
                });

                const response = await fetch('/chat/generate-prompt', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        prompt: message,
                        domain: domain,
                        model: model,
                        style: style,
                        format: format,
                        session_id: currentChatId // Передаем ID текущей сессии
                    })
                });

                if (!response.ok) {
                    // Получаем тело ответа для более детальной ошибки
                    let errorData;
                    try {
                        errorData = await response.json();
                    } catch (e) {
                        errorData = {
                            error: `HTTP error! status: ${response.status}`
                        };
                    }

                    console.error('Ошибка от сервера:', {
                        status: response.status,
                        data: errorData
                    });

                    if (response.status === 429) {
                        throw new Error('Превышен дневной лимит запросов');
                    }

                    // Используем сообщение об ошибке из ответа сервера, если оно есть
                    throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Успешный ответ от сервера:', data);

                // Удаляем индикатор "ИИ печатает..."
                removeTypingIndicator(loadingId);

                if (data.success) {
                    // Обновляем currentChatId если создалась новая сессия
                    if (data.session_id && !currentChatId) {
                        currentChatId = data.session_id;
                        // Обновляем URL без перезагрузки страницы
                        window.history.pushState({}, '', `/chat/${data.session_id}`);
                    }

                    // Добавляем только ответ ИИ (сообщение пользователя уже отображено)
                    console.log('chatDisplayManager:', chatDisplayManager);
                    console.log('Данные для отображения:', data);
                    if (chatDisplayManager) {
                        console.log('Вызываем addAIResponse');
                        if (typeof chatDisplayManager.addAIResponse === 'function') {
                            chatDisplayManager.addAIResponse(data);
                        } else {
                            console.error('addAIResponse не является функцией!');
                            // Временное решение - отображаем только ответ ИИ
                            displayAIResponseDirectly(data);
                        }
                    } else {
                        console.error('chatDisplayManager не инициализирован!');
                        // Временное решение - отображаем только ответ ИИ
                        displayAIResponseDirectly(data);
                    }

                    // Обновляем историю чатов
                    if (window.chatHistoryManager) {
                        window.chatHistoryManager.loadChatHistory();
                    }
                } else {
                    throw new Error(data.error || 'Неизвестная ошибка');
                }

            } catch (error) {
                console.error('Ошибка при отправке сообщения:', error);

                // Удаляем индикатор "ИИ печатает..."
                removeTypingIndicator(loadingId);

                // Определяем тип ошибки и показываем соответствующее сообщение
                if (window.infoModalManager) {
                    // Получаем данные об ошибке из ответа сервера
                    let errorData = null;
                    try {
                        if (error.response && error.response.data) {
                            errorData = error.response.data;
                        }
                    } catch (e) {
                        console.log('Не удалось получить данные об ошибке из ответа');
                    }

                    const errorType = errorData?.error_type || 'general';
                    const errorMessage = errorData?.error || error.message ||
                        'Произошла неизвестная ошибка. Попробуйте позже.';

                    // Показываем соответствующее сообщение в зависимости от типа ошибки
                    switch (errorType) {
                        case 'rate_limit':
                            window.infoModalManager.showWarning(
                                'Превышен лимит запросов',
                                errorMessage, {
                                    confirmText: 'Понятно'
                                }
                            );
                            break;
                        case 'auth':
                        case 'forbidden':
                            window.infoModalManager.showError(
                                'Ошибка авторизации API',
                                `<div class="space-y-3">
                                    <p>${errorMessage}</p>
                                    <p class="text-sm font-medium">Возможные причины:</p>
                                    <ul class="list-disc list-inside space-y-1 text-sm">
                                        <li>API ключ истек или недействителен</li>
                                        <li>API ключ не активирован</li>
                                        <li>Недостаточно средств на аккаунте поставщика LLM</li>
                                    </ul>
                                    <p class="text-sm font-medium mt-3">Для администратора:</p>
                                    <ol class="list-decimal list-inside space-y-1 text-sm text-gray-600">
                                        <li>Проверьте настройки API ключей в админ-панели</li>
                                        <li>Обновите ключи в файле .env</li>
                                        <li>Выполните: php artisan config:clear</li>
                                    </ol>
                                </div>`, {
                                    confirmText: 'Понятно'
                                }
                            );
                            break;
                        case 'connection':
                            window.infoModalManager.showError(
                                'Ошибка подключения',
                                errorMessage, {
                                    confirmText: 'Понятно'
                                }
                            );
                            break;
                        case 'timeout':
                            window.infoModalManager.showWarning(
                                'Превышено время ожидания',
                                errorMessage, {
                                    confirmText: 'Попробовать позже'
                                }
                            );
                            break;
                        case 'server_error':
                            window.infoModalManager.showError(
                                'Ошибка сервера',
                                errorMessage, {
                                    confirmText: 'Понятно'
                                }
                            );
                            break;
                        case 'not_found':
                            window.infoModalManager.showError(
                                'Сервис недоступен',
                                errorMessage, {
                                    confirmText: 'Понятно'
                                }
                            );
                            break;
                        default:
                            // Общая ошибка
                            window.infoModalManager.showError(
                                'Ошибка генерации',
                                errorMessage, {
                                    confirmText: 'Понятно'
                                }
                            );
                    }
                } else {
                    // Fallback если нет infoModalManager
                    alert('Ошибка: ' + error.message);
                }
            } finally {
                isGenerating = false;
                sendButton.disabled = false;
            }
        }

        // Добавление индикатора загрузки
        function addLoadingMessage() {
            const chatMessages = document.getElementById('chat-messages');
            if (!chatMessages) return null;

            // Показываем область сообщений если она скрыта
            chatMessages.classList.remove('hidden');

            // Скрываем приветственное сообщение
            const welcomeMessage = document.getElementById('welcome-message');
            if (welcomeMessage) {
                welcomeMessage.classList.add('hidden');
            }

            const loadingId = 'loading-' + Date.now();
            const loadingDiv = document.createElement('div');
            loadingDiv.id = loadingId;
            loadingDiv.className = 'flex items-start space-x-3 p-4';

            loadingDiv.innerHTML = `
        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </div>
        <div class="flex-1">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center space-x-2">
                    <div class="animate-pulse bg-gray-200 h-4 w-32 rounded"></div>
                    <div class="animate-pulse bg-gray-200 h-4 w-24 rounded"></div>
                </div>
            </div>
        </div>
    `;

            chatMessages.appendChild(loadingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            return loadingId;
        }

        // Удаление индикатора загрузки
        function removeLoadingMessage(loadingId) {
            if (!loadingId) return;
            const loadingElement = document.getElementById(loadingId);
            if (loadingElement) {
                loadingElement.remove();
            }
        }

        // Установка примера промпта
        function setExamplePrompt(prompt) {
            const messageInput = document.getElementById('message-input');
            if (messageInput) {
                messageInput.value = prompt;
                messageInput.focus();

                // Обновляем счетчик символов
                const charCount = document.getElementById('char-count');
                if (charCount) {
                    charCount.textContent = `${prompt.length}/3000`;
                }

                // Автоматически изменяем высоту
                messageInput.style.height = 'auto';
                messageInput.style.height = Math.min(messageInput.scrollHeight, 120) + 'px';
            }
        }

        // Функция для загрузки чата из истории
        function loadChatFromHistory(chatData) {
            if (chatDisplayManager) {
                chatDisplayManager.showChatHistory(chatData);
                // Синхронизируем глобальный currentChatId с ID чата
                currentChatId = chatData.session_id || chatData.id;
            }
        }

        // Функция для показа приветственного сообщения
        function showWelcomeMessage() {
            if (chatDisplayManager) {
                chatDisplayManager.showWelcomeMessage();
            }
        }

        // Функция для переключения выпадающего меню
        function toggleDropdown(type) {
            // Закрываем все другие выпадающие меню
            const allDropdowns = ['domain', 'model', 'style', 'format'];
            allDropdowns.forEach(dropdownType => {
                if (dropdownType !== type) {
                    const dropdown = document.getElementById(`${dropdownType}-dropdown`);
                    if (dropdown) {
                        dropdown.classList.add('hidden');
                    }
                }
            });

            // Переключаем текущее меню
            const dropdown = document.getElementById(`${type}-dropdown`);
            if (dropdown) {
                const isHidden = dropdown.classList.contains('hidden');
                dropdown.classList.toggle('hidden');

                // Если меню открывается, обновляем галочки
                if (isHidden) {
                    const buttonElement = document.getElementById(`${type}-button`);
                    const selectedValue = buttonElement ? buttonElement.getAttribute('data-selected-value') : null;
                    if (selectedValue) {
                        updateCheckmarks(type, selectedValue);
                    }
                }
            }
        }

        // Функция для выбора опции

        // Функция для получения выбранного значения
        function getSelectedValue(type) {
            const buttonElement = document.getElementById(`${type}-button`);
            return buttonElement ? buttonElement.getAttribute('data-selected-value') : null;
        }


        // Закрытие выпадающих меню при клике вне их
        document.addEventListener('click', function(event) {
            const dropdowns = ['domain', 'model', 'style', 'format'];
            dropdowns.forEach(type => {
                const button = document.getElementById(`${type}-button`);
                const dropdown = document.getElementById(`${type}-dropdown`);

                if (button && dropdown && !button.contains(event.target) && !dropdown.contains(event
                        .target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });

        // Функция для загрузки чата по ID из URL
        async function loadChatFromUrl(chatId) {
            try {
                const response = await fetch(`/api/sessions/${chatId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load chat data');
                }

                const chatData = await response.json();

                // Показываем чат через ChatDisplayManager
                if (chatDisplayManager) {
                    chatDisplayManager.showChatHistory(chatData);
                    // Синхронизируем глобальный currentChatId с ID чата
                    currentChatId = chatData.session_id || chatData.id || chatId;
                }

                // Обновляем активный чат в истории
                if (window.chatHistoryManager) {
                    window.chatHistoryManager.setActiveChat(chatId);
                }

            } catch (error) {
                console.error('Ошибка загрузки чата:', error);
                if (window.infoModalManager) {
                    window.infoModalManager.showError('Ошибка загрузки', 'Не удалось загрузить чат');
                }
            }
        }

        // Временная функция для прямого отображения сообщений
        function displayMessageDirectly(message, data) {
            console.log('Используем временную функцию displayMessageDirectly');

            const chatMessages = document.getElementById('chat-messages');
            const welcomeMessage = document.getElementById('welcome-message');

            if (!chatMessages) {
                console.error('chat-messages контейнер не найден!');
                return;
            }

            // Скрываем приветственное сообщение
            if (welcomeMessage) {
                welcomeMessage.classList.add('hidden');
            }

            // Показываем контейнер сообщений
            chatMessages.classList.remove('hidden');

            // Добавляем сообщение пользователя
            const userMessageDiv = document.createElement('div');
            userMessageDiv.className = 'flex items-start justify-end p-2 md:p-4';
            userMessageDiv.innerHTML = `
                <div class="flex ml-2 md:ml-16 items-start space-x-3 max-w-sm md:max-w-2xl">
                    <div class="flex-1">
                        <div class="bg-blue-500 text-white rounded-lg p-3 ml-auto">
                            <p>${escapeHtml(message)}</p>
                        </div>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-sm font-bold">${getUserInitial()}</span>
                    </div>
                </div>
            `;
            chatMessages.appendChild(userMessageDiv);

            // Добавляем ответ ИИ
            const aiMessageDiv = document.createElement('div');
            aiMessageDiv.className = 'flex items-start justify-start p-2 md:p-4';
            aiMessageDiv.innerHTML = `
                <div class="flex mr-2 md:mr-32 items-start space-x-3 max-w-sm md:max-w-3xl">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-gray-800 whitespace-pre-wrap">${escapeHtml(data.generated_prompt || data.generatedPrompt)}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            chatMessages.appendChild(aiMessageDiv);

            console.log('Сообщения добавлены напрямую');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function getUserInitial() {
            const userInitialElement = document.querySelector('#user-initial');
            if (userInitialElement) {
                return userInitialElement.textContent.trim().toUpperCase();
            }
            return 'U';
        }

        // Функция для немедленного отображения сообщения пользователя
        function displayUserMessageImmediately(message) {
            console.log('Отображаем сообщение пользователя немедленно:', message);

            const chatMessages = document.getElementById('chat-messages');
            const welcomeMessage = document.getElementById('welcome-message');

            if (!chatMessages) {
                console.error('chat-messages контейнер не найден!');
                return;
            }

            // Скрываем приветственное сообщение
            if (welcomeMessage) {
                welcomeMessage.classList.add('hidden');
            }

            // Показываем контейнер сообщений
            chatMessages.classList.remove('hidden');

            // Добавляем сообщение пользователя
            const userMessageDiv = document.createElement('div');
            userMessageDiv.className = 'flex items-start justify-end p-2 md:p-4';
            userMessageDiv.innerHTML = `
                <div class="flex ml-2 md:ml-16 items-start space-x-3 max-w-sm md:max-w-2xl">
                    <div class="flex-1">
                        <div class="bg-blue-500 text-white rounded-lg p-3 ml-auto">
                            <p>${escapeHtml(message)}</p>
                        </div>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-sm font-bold">${getUserInitial()}</span>
                    </div>
                </div>
            `;
            chatMessages.appendChild(userMessageDiv);

            // Прокручиваем к новому сообщению
            userMessageDiv.scrollIntoView({
                behavior: 'smooth'
            });

            console.log('Сообщение пользователя добавлено немедленно');
        }

        // Функция для добавления индикатора "ИИ печатает..."
        function addTypingIndicator() {
            const chatMessages = document.getElementById('chat-messages');
            if (!chatMessages) return null;

            const typingId = 'typing-' + Date.now();
            const typingDiv = document.createElement('div');
            typingDiv.id = typingId;
            typingDiv.className = 'flex items-start justify-start p-2 md:p-4';
            typingDiv.innerHTML = `
                <div class="flex mr-2 md:mr-32 items-start space-x-3 max-w-sm md:max-w-3xl">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center space-x-2">
                                <div class="flex space-x-1">
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                </div>
                                <span class="text-gray-500 text-sm">ИИ печатает...</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            chatMessages.appendChild(typingDiv);

            // Прокручиваем к индикатору
            typingDiv.scrollIntoView({
                behavior: 'smooth'
            });

            return typingId;
        }

        // Функция для удаления индикатора "ИИ печатает..."
        function removeTypingIndicator(typingId) {
            if (typingId) {
                const typingElement = document.getElementById(typingId);
                if (typingElement) {
                    typingElement.remove();
                }
            }
        }

        // Функция для отображения только ответа ИИ
        function displayAIResponseDirectly(data) {
            console.log('Отображаем только ответ ИИ:', data);

            const chatMessages = document.getElementById('chat-messages');
            if (!chatMessages) {
                console.error('chat-messages контейнер не найден!');
                return;
            }

            // Добавляем ответ ИИ
            const aiMessageDiv = document.createElement('div');
            aiMessageDiv.className = 'flex items-start justify-start p-2 md:p-4';
            aiMessageDiv.innerHTML = `
                <div class="flex mr-2 md:mr-32 items-start space-x-3 max-w-sm md:max-w-3xl">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-gray-800 whitespace-pre-wrap">${escapeHtml(data.generated_prompt || data.generatedPrompt)}</p>
                            </div>
                            ${data.allow_edit ? `
                                <div class="mt-3 flex justify-end">
                                    <button onclick="openEditPromptModal(${data.request_id}, '${escapeHtml(data.generated_prompt || data.generatedPrompt).replace(/'/g, "\\'")}')"
                                            class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                                        ✏️ Редактировать промпт
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
            chatMessages.appendChild(aiMessageDiv);

            // Прокручиваем к новому сообщению
            aiMessageDiv.scrollIntoView({
                behavior: 'smooth'
            });

            console.log('Ответ ИИ добавлен');
        }

        /**
         * Удаление текущего чата
         * Показывает модальное окно подтверждения перед удалением
         */
        async function deleteChat() {
            console.log('deleteChat вызван, currentChatId:', currentChatId);

            // Проверяем, что есть текущий чат
            if (!currentChatId) {
                console.warn('Нет currentChatId для удаления');
                if (window.infoModalManager) {
                    window.infoModalManager.showWarning(
                        'Нет активного чата',
                        'Нет чата для удаления. Создайте новый чат, отправив сообщение.'
                    );
                }
                return;
            }

            // Показываем модальное окно подтверждения
            if (window.infoModalManager) {
                const confirmed = await window.infoModalManager.showConfirm(
                    'Удалить чат?',
                    'Вы уверены, что хотите удалить этот чат? Это действие нельзя будет отменить.', {
                        confirmText: 'Удалить',
                        cancelText: 'Отмена',
                        confirmClass: 'bg-red-500 hover:bg-red-600'
                    }
                );

                if (!confirmed) return;
            } else {
                // Fallback если нет infoModalManager
                if (!confirm('Вы уверены, что хотите удалить этот чат?')) {
                    return;
                }
            }

            try {
                // Отправляем запрос на удаление
                const response = await fetch(`/api/sessions/${currentChatId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                if (!response.ok) {
                    throw new Error('Ошибка при удалении чата');
                }

                // Успешное удаление
                if (window.infoModalManager) {
                    window.infoModalManager.showSuccess(
                        'Чат удален',
                        'Чат успешно удален из истории'
                    );
                }

                // Обновляем историю чатов в сайдбаре
                if (window.chatHistoryManager) {
                    window.chatHistoryManager.loadChatHistory();
                }

                // Перенаправляем на страницу нового чата
                setTimeout(() => {
                    window.location.href = '/chat';
                }, 1000);

            } catch (error) {
                console.error('Ошибка удаления чата:', error);
                if (window.infoModalManager) {
                    window.infoModalManager.showError(
                        'Ошибка удаления',
                        'Не удалось удалить чат. Попробуйте позже.'
                    );
                } else {
                    alert('Ошибка при удалении чата');
                }
            }
        }

        /**
         * Поделиться текущим чатом
         * Копирует ссылку на чат в буфер обмена
         */
        function shareChat() {
            // Проверяем, что есть текущий чат
            if (!currentChatId) {
                if (window.infoModalManager) {
                    window.infoModalManager.showWarning(
                        'Нет активного чата',
                        'Невозможно поделиться. Сначала создайте чат, отправив сообщение.'
                    );
                }
                return;
            }

            // Формируем URL чата
            const chatUrl = `${window.location.origin}/chat/${currentChatId}`;

            // Копируем в буфер обмена
            navigator.clipboard.writeText(chatUrl)
                .then(() => {
                    if (window.infoModalManager) {
                        window.infoModalManager.showSuccess(
                            'Ссылка скопирована!',
                            'Ссылка на чат скопирована в буфер обмена. Теперь вы можете поделиться ей.'
                        );
                    } else {
                        alert('Ссылка на чат скопирована в буфер обмена');
                    }
                })
                .catch(err => {
                    console.error('Ошибка копирования:', err);
                    // Fallback для старых браузеров
                    const textArea = document.createElement('textarea');
                    textArea.value = chatUrl;
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        if (window.infoModalManager) {
                            window.infoModalManager.showSuccess(
                                'Ссылка скопирована!',
                                'Ссылка на чат скопирована в буфер обмена'
                            );
                        } else {
                            alert('Ссылка на чат скопирована');
                        }
                    } catch (err) {
                        if (window.infoModalManager) {
                            window.infoModalManager.showError(
                                'Ошибка',
                                'Не удалось скопировать ссылку'
                            );
                        } else {
                            alert('Ошибка копирования ссылки');
                        }
                    }
                    document.body.removeChild(textArea);
                });
        }

        // Глобальные переменные для редактирования промпта
        let currentEditRequestId = null;

        // Функция для открытия модального окна редактирования промпта
        window.openEditPromptModal = function(requestId, currentPrompt) {
            currentEditRequestId = requestId;

            if (window.infoModalManager) {
                window.infoModalManager.show({
                    title: 'Редактировать промпт',
                    message: `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Отредактируйте промпт перед отправкой в LLM:
                            </label>
                            <textarea id="edit-prompt-textarea"
                                      class="w-full h-64 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                      placeholder="Введите отредактированный промпт...">${currentPrompt}</textarea>
                        </div>
                        <div class="text-sm text-gray-500">
                            Отредактируйте текст промпта для получения более эффективного ответа.
                        </div>
                    </div>
                    `,
                    type: 'info',
                    showCancel: true,
                    confirmText: 'Сохранить',
                    cancelText: 'Отмена',
                    onConfirm: sendEditedPrompt,
                    onCancel: () => {
                        currentEditRequestId = null;
                    }
                });

                // Фокусируемся на textarea
                setTimeout(() => {
                    const textarea = document.getElementById('edit-prompt-textarea');
                    if (textarea) {
                        textarea.focus();
                        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
                    }
                }, 100);
            }
        }

        // Функция для обновления отображения промпта на странице
        function updatePromptDisplay(requestId, newPrompt) {
            // Ищем все элементы с отредактированным промптом по request_id
            const promptElements = document.querySelectorAll(`[data-request-id="${requestId}"]`);

            promptElements.forEach(element => {
                // Обновляем текст промпта с форматированием
                const promptTextElement = element.querySelector('.prompt-text');
                if (promptTextElement) {
                    // Если элемент имеет класс formatted-content, используем formatText
                    if (promptTextElement.classList.contains('formatted-content')) {
                        promptTextElement.innerHTML = formatText(newPrompt);
                    } else {
                        promptTextElement.textContent = newPrompt;
                    }
                }

                // Обновляем атрибут data-prompt-text для кнопки редактирования
                const editButton = element.querySelector('[data-edit-prompt-button]');
                if (editButton) {
                    editButton.setAttribute('data-prompt-text', newPrompt);
                }
            });

            console.log('Промпт обновлен на странице:', { requestId, newPrompt });
        }

        // Функция форматирования текста (копируем из PromptResultManager)
        function formatText(text) {
            if (!text) return '';

            let result = text
                // Экранируем HTML
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                // Выделяем жирный текст
                .replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold">$1</strong>')
                // Выделяем курсив
                .replace(/\*(.*?)\*/g, '<em class="italic">$1</em>')
                // Выделяем код
                .replace(/`(.*?)`/g, '<code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono">$1</code>')
                // Создаем маркированные списки из строк, начинающихся с - или •
                .replace(/^[\s]*[-•]\s*(.+)$/gm, '<li>$1</li>')
                // Создаем нумерованные списки
                .replace(/^[\s]*(\d+)\.\s*(.+)$/gm, '<li>$1. $2</li>')
                // Оборачиваем группы <li> в <ul> или <ol>
                .replace(/(<li>\d+\..*?<\/li>)(?=\s*<li>\d+\.|$)/gs,
                    '<ol class="list-decimal list-inside space-y-2 my-4">$1</ol>')
                .replace(/(<li>•.*?<\/li>)(?=\s*<li>•|$)/gs, '<ul class="list-disc list-inside space-y-2 my-4">$1</ul>')
                // Обрабатываем одиночные элементы списка
                .replace(/(<li>\d+\..*?<\/li>)/gs, '<ol class="list-decimal list-inside space-y-2 my-4">$1</ol>')
                .replace(/(<li>•.*?<\/li>)/gs, '<ul class="list-disc list-inside space-y-2 my-4">$1</ul>')
                // Выделяем заголовки (строки, заканчивающиеся на :)
                .replace(/^(.+):\s*$/gm, '<h4 class="font-semibold text-gray-800 mt-3 mb-2 text-blue-700">$1</h4>')
                // Выделяем цитаты (строки, начинающиеся с >)
                .replace(/^>\s*(.+)$/gm,
                    '<blockquote class="border-l-4 border-gray-300 pl-4 italic text-gray-600 my-2">$1</blockquote>')
                // Создаем разделители
                .replace(/^---$/gm, '<hr class="my-4 border-gray-300">')
                // Выделяем ссылки
                .replace(/\[([^\]]+)\]\(([^)]+)\)/g,
                    '<a href="$2" class="text-blue-600 hover:text-blue-800 underline" target="_blank">$1</a>');

            return result;
        }

        // Функция для отправки отредактированного промпта
        window.sendEditedPrompt = function() {
            if (!currentEditRequestId) {
                console.error('Нет ID запроса для редактирования');
                return;
            }

            const textarea = document.getElementById('edit-prompt-textarea');
            if (!textarea) {
                console.error('Textarea для редактирования не найдена');
                return;
            }

            const editedPrompt = textarea.value.trim();
            if (!editedPrompt) {
                if (window.infoModalManager) {
                    window.infoModalManager.showError('Ошибка', 'Промпт не может быть пустым');
                }
                return;
            }

            // Показываем индикатор загрузки
            const loadingId = addTypingIndicator('Отправляем отредактированный промпт...');

            // Отправляем запрос
            fetch('/chat/send-edited-prompt', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    request_id: currentEditRequestId,
                    edited_prompt: editedPrompt
                })
            })
            .then(response => response.json())
            .then(data => {
                // Удаляем индикатор загрузки
                removeTypingIndicator(loadingId);

                if (data.success) {
                    // Обновляем отображение промпта на странице
                    updatePromptDisplay(currentEditRequestId, editedPrompt);

                    // Показываем сообщение об успешном сохранении
                    if (window.infoModalManager) {
                        window.infoModalManager.show({
                            title: 'Успешно',
                            message: data.message || 'Промпт успешно сохранен',
                            type: 'success'
                        });
                    }

                    // Закрываем модальное окно редактирования
                    if (window.infoModalManager) {
                        window.infoModalManager.hide();
                    }

                    // Сбрасываем текущий ID редактирования
                    currentEditRequestId = null;
                } else {
                    // Показываем ошибку
                    if (window.infoModalManager) {
                        window.infoModalManager.showError('Ошибка', data.error || 'Не удалось сохранить отредактированный промпт');
                    }
                }
            })
            .catch(error => {
                console.error('Ошибка при отправке отредактированного промпта:', error);
                removeTypingIndicator(loadingId);

                if (window.infoModalManager) {
                    window.infoModalManager.showError('Ошибка', 'Произошла ошибка при отправке промпта');
                }
            })
            .finally(() => {
                currentEditRequestId = null;
            });
        }
    </script>
@endsection
