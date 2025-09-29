@extends('layouts.chatapp')

@section('content')
    <!-- Скрытый элемент с инициалом пользователя для JavaScript -->
    <div id="user-initial" class="hidden">{{ substr(Auth::user()->name ?? Auth::user()->email, 0, 1) }}</div>
        <!-- Заголовок чата -->
    <header class="md:absolute md:top-0 md:left-0 md:right-0 md:z-20 p-3 bg-white/90 border-b border-gray-200 md:bg-transparent md:border-none">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <!-- Логотип для мобильных (заменяет гамбургер) -->
                    <button onclick="toggleSidebar()"
                        class="md:hidden flex items-center space-x-2 p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                        title="Открыть меню">
                        <div
                            class="w-6 h-6 bg-gradient-to-br from-blue-500 to-orange-500 rounded flex items-center justify-center">
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
                        <span class="text-sm font-medium">Толкователь ИИ</span>
                    </button>
                </div>
            <div class="bg-transparent">
            </div>
            <div class="flex items-center space-x-2 mr-2 ">
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
    <div class="md:absolute md:bottom-0 md:left-0 md:right-0 md:z-20 bg-white/90 backdrop-blur-sm border-t border-gray-200 md:bg-transparent md:border-none p-4">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span id="domain-text" class="hidden">Область</span>
                    </button>
                    <div id="domain-dropdown" class="hidden absolute bottom-full left-0 mb-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                        <div class="py-1">
                            <div class="px-3 py-2 text-xs text-gray-500 border-b">Область знаний</div>
                            <button onclick="selectOption('domain', 'Выберите область')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Выберите область</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('domain', 'Программирование')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Программирование</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('domain', 'Технологии')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Технологии</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('domain', 'Маркетинг')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Маркетинг</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('domain', 'Образование')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Образование</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('domain', 'Медицина')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Медицина</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('domain', 'Финансы')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Финансы</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('domain', 'Дизайн')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Дизайн</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('domain', 'Писательство')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Писательство</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span id="model-text" class="hidden">Модель</span>
                    </button>
                    <div id="model-dropdown" class="hidden absolute bottom-full left-0 mb-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                        <div class="py-1">
                            <div class="px-3 py-2 text-xs text-gray-500 border-b">Целевая модель</div>
                            <button onclick="selectOption('model', 'Выберите модель')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Выберите модель</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('model', 'ChatGPT-4')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>ChatGPT-4</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('model', 'ChatGPT-3.5')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>ChatGPT-3.5</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('model', 'Claude')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Claude</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('model', 'Gemini')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Gemini</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('model', 'Llama')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Llama</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('model', 'Perplexity')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Perplexity</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('model', 'Универсальный')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Универсальный</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
                        </svg>
                        <span id="style-text" class="hidden">Стиль</span>
                    </button>
                    <div id="style-dropdown" class="hidden absolute bottom-full left-0 mb-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                        <div class="py-1">
                            <div class="px-3 py-2 text-xs text-gray-500 border-b">Стиль промпта</div>
                            <button onclick="selectOption('style', 'Выберите стиль')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Выберите стиль</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('style', 'Формальный')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Формальный</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('style', 'Дружелюбный')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Дружелюбный</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('style', 'Технический')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Технический</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('style', 'Креативный')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Креативный</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('style', 'Академический')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Академический</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span id="format-text" class="hidden">Формат</span>
                    </button>
                    <div id="format-dropdown" class="hidden absolute bottom-full left-0 mb-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                        <div class="py-1">
                            <div class="px-3 py-2 text-xs text-gray-500 border-b">Формат результата</div>
                            <button onclick="selectOption('format', 'Выберите формат')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Выберите формат</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('format', 'Текст')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Текст</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('format', 'Список')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Список</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('format', 'Таблица')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Таблица</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('format', 'JSON')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>JSON</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('format', 'Markdown')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Markdown</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button onclick="selectOption('format', 'Изображение')" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                                <span>Изображение</span>
                                <svg class="w-4 h-4 text-blue-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="flex justify-between items-center mt-2 text-sm text-gray-500">
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

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    if (window.ChatDisplayManager) {
        chatDisplayManager = new window.ChatDisplayManager();
        window.chatDisplayManager = chatDisplayManager;
    }

            // Загружаем чат по ID, если он передан в URL
            @if (isset($chatId))
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
                const style = getSelectedValue('style') || 'Профессиональный';
                const format = getSelectedValue('format') || 'Текст';

                console.log('Отправляем запрос на /generate-prompt с данными:', {
                    prompt: message,
                    domain: domain,
                    model: model,
                    style: style,
                    format: format,
                    session_id: currentChatId
                });

        const response = await fetch('/generate-prompt', {
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
            if (response.status === 429) {
                throw new Error('Превышен дневной лимит запросов');
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

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

        // Показываем ошибку
        if (error.message.includes('429') || error.message.includes('лимит')) {
            if (window.infoModalManager) {
                window.infoModalManager.showWarning('Превышен лимит запросов',
                    'Вы использовали все доступные запросы на сегодня. Зарегистрируйтесь для увеличения лимита или попробуйте завтра.', {
                        confirmText: 'Понятно'
                    }
                );
            }
        } else {
            if (window.infoModalManager) {
                window.infoModalManager.showError('Ошибка генерации', error.message);
            }
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
        function selectOption(type, value) {
            const textElement = document.getElementById(`${type}-text`);
            const buttonElement = document.getElementById(`${type}-button`);
            const dropdown = document.getElementById(`${type}-dropdown`);

            if (textElement && buttonElement) {
                // Обновляем текст кнопки (скрытый элемент)
                if (value.includes('Выберите')) {
                    textElement.textContent = type === 'domain' ? 'Область' :
                                            type === 'model' ? 'Модель' :
                                            type === 'style' ? 'Стиль' : 'Формат';
                } else {
                    textElement.textContent = value;
                }

                // Сохраняем выбранное значение в data-атрибуте кнопки
                buttonElement.setAttribute('data-selected-value', value);

                // Обновляем title кнопки для подсказки
                const titleText = type === 'domain' ? 'Область знаний' :
                                type === 'model' ? 'Модель ИИ' :
                                type === 'style' ? 'Стиль промпта' : 'Формат результата';
                buttonElement.setAttribute('title', `${titleText}: ${value}`);

                // Обновляем галочки в выпадающем меню
                updateCheckmarks(type, value);
            }

            // Закрываем выпадающее меню
            if (dropdown) {
                dropdown.classList.add('hidden');
            }
        }

        // Функция для обновления галочек в выпадающем меню
        function updateCheckmarks(type, selectedValue) {
            const dropdown = document.getElementById(`${type}-dropdown`);
            if (!dropdown) return;

            // Скрываем все галочки
            const allCheckmarks = dropdown.querySelectorAll('svg');
            allCheckmarks.forEach(checkmark => {
                if (checkmark.querySelector('path[d*="M5 13l4 4L19 7"]')) {
                    checkmark.classList.add('hidden');
                }
            });

            // Показываем галочку для выбранной опции
            const buttons = dropdown.querySelectorAll('button');
            buttons.forEach(button => {
                const span = button.querySelector('span');
                const checkmark = button.querySelector('svg');

                if (span && checkmark && span.textContent === selectedValue) {
                    checkmark.classList.remove('hidden');
                }
            });
        }

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

                if (button && dropdown && !button.contains(event.target) && !dropdown.contains(event.target)) {
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
        userMessageDiv.scrollIntoView({ behavior: 'smooth' });

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
        typingDiv.scrollIntoView({ behavior: 'smooth' });

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
                    </div>
                </div>
            </div>
        `;
        chatMessages.appendChild(aiMessageDiv);

        // Прокручиваем к новому сообщению
        aiMessageDiv.scrollIntoView({ behavior: 'smooth' });

        console.log('Ответ ИИ добавлен');
    }
}
</script>
@endsection
