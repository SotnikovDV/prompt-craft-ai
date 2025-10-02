<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Толкователь ИИ') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Шрифты -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Стили / Скрипты -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Мобильное поведение левой панели */
        @media (max-width: 767px) {
            #sidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                height: 100vh !important;
                width: 16rem !important;
                transform: translateX(-100%) !important;
                transition: transform 0.3s ease-in-out !important;
                z-index: 50 !important;
                background-color: rgba(249, 250, 251, 0.9) !important;
                backdrop-filter: blur(8px) !important;
                border-right: 1px solid #e5e7eb !important;
            }

            #sidebar.sidebar-open {
                transform: translateX(0) !important;
            }

            #sidebar.sidebar-closed {
                transform: translateX(-100%) !important;
            }

            /* Отключаем hover эффекты на мобильных */
            #sidebar:hover {
                width: 16rem !important;
                transform: translateX(-100%) !important;
            }

            #sidebar.sidebar-open:hover {
                transform: translateX(0) !important;
            }

            /* Принудительно показываем все элементы в открытой панели на мобильных */
            #sidebar.sidebar-open .group-hover\:block {
                display: block !important;
            }

            #sidebar.sidebar-open .group-hover\:justify-start {
                justify-content: flex-start !important;
            }

            #sidebar.sidebar-open #chat-history-section {
                display: block !important;
            }
        }

        /* Десктопное поведение */
        @media (min-width: 768px) {
            #sidebar {
                position: relative !important;
                transform: none !important;
                width: 4rem !important;
            }

            #sidebar:hover {
                width: 20rem !important;
            }
        }

        /* Исправление позиционирования и формы аватара */
        #sidebar {
            display: flex !important;
            flex-direction: column !important;
        }

        #user-profile-btn {
            margin-top: auto !important;
        }

        #user-profile-btn .w-8 {
            width: 2rem !important;
            height: 2rem !important;
            border-radius: 50% !important;
            flex-shrink: 0 !important;
        }

        /* Обеспечиваем правильную форму круга */
        #user-profile-btn .rounded-full {
            border-radius: 50% !important;
            aspect-ratio: 1 !important;
        }

        /* Показываем историю чатов когда панель открыта на мобильных */
        .sidebar-open #chat-history-section {
            display: block !important;
        }

        /* Показываем названия кнопок когда панель открыта на мобильных */
        .sidebar-open .group-hover\:block {
            display: block !important;
        }

        /* Показываем названия кнопок в навигации когда панель открыта */
        .sidebar-open .group-hover\:justify-start {
            justify-content: flex-start !important;
        }

        /* Показываем все скрытые элементы в открытой панели */
        .sidebar-open .hidden.group-hover\:block {
            display: block !important;
        }

        /* Скрываем пункт "История чатов" когда панель открыта на мобильных */
        .sidebar-open .group-hover\:hidden {
            display: none !important;
        }

        /* Стили для плавного появления текста при расширении панели */
        #sidebar .group-hover\:block {
            opacity: 0;
            transform: translateX(-10px);
            transition: opacity 0.1s ease-in-out 0.25s, transform 0.1s ease-in-out 0.25s;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 0;
            display: inline-block;
        }

        #sidebar.group:hover .group-hover\:block {
            opacity: 1;
            transform: translateX(0);
            width: auto;
        }

        /* Для мобильных устройств - мгновенное появление */
        @media (max-width: 767px) {
            #sidebar .group-hover\:block {
                transition: none;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="flex h-screen relative">
        <!-- Фоновое изображение -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-fixed"
            style="background-image: url('/images/bg_ai.jpg'); opacity: 0.3; z-index: -1;"></div>
        <!-- Левая панель - История запросов (узкая, разворачивается при наведении) -->
        <div id="sidebar"
            class="relative bg-gradient-to-r from-violet-100 to-white {{-- bg-gray-50/90 backdrop-blur-sm --}} border-r border-gray-200 transition-all duration-300 ease-in-out hover:w-80 w-16 group flex flex-col h-full md:relative md:z-auto z-50 md:shadow-sm shadow-lg overflow-hidden">
            <!-- Логотип -->
            <div class="p-4 border-b border-gray-200 flex-shrink-0">
                <div class="w-full flex items-center justify-center group-hover:justify-start">
                    <div onclick="toggleSidebar()"
                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-lg flex items-center justify-center">
                        {{-- <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg> --}}

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
                    <a href="{{ route('welcome') }}">
                    <span class="text-gradient-hero ml-3 text-xl font-bold hidden group-hover:block text-left">Толкователь
                        ИИ</span>
                    </a>
                </div>
            </div>

            <!-- Навигационные иконки -->
            <div class="p-2 space-y-1 flex-shrink-0">
                <!-- Новый запрос -->
                <button id="new-chat-btn" onclick="startNewChat()"
                    class="w-full flex items-center justify-center p-2 hover:bg-gray-100 rounded-lg transition-colors group-hover:justify-start"
                    title="Новый запрос">
                    <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span class="ml-3 text-sm font-medium text-gray-700 hidden group-hover:block text-left">Новый
                        запрос</span>
                </button>

                <!-- Поиск -->
                <button
                    class="w-full flex items-center justify-center p-2 hover:bg-gray-100 rounded-lg transition-colors group-hover:justify-start"
                    title="Поиск в чатах">
                    <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="ml-3 text-sm font-medium text-gray-700 hidden group-hover:block text-left">Поиск в
                        чатах</span>
                </button>

                <!-- Библиотека -->
                <button
                    class="w-full flex items-center justify-center p-2 hover:bg-gray-100 rounded-lg transition-colors group-hover:justify-start"
                    title="Библиотека">
                    <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span
                        class="ml-3 text-sm font-medium text-gray-700 hidden group-hover:block text-left">Библиотека</span>
                </button>

                <!-- История чатов (только иконка в свернутом виде) -->
                <div class="w-full flex items-center justify-center p-2 hover:bg-gray-100 rounded-lg transition-colors group-hover:justify-start group-hover:hidden"
                    title="История чатов">
                    <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="ml-3 text-sm font-medium text-gray-700 hidden group-hover:block text-left">История
                        чатов</span>
                </div>
            </div>

            <!-- Список истории запросов (только в развернутом виде) -->
            <div id="chat-history-section"
                class="flex-1 overflow-y-auto min-h-0 group-hover:block hidden sidebar-open:block">
                <div class="flex items-center justify-start px-2 py-1 ml-2 space-x-2">
                    <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Чаты</h3>
                </div>
                <div id="chat-history" class="p-2 space-y-1">
                    <!-- Загрузка истории -->
                    <div id="history-loading" class="p-2 text-center">
                        <svg class="w-5 h-5 animate-spin text-gray-400 mx-auto" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>

                    <!-- Пустое состояние -->
                    <div id="history-empty" class="p-4 text-center hidden">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="text-xs text-gray-500">Нет истории</p>
                    </div>
                </div>
            </div>

            <!-- Нижняя панель с профилем -->
            <div class="px-4 py-2 border-t border-gray-300 flex-shrink-0 relative" style="margin-top: auto;">
                <!-- Мобильная версия - горизонтальные кнопки - меняем местами - профиль и админ панель -->
                <div class="md:hidden flex items-center justify-around px-8">
                    @if (in_array(Auth::user()->email, config('app.admin_emails', [])))
                        <button id="admin-profile-btn-mobile"
                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center hover:bg-gray-100 border border-gray-200 transition-colors order-2"
                            onclick="window.location.href='{{ route('admin.settings') }}'"
                            title="Админ панель">
                            <span class="text-2xl">⚙️</span>
                        </button>
                    @endif

                    <button id="user-profile-btn-mobile"
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center hover:opacity-80 transition-opacity order-1"
                        onclick="toggleUserMenu()"
                        title="{{ Auth::user()->name ?? Auth::user()->email }}">
                        <span class="text-white text-sm font-bold">{{ substr(Auth::user()->name ?? Auth::user()->email, 0, 1) }}</span>
                    </button>
                </div>

                <!-- Десктопная версия - вертикальные кнопки -->
                <div class="hidden md:block">
                    @if (in_array(Auth::user()->email, config('app.admin_emails', [])))
                        <button id="admin-profile-btn"
                            class="w-full flex items-center justify-center group-hover:justify-start hover:bg-gray-100 rounded-lg px-2 py-1 transition-colors"
                            onclick="window.location.href='{{ route('admin.settings') }}'">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xl font-bold">⚙️</span>
                            </div>
                            <div class="ml-3 hidden group-hover:block flex-1 min-w-0 text-left">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    Админ</p>
                                <p class="text-xs text-gray-500">панель</p>
                            </div>
                        </button>
                    @endif
                    <hr class="my-1">

                    <button id="user-profile-btn"
                        class="w-full flex items-center justify-center group-hover:justify-start hover:bg-gray-100 rounded-lg px-2 py-1 transition-colors"
                        onclick="toggleUserMenu()">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <span
                                class="text-white text-sm font-bold">{{ substr(Auth::user()->name ?? Auth::user()->email, 0, 1) }}</span>
                        </div>
                        <div class="ml-3 hidden group-hover:block flex-1 min-w-0 text-left">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ Auth::user()->name ?? Auth::user()->email }}</p>
                            <p class="text-xs text-gray-500">Премиум</p>
                        </div>
                    </button>
                </div>

                <!-- Контекстное меню пользователя -->
                <div id="user-menu"
                    class="absolute bottom-full left-4 right-4 mb-2 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50 md:left-4 md:right-4">
                    <div class="p-2">
                        <!-- Профиль -->
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ Auth::user()->email }}
                        </a>

                        <!-- Обновить план -->
                        <button
                            class="w-full flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Обновить план
                        </button>

                        <!-- Персонализация -->
                        <button
                            class="w-full flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Персонализация
                        </button>

                        <!-- Настройки -->
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Настройки
                        </a>

                        <!-- Справка -->
                        <button
                            class="w-full flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Справка
                        </button>

                        <!-- Разделитель -->
                        <div class="border-t border-gray-200 my-1"></div>

                        <!-- Выход -->
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Выйти
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overlay для мобильных устройств -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>


        <!-- Основная область чата -->
        <div class="flex-1 flex flex-col relative z-10">
            @yield('content')
        </div>

    </div>

    <!-- Компонент универсального модального окна -->
    <x-modals.info-modal modalId="notification-modal" />

    <!-- Компонент модального окна выбора чат-бота -->
    <x-modals.chatbot-modal modalId="chatbot-modal" />

    <!-- Компонент модального окна отправки в Telegram -->
    <x-modals.telegram-modal modalId="telegram-modal" />

    <!-- Футер сайта -->
    {{-- <x-footer /> --}}

    <script>
        // Глобальные переменные для чата
        let currentChatId = null;
        let chatHistory = [];
        let isGenerating = false;

        // Инициал пользователя
        window.userInitial = '{{ substr(Auth::user()->name ?? Auth::user()->email, 0, 1) }}';

        // Функция для выбора опции в выпадающем меню
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

            // Скрываем все галочки и добавляем отступы для невыбранных пунктов
            const allCheckmarks = dropdown.querySelectorAll('svg');
            allCheckmarks.forEach(checkmark => {
                if (checkmark.querySelector('path[d*="M5 13l4 4L19 7"]')) {
                    checkmark.classList.add('hidden');
                    // Добавляем отступ для невыбранного пункта
                    const span = checkmark.parentElement.querySelector('span');
                    if (span) {
                        span.classList.add('ml-6');
                    }
                }
            });

            // Показываем галочку для выбранной опции и убираем отступ
            const buttons = dropdown.querySelectorAll('button');
            buttons.forEach(button => {
                const span = button.querySelector('span');
                const checkmark = button.querySelector('svg');

                if (span && checkmark && span.textContent === selectedValue) {
                    checkmark.classList.remove('hidden');
                    // Убираем отступ для выбранного пункта
                    span.classList.remove('ml-6');
                }
            });
        }

        // Функция для сброса всех параметров к значениям по умолчанию
        function resetParameters() {
            // Сбрасываем область знаний
            selectOption('domain', 'Выберите область');

            // Сбрасываем модель
            selectOption('model', 'Выберите модель');

            // Сбрасываем стиль
            selectOption('style', 'Выберите стиль');

            // Сбрасываем формат
            selectOption('format', 'Выберите формат');
        }

        // Инициализация чата
        function initializeChat() {
            const messageInput = document.getElementById('message-input');
            const charCount = document.getElementById('char-count');

            if (messageInput && charCount) {
                messageInput.addEventListener('input', function() {
                    const count = this.value.length;
                    charCount.textContent = `${count}/3000`;

                    // Автоматическое изменение высоты textarea
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                });

                // Отправка по Enter (Shift+Enter для новой строки)
                messageInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        sendMessage();
                    }
                });

                // Сбрасываем параметры к значениям по умолчанию
                resetParameters();

            }
        }

        // Инициализация менеджера истории чатов
        function initializeChatHistory() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar && window.ChatHistoryManager) {
                window.chatHistoryManager = new window.ChatHistoryManager(sidebar);

                // Слушаем события от ChatHistoryManager
                sidebar.addEventListener('chat-loaded', (e) => {
                    console.log('Чат загружен:', e.detail);
                    currentChatId = e.detail.chatId;
                    // Здесь можно добавить логику загрузки сообщений чата
                });

                sidebar.addEventListener('chat-deleted', (e) => {
                    console.log('Чат удален:', e.detail);
                    if (currentChatId === e.detail.chatId) {
                        currentChatId = null;
                        // Здесь можно добавить логику очистки текущего чата
                    }
                });
            }
        }

        // Настройка обработчиков событий
        function setupEventListeners() {
            // Инициализируем компоненты
            initializeComponents();
        }

        // Инициализация компонентов
        function initializeComponents() {
            // Инициализируем универсальное модальное окно
            const infoModalContainer = document.querySelector('[data-info-modal]');
            if (infoModalContainer) {
                const infoModal = new window.InfoModalManager(document.body);
                window.infoModalManager = infoModal;
            }

            // Инициализируем модальное окно выбора чат-бота
            const chatbotModalContainer = document.querySelector('[data-chatbot-modal]');
            if (chatbotModalContainer) {
                const chatbotModal = new window.ChatBotModalManager(chatbotModalContainer);
                window.chatbotModalManager = chatbotModal;
            }

            // Инициализируем модальное окно отправки в Telegram
            const telegramModalContainer = document.querySelector('[data-telegram-modal]');
            if (telegramModalContainer) {
                const telegramModal = new window.TelegramModalManager(telegramModalContainer);
                window.telegramModalManager = telegramModal;
            }
        }

        // Начало нового чата
        function startNewChat() {
            currentChatId = null;
            chatHistory = [];

            // Обновляем URL для нового чата
            window.history.pushState({}, '', '/chat');

            // Очищаем поле ввода
            const messageInput = document.getElementById('message-input');
            if (messageInput) {
                messageInput.value = '';
                messageInput.style.height = 'auto';
            }

            // Сбрасываем счетчик символов
            const charCount = document.getElementById('char-count');
            if (charCount) {
                charCount.textContent = '0/3000';
            }

            // Показываем приветственное сообщение
            const welcomeMessage = document.getElementById('welcome-message');
            if (welcomeMessage) {
                welcomeMessage.classList.remove('hidden');
            }

            // Скрываем область сообщений
            const chatMessages = document.getElementById('chat-messages');
            if (chatMessages) {
                chatMessages.classList.add('hidden');
                chatMessages.innerHTML = '';
            }

            // Сбрасываем параметры к значениям по умолчанию
            resetParameters();

            // Фокусируемся на поле ввода
            if (messageInput) {
                messageInput.focus();
            }
        }

        // Функции shareChat() и deleteChat() определены в chat.blade.php

        // Вспомогательные функции теперь в ChatHistoryManager

        // Переключение контекстного меню пользователя
        function toggleUserMenu() {
            const userMenu = document.getElementById('user-menu');
            if (userMenu) {
                userMenu.classList.toggle('hidden');
            }
        }

        // Закрытие контекстного меню при клике вне его
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const userProfileBtn = document.getElementById('user-profile-btn');
            const userProfileBtnMobile = document.getElementById('user-profile-btn-mobile');
            const adminProfileBtn = document.getElementById('admin-profile-btn');
            const adminProfileBtnMobile = document.getElementById('admin-profile-btn-mobile');

            if (userMenu && !userMenu.contains(event.target) &&
                !userProfileBtn?.contains(event.target) &&
                !userProfileBtnMobile?.contains(event.target) &&
                !adminProfileBtn?.contains(event.target) &&
                !adminProfileBtnMobile?.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Управление левой панелью на мобильных устройствах
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar && overlay) {
                const isOpen = sidebar.classList.contains('sidebar-open');

                if (isOpen) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            }
        }

        // Открытие левой панели
        function openSidebar() {
            console.log('openSidebar вызвана');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar && overlay) {
                console.log('Добавляем класс sidebar-open');
                sidebar.classList.add('sidebar-open');
                sidebar.classList.remove('sidebar-closed');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');

                // Проверяем состояние элементов
                setTimeout(() => {
                    const historySection = document.getElementById('chat-history-section');
                    const hiddenElements = sidebar.querySelectorAll('.hidden.group-hover\\:block');
                    console.log('История чатов:', historySection ? historySection.style.display : 'не найдена');
                    console.log('Скрытые элементы:', hiddenElements.length);
                    console.log('Классы sidebar:', sidebar.className);
                }, 100);
            }
        }

        // Закрытие левой панели
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar && overlay) {

                sidebar.classList.remove('sidebar-open');
                sidebar.classList.add('sidebar-closed');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Инициализация поведения панели
        function initializeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar && overlay) {
                // На мобильных устройствах панель должна быть скрыта по умолчанию
                if (window.innerWidth < 768) {
                    sidebar.classList.add('sidebar-closed');
                } else {
                    sidebar.classList.remove('sidebar-open', 'sidebar-closed');
                }

                // Обработчик изменения размера окна
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        // Десктоп - убираем мобильные классы
                        sidebar.classList.remove('sidebar-open', 'sidebar-closed');
                        overlay.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    } else {
                        // Мобильный - добавляем мобильные классы
                        if (!sidebar.classList.contains('sidebar-open')) {
                            sidebar.classList.add('sidebar-closed');
                        }
                    }
                });

                // Закрытие панели при клике на overlay
                overlay.addEventListener('click', closeSidebar);
            }
        }

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            initializeChat();
            initializeChatHistory();
            setupEventListeners();
            initializeSidebar();
        });
    </script>

    @yield('scripts')
</body>

</html>
