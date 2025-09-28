@extends('layouts.app')

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

        /* Пользовательские стили теперь определены в resources/css/custom.css */
    </style>
    <!-- Герой-секция (верхний блок) -->
    <section class="py-10 bg-white bg-opacity-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <!-- Бейдж (значок) -->
                <div
                    class="inline-flex items-start shadow-sm px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-sm font-medium mb-8">
                    {{-- <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                </svg> --}}
                    ... всё понятно, но что конкретно ?

                </div>

                <!-- Основной заголовок -->
                <h1 class="text-3xl sm:text-4xl md:text-6xl max-w-full px-4 md:max-w-4xl mx-auto font-bold mb-6">
                    <span class="text-gradient-hero">Превращайте идеи в профессиональные промпты</span>
                </h1>

                <!-- Подзаголовок -->
                <p class="text-xl text-gray-600 mb-12 max-w-3xl mx-auto">
                    Толкователь превратит <q class="font-serif font-italic text-orange-600">ммм.., хочу что-то умное</q> в
                    профессиональные промпты,
                    которые удивят даже ChatGPT!<br>
                    Больше никаких <q class="font-serif font-italic text-orange-600">попробуй еще раз</q> и <q
                        class="font-serif font-italic text-orange-600">уточни, пожалуйста</q><br>
                    — только четкие результаты от всех ИИ-моделей!
                </p>

                <!-- Кнопки призыва к действию (CTA) -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">

                    <button onclick="showPromptInfoModal()"
                        class="btn-flex inline-flex items-center justify-center text-lg px-8 py-2 font-semibold">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Что такое промпт
                    </button>

                    <a href="#prompt-form-section"
                        class="btn-brand inline-flex items-center justify-center text-lg px-8 py-2 font-semibold">
                        Попробовать
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Секция возможностей/преимуществ -->
    <section class="pt-20 pb-10 bg-violet-300 bg-opacity-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Преимущество 1 -->
                <div class="text-center">

                    <div
                        class="w-16 h-16 bg-blue-100/60 rounded-xl shadow-md flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-brain h-8 w-8 text-blue-600">
                            <path d="M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z">
                            </path>
                            <path d="M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z">
                            </path>
                            <path d="M15 13a4.5 4.5 0 0 1-3-4 4.5 4.5 0 0 1-3 4"></path>
                            <path d="M17.599 6.5a3 3 0 0 0 .399-1.375"></path>
                            <path d="M6.003 5.125A3 3 0 0 0 6.401 6.5"></path>
                            <path d="M3.477 10.896a4 4 0 0 1 .585-.396"></path>
                            <path d="M19.938 10.5a4 4 0 0 1 .585.396"></path>
                            <path d="M6 18a4 4 0 0 1-1.967-.516"></path>
                            <path d="M19.967 17.484A4 4 0 0 1 18 18"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">ИИ-анализ</h3>
                    <p class="text-gray-600">Читает ваши мысли лучше, чем вы сами их формулируете</p>
                </div>

                <!-- Преимущество 2 -->
                <div class="text-center">

                    <div
                        class="w-16 h-16 bg-orange-100/60 rounded-xl shadow-md flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-target h-8 w-8 text-orange-600">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="6"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Точность</h3>
                    <p class="text-gray-600">Каждой модели - свой промпт!</p>
                </div>


                <!-- Преимущество 3 -->
                <div class="text-center">
                    <div
                        class="w-16 h-16 bg-green-100/60 rounded-xl shadow-md flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-zap h-8 w-8 text-green-600">
                            <path
                                d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Эффективность</h3>
                    <p class="text-gray-600">Теперь промпты решают всё!</p>
                </div>
            </div>

            <!-- Кнопки призыва к действию (CTA) -->
            <div class="flex flex-col mt-4 sm:flex-row gap-4 justify-center">
                <!-- Кнопка "Как это работает?" -->
                {{-- <div class="text-center mt-12"> --}}
                <button onclick="showHowItWorksModal()"
                    class="btn-flex inline-flex items-center justify-center text-lg px-8 py-2 font-semibold">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Как это работает
                </button>
                {{-- </div> --}}
                <!-- Кнопка "Попробовать" -->
                <a href="#prompt-form-section"
                    class="btn-brand inline-flex items-center justify-center text-lg px-8 py-2 font-semibold">
                    Попробовать
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>

        </div>
    </section>

    <!-- Секция формы создания промпта -->
    <section id="prompt-form-section" class="py-20 bg-white bg-opacity-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <!-- Градиентная панель ввода запроса -->
                <div
                    class="rounded-2xl p-[1px] shadow-lg bg-gradient-to-r from-blue-500/30 via-blue-300/20 to-orange-500/30">
                    <div class="bg-white/90 rounded-2xl border border-gray-200 p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Создание промпта</h2>
                        <p class="text-gray-600 mb-4">Напишите что хотите, а мы превратим это в шедевр для ИИ. Даже если вы
                            пишете как первоклассник!</p>

                        <!-- Компонент формы промптов -->
                        <x-prompts.prompt-form :showLimits="true" formId="prompt-form" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Компонент результата промпта -->
    <x-prompts.prompt-result resultId="result-section" :showTitle="true" :showParameters="true" />

    <!-- Подвал сайта -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Информация о компании -->
                <div class="md:col-span-1">
                    <div class="flex items-center space-x-2 mb-4">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold">Толкователь ИИ</span>
                    </div>
                    <p class="text-gray-400 mb-6">
                        Интеллектуальная платформа для создания профессиональных промптов для языковых моделей.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Ссылки на продукт -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Продукт</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Генератор промптов</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Библиотека шаблонов</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">API для разработчиков</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Chrome расширение</a></li>
                    </ul>
                </div>

                <!-- Полезные ресурсы -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Ресурсы</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Документация</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Руководства</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Best Practices</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Сообщество</a></li>
                    </ul>
                </div>

                <!-- Ссылки поддержки -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Поддержка</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Центр помощи</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Связаться с нами</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Статус системы</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Обратная связь</a></li>
                    </ul>
                </div>
            </div>

            <!-- Нижняя панель подвала -->
            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">© 2025 Толкователь ИИ. Все права защищены.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white text-sm">Политика конфиденциальности</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm">Условия использования</a>
                </div>
            </div>
        </div>
    </footer>


    <!-- Модальное окно с информацией о промптах -->
    <div id="prompt-info-modal" class="fixed inset-0 top-6 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-[85vh] flex flex-col transform transition-all duration-300 scale-95 opacity-0"
                id="prompt-info-content">
                <!-- Заголовок модального окна -->
                <div
                    class="modal-header-brand flex rounded-t-2xl items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-blue-500 to-orange-500 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Что такое промпт?</h3>
                    </div>
                    <button onclick="closePromptInfoModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Содержимое модального окна -->
                <div class="p-6 overflow-y-auto flex-1">
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed mb-6 text-lg">
                            <strong>Промпт для ИИ</strong> — это, по сути, ваша инструкция или вопрос, который вы задаете
                            искусственному интеллекту, чтобы он что-то сделал. Это своего рода цифровое заклинание, которое
                            заставляет кремниевого джинна исполнять ваши желания.
                        </p>

                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                            <p class="text-gray-700 leading-relaxed">
                                Представьте, что искусственный интеллект — это невероятно могущественный, но очень
                                буквальный стажёр. Если вы дадите ему расплывчатую задачу, результат может вас сильно
                                удивить, и не всегда в хорошем смысле. Промпт — это ваше техническое задание для этого
                                стажёра. Чем точнее и детальнее вы опишете, чего хотите, тем больше шансов получить шедевр,
                                а не странную картинку с котом, у которого семь лап. Качество промпта напрямую определяет,
                                наколдует ли ИИ для вас «Войну и мир» или просто набор случайных слов.
                            </p>
                        </div>

                        <h4 class="text-xl font-semibold text-gray-900 mb-4">Промпт может быть чем угодно:</h4>

                        <div class="grid md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div
                                        class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3 mt-1">
                                        <span class="text-white text-sm font-bold">?</span>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-green-800 mb-2">Простым вопросом</h5>
                                        <p class="text-green-700 text-sm">«Сколько весит средний африканский слон?»</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div
                                        class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center mr-3 mt-1">
                                        <span class="text-white text-sm font-bold">✍</span>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-purple-800 mb-2">Командой на создание текста</h5>
                                        <p class="text-purple-700 text-sm">«Напиши короткий рассказ в стиле киберпанк о
                                            детективе...»</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div
                                        class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center mr-3 mt-1">
                                        <span class="text-white text-sm font-bold">&lt;/&gt;</span>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-orange-800 mb-2">Запросом на написание кода</h5>
                                        <p class="text-orange-700 text-sm">«Создай функцию на Python для сортировки
                                            массива...»</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div
                                        class="w-6 h-6 bg-pink-500 rounded-full flex items-center justify-center mr-3 mt-1">
                                        <span class="text-white text-sm font-bold">🎨</span>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-pink-800 mb-2">Подробным описанием для изображения
                                        </h5>
                                        <p class="text-pink-700 text-sm">«Нарисуй фотореалистичного енота в скафандре...»
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-6">
                            <div class="flex items-start">
                                <div
                                    class="w-8 h-8 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">В итоге</h4>
                                    <p class="text-gray-700 leading-relaxed">
                                        хороший промпт — это ключ к получению от ИИ именно того, что вам нужно. А умение
                                        составлять такие промпты, или <strong>промпт-инжиниринг</strong>, — это почти
                                        суперспособность в современном мире.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Кнопки действий -->
                <div
                    class="flex rounded-b-2xl justify-end space-x-3 px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                    <button onclick="closePromptInfoModal()"
                        class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Понятно
                    </button>
                    <button
                        onclick="closePromptInfoModal(); document.getElementById('prompt-form-section').scrollIntoView({ behavior: 'smooth' });"
                        class="px-6 py-2 bg-gradient-to-r from-blue-500 to-orange-500 hover:from-blue-600 hover:to-orange-600 text-white rounded-lg transition-colors">
                        Создать промпт
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно "Как это работает?" -->
    <div id="how-it-works-modal" {{-- class="fixed inset-0 top-6 bg-black bg-opacity-50 z-50 hidden" --}} class="fixed inset-0 top-6 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div id="how-it-works-modal-content" {{-- class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[85vh] flex flex-col duration-300 scale-95 opacity-0" --}}
                class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-[85vh] flex flex-col transform transition-all duration-300 scale-95 opacity-0">
                <!-- Заголовок -->
                <div
                    class="modal-header-brand flex items-center justify-between px-6 py-4 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-2xl font-bold text-gray-900">Как это работает?</h3>
                    <button onclick="closeHowItWorksModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Контент -->
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="space-y-6">
                        <!-- Шаг 1 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="text-purple-600 font-bold text-sm">1</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Напишите вашу задачу/проблему</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Просто опишите, что вам нужно — даже если это звучит как "хочу что-то умное".
                                    Не нужно быть экспертом в промпт-инжиниринге!
                                </p>
                            </div>
                        </div>

                        <!-- Шаг 2 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-sm">2</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Анализ вашего запроса</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Наш ИИ-аналитик изучает ваш запрос, пытаясь понять ваши мотивы и определяет,
                                    что именно вы хотите получить от языковой модели.
                                </p>
                            </div>
                        </div>

                        <!-- Шаг 3 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                <span class="text-orange-600 font-bold text-sm">3</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Создание оптимального промпта</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    На основе анализа создается идеальный промпт с правильной структурой,
                                    контекстом и уточняюшими вопросами.
                                </p>
                            </div>
                        </div>

                        <!-- Шаг 4 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <span class="text-yellow-600 font-bold text-sm">4</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Уточнение запроса</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Если нужно, система задает уточняющие вопросы, чтобы лучше понять ваши потребности
                                    и создать максимально точный промпт.
                                </p>
                            </div>
                        </div>

                        <!-- Шаг 5 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-600 font-bold text-sm">5</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Готовый промпт для использования</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Вы получаете профессиональный промпт, который можно сразу использовать
                                    с любой языковой моделью для получения качественного результата.
                                </p>
                            </div>
                        </div>

                        <!-- Дополнительная информация -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-blue-900 mb-2">💡 Почему это работает?</h4>
                            <ul class="text-blue-800 space-y-1">
                                <li>• <strong>Глубокий анализ</strong> — понимаем ваши истинные потребности</li>
                                <li>• <strong>Экспертные знания</strong> — используем лучшие практики промпт-инжиниринга
                                </li>
                                <li>• <strong>Адаптация под модель</strong> — учитываем особенности разных ИИ</li>
                                <li>• <strong>Структурированность</strong> — создаем четкие и понятные инструкции</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Кнопки действий -->
                <div
                    class="flex rounded-b-2xl justify-end space-x-3 px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                    <button onclick="closeHowItWorksModal()"
                        class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Понятно
                    </button>
                    <button
                        onclick="closeHowItWorksModal(); document.getElementById('prompt-form-section').scrollIntoView({ behavior: 'smooth' });"
                        class="px-6 py-2 bg-gradient-to-r from-blue-500 to-orange-500 hover:from-blue-600 hover:to-orange-600 text-white rounded-lg transition-colors">
                        Создать промпт
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>

        // ============================================================================
        // ФУНКЦИИ ОБРАБОТКИ РЕЗУЛЬТАТА
        // ============================================================================

        /**
         * Форматирование текста с поддержкой Markdown-подобного синтаксиса
         */
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

        /**
         * Обработка результата генерации промпта
         */
        function handlePromptResult(data) {
            console.log('Обработка результата:', data);
            console.log('window.promptResultManager существует:', !!window.promptResultManager);

            // Используем PromptResultManager для отображения результата
            if (window.promptResultManager) {
                console.log('Вызываем promptResultManager.showResult');
                window.promptResultManager.showResult(data);
            } else {
                console.error('PromptResultManager не инициализирован');
            }
        }

        // ============================================================================
        // ИНИЦИАЛИЗАЦИЯ КОМПОНЕНТОВ
        // ============================================================================
        document.addEventListener('DOMContentLoaded', function() {
            // Проверяем доступность модулей

            // Инициализируем форму промптов
            const promptFormContainer = document.querySelector('[data-prompt-form]');
            if (promptFormContainer && window.PromptFormManager) {
                const promptForm = new window.PromptFormManager(promptFormContainer);
                // Сохраняем ссылку для глобального доступа
                window.promptFormManager = promptForm;

                // Слушаем событие генерации промпта
                promptFormContainer.addEventListener('prompt-generated', (e) => {

                    // 1. Обрабатываем результат
                    handlePromptResult(e.detail);
                });

                // Слушаем событие ошибки
                promptFormContainer.addEventListener('prompt-error', (e) => {
                    showErrorModal('Ошибка генерации', e.detail.error);
                });

                // Слушаем событие уведомлений
                promptFormContainer.addEventListener('show-notification', (e) => {
                    if (e.detail.type === 'error') {
                        showErrorModal('Ошибка', e.detail.message);
                    } else if (e.detail.type === 'success') {
                        showSuccessModal('Успех', e.detail.message);
                    }
                });
            }

            // Инициализируем компонент результата
            const promptResultContainer = document.querySelector('[data-prompt-result]');
            if (promptResultContainer) {
                const promptResult = new window.PromptResultManager(promptResultContainer);

                // Слушаем событие уведомлений от результата
                promptResultContainer.addEventListener('show-notification', (e) => {
                    if (e.detail.type === 'error') {
                        showErrorModal('Ошибка', e.detail.message);
                    } else if (e.detail.type === 'success') {
                        showSuccessModal('Успех', e.detail.message);
                    }
                });

                // Слушаем события для модальных окон
                promptResultContainer.addEventListener('open-telegram-modal', (e) => {
                    console.log('Открытие модального окна Telegram:', e.detail);

                    const {
                        prompt,
                        encodedPrompt
                    } = e.detail;

                    if (window.telegramModalManager) {
                        window.telegramModalManager.show(prompt, encodedPrompt);
                    } else {
                        console.error('TelegramModalManager не инициализирован');
                    }
                });

                promptResultContainer.addEventListener('open-chatbot-modal', (e) => {
                    console.log('Открытие модального окна чат-бота:', e.detail);

                    const {
                        prompt,
                        encodedPrompt
                    } = e.detail;

                    if (window.chatbotModalManager) {
                        window.chatbotModalManager.show(prompt, encodedPrompt);
                    } else {
                        console.error('ChatBotModalManager не инициализирован');
                    }
                });

                promptResultContainer.addEventListener('regenerate-prompt', (e) => {
                    console.log('Пересоздание промпта:', e.detail);

                    const {
                        clarification,
                        parentId
                    } = e.detail;

                    if (!clarification) {
                        console.error('Отсутствуют уточнения для пересоздания промпта');
                        return;
                    }

                    console.log('Начинаем пересоздание промпта с уточнениями:', clarification);

                    // 1. Получаем текущий текст из поля "Ваш запрос"
                    const promptInput = document.querySelector('[data-prompt-input]');
                    console.log('Поле ввода промпта найдено:', !!promptInput);
                    if (!promptInput) {
                        console.error('Поле ввода промпта не найдено');
                        return;
                    }

                    const currentPrompt = promptInput.value.trim();
                    console.log('Текущий промпт:', currentPrompt);

                    // 2. Добавляем уточнения с префиксом "Дополнительная информация"
                    const updatedPrompt = currentPrompt + '\n\nДополнительная информация: ' + clarification;
                    promptInput.value = updatedPrompt;
                    console.log('Обновленный промпт:', updatedPrompt);

                    // 3. Скрываем панель результата
                    if (window.promptResultManager) {
                        console.log('Скрываем панель результата');
                        window.promptResultManager.hide();
                    }

                    // 4. Очищаем поле уточнений
                    const clarificationInput = document.querySelector('[data-clarification-input]');
                    console.log('Поле уточнений найдено:', !!clarificationInput);
                    if (clarificationInput) {
                        clarificationInput.value = '';
                        console.log('Поле уточнений очищено');
                    }

                    // 5. Автоматически запускаем генерацию промпта
                    if (window.promptFormManager) {
                        console.log('Запускаем генерацию промпта через PromptFormManager');
                        // Небольшая задержка для плавности
                        setTimeout(() => {
                            console.log('Вызываем handleSubmit');
                            window.promptFormManager.handleSubmit();
                        }, 300);
                    } else {
                        console.log('PromptFormManager не найден, используем альтернативный способ');
                        // Альтернативный способ - находим кнопку и кликаем по ней
                        const submitButton = document.querySelector('[data-prompt-submit]');
                        if (submitButton) {
                            console.log('Найдена кнопка отправки, кликаем по ней');
                            setTimeout(() => {
                                submitButton.click();
                            }, 300);
                        } else {
                            console.error('Кнопка отправки не найдена');
                        }
                    }
                });

                // Сохраняем ссылку на менеджер результата для глобального доступа
                window.promptResultManager = promptResult;
            }

            // Инициализируем универсальное модальное окно
            const infoModalContainer = document.querySelector('[data-info-modal]');
            if (infoModalContainer) {
                const infoModal = new window.InfoModalManager(document.body);

                // Сохраняем ссылку для глобального доступа
                window.infoModalManager = infoModal;
            }

            // Инициализируем модальное окно выбора чат-бота
            const chatbotModalContainer = document.querySelector('[data-chatbot-modal]');
            if (chatbotModalContainer) {
                const chatbotModal = new window.ChatBotModalManager(chatbotModalContainer);

                // Сохраняем ссылку для глобального доступа
                window.chatbotModalManager = chatbotModal;
            }

            // Инициализируем модальное окно отправки в Telegram
            const telegramModalContainer = document.querySelector('[data-telegram-modal]');
            if (telegramModalContainer) {
                const telegramModal = new window.TelegramModalManager(telegramModalContainer);

                // Сохраняем ссылку для глобального доступа
                window.telegramModalManager = telegramModal;
            }
        });

        // ============================================================================
        // ГЛОБАЛЬНЫЕ ФУНКЦИИ ДЛЯ МОДАЛЬНОГО ОКНА С ИНФОРМАЦИЕЙ О ПРОМПТАХ
        // ============================================================================

        /**
         * Показать модальное окно с информацией о промптах
         */
        function showPromptInfoModal() {
            console.log('showPromptInfoModal вызвана');
            const modal = document.getElementById('prompt-info-modal');
            const modalContent = document.getElementById('prompt-info-content');

            if (!modal) {
                console.error('Модальное окно не найдено');
                return;
            }

            // Показываем модальное окно с анимацией
            modal.classList.remove('hidden');

            // Небольшая задержка для анимации
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 5);

            // Блокируем прокрутку body
            document.body.style.overflow = 'hidden';
        }

        // Делаем функцию глобальной
        window.showPromptInfoModal = showPromptInfoModal;

        /**
         * Закрыть модальное окно с информацией о промптах
         */
        function closePromptInfoModal() {
            console.log('closePromptInfoModal вызвана');
            const modal = document.getElementById('prompt-info-modal');
            const modalContent = document.getElementById('prompt-info-content');

            if (!modal) {
                console.error('Модальное окно не найдено для закрытия');
                return;
            }

            // Анимация закрытия
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                // Восстанавливаем прокрутку body
                document.body.style.overflow = '';
            }, 200);
        }

        // Делаем функцию глобальной
        window.closePromptInfoModal = closePromptInfoModal;

        // ============================================================================
        // ФУНКЦИИ ДЛЯ МОДАЛЬНОГО ОКНА "КАК ЭТО РАБОТАЕТ?"
        // ============================================================================

        /**
         * Показать модальное окно "Как это работает?"
         */
        function showHowItWorksModal() {
            console.log('showHowItWorksModal вызвана');
            const modal = document.getElementById('how-it-works-modal');
            const modalContent = document.getElementById('how-it-works-modal-content');
            if (modal) {
                modal.classList.remove('hidden');
                // Добавляем анимацию появления
                setTimeout(() => {
                    //modal.style.opacity = '1';
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                console.error('Модальное окно how-it-works-modal не найдено');
            }
        }

        // Делаем функцию глобальной
        window.showHowItWorksModal = showHowItWorksModal;

        /**
         * Закрыть модальное окно "Как это работает?"
         */
        function closeHowItWorksModal() {
            console.log('closeHowItWorksModal вызвана');
            const modal = document.getElementById('how-it-works-modal');
            const modalContent = document.getElementById('how-it-works-modal-content');

            // Анимация закрытия
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            if (modal) {
                //modal.style.opacity = '0';
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 200);
            } else {
                console.error('Модальное окно how-it-works-modal не найдено');
            }
        }

        // Делаем функцию глобальной
        window.closeHowItWorksModal = closeHowItWorksModal;

        // Функции clearPromptInput и copyPromptInput теперь обрабатываются в PromptFormManager

        // Проверяем, что функции определены
        console.log('Функции определены:', {
            showPromptInfoModal: typeof window.showPromptInfoModal,
            closePromptInfoModal: typeof window.closePromptInfoModal
        });

        // Дополнительная проверка после загрузки DOM
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM загружен, проверяем функции:', {
                showPromptInfoModal: typeof window.showPromptInfoModal,
                closePromptInfoModal: typeof window.closePromptInfoModal
            });
        });

        // Обработчики для модального окна и определение типа устройства
        document.addEventListener('DOMContentLoaded', function() {
            // Добавляем обработчики для модального окна
            setupModalHandlers();

            // Определяем тип устройства
            detectDeviceType();
        });

        /**
         * Настройка обработчиков для модального окна
         */
        function setupModalHandlers() {
            const modal = document.getElementById('notification-modal');

            // Закрытие по клику на фон
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Закрытие по клавише Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        }

        /**
         * Определение типа устройства
         */
        function detectDeviceType() {
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

            // Добавляем классы к body для CSS стилизации
            if (isMobile) {
                document.body.classList.add('is-mobile');
            }
            if (isTouch) {
                document.body.classList.add('is-touch');
            }

            // Сохраняем информацию о устройстве в глобальной переменной
            window.deviceInfo = {
                isMobile: isMobile,
                isTouch: isTouch,
                userAgent: navigator.userAgent
            };

            console.log('Device info:', window.deviceInfo);
        }

        // ============================================================================
        // ФУНКЦИИ ДЛЯ УПРАВЛЕНИЯ МОДАЛЬНЫМ ОКНОМ
        // ============================================================================

        /**
         * Показать модальное окно с уведомлением
         * @param {string} type - тип уведомления: 'success', 'error', 'warning', 'info'
         * @param {string} title - заголовок
         * @param {string} message - сообщение
         * @param {Object} options - дополнительные опции
         */
        function showModal(type = 'info', title = 'Уведомление', message = '', options = {}) {
            if (window.infoModalManager) {
                window.infoModalManager.show({
                    title,
                    message,
                    type,
                    showCancel: options.showCancel || false,
                    confirmText: options.confirmText || 'OK',
                    cancelText: options.cancelText || 'Отмена',
                    onConfirm: options.onConfirm || null,
                    onCancel: options.onCancel || null,
                    onClose: options.onClose || null
                });
            } else {
                console.error('InfoModalManager не инициализирован');
            }
        }

        /**
         * Закрыть модальное окно
         */
        function closeModal() {
            const modal = document.getElementById('notification-modal');
            const modalContent = document.getElementById('modal-content');

            // Анимация закрытия
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                // Очищаем callback функции
                window.modalCallback = null;
                window.modalCancelCallback = null;
            }, 300);
        }

        /**
         * Подтвердить действие в модальном окне
         */
        function confirmModal() {
            if (window.modalCallback && typeof window.modalCallback === 'function') {
                window.modalCallback();
            }
            closeModal();
        }

        /**
         * Показать уведомление об ошибке лимита
         */
        function showLimitExceededModal() {
            showModal('warning', 'Превышен лимит запросов',
                'Вы использовали все доступные запросы на сегодня. Зарегистрируйтесь для увеличения лимита или попробуйте завтра.', {
                    confirmText: 'Понятно',
                    onConfirm: () => {
                        // Можно добавить дополнительную логику, например, прокрутку к форме регистрации
                        const registerLink = document.querySelector('a[href*="register"]');
                        if (registerLink) {
                            registerLink.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    }
                }
            );
        }

        /**
         * Показать уведомление об успехе
         */
        function showSuccessModal(title, message) {
            if (window.infoModalManager) {
                window.infoModalManager.showSuccess(title, message, {
                    confirmText: 'Отлично!'
                });
            } else {
                showModal('success', title, message, {
                    confirmText: 'Отлично!'
                });
            }
        }

        /**
         * Показать уведомление об ошибке
         */
        function showErrorModal(title, message) {
            if (window.infoModalManager) {
                window.infoModalManager.showError(title, message, {
                    confirmText: 'Понятно'
                });
            } else {
                showModal('error', title, message, {
                    confirmText: 'Понятно'
                });
            }
        }

        /**
         * Тестовая функция для проверки модальных окон
         */
        function testModals() {
            // Показываем разные типы модальных окон по очереди
            showModal('info', 'Информация', 'Это информационное сообщение для тестирования модального окна.', {
                confirmText: 'Понятно',
                onConfirm: () => {
                    setTimeout(() => {
                        showModal('success', 'Успех!',
                            'Операция выполнена успешно! Модальное окно работает корректно.', {
                                confirmText: 'Отлично!',
                                onConfirm: () => {
                                    setTimeout(() => {
                                        showModal('warning', 'Предупреждение',
                                            'Это предупреждающее сообщение. Будьте осторожны!', {
                                                confirmText: 'Принято',
                                                onConfirm: () => {
                                                    setTimeout(() => {
                                                        showLimitExceededModal
                                                            ();
                                                    }, 500);
                                                }
                                            });
                                    }, 500);
                                }
                            });
                    }, 500);
                }
            });
        }

        /**
         * Тестовая функция для проверки копирования
         */
        function testCopy() {
            const testText = 'Это тестовый текст для проверки функции копирования на мобильных устройствах.';

            // Создаем временный элемент для тестирования
            const testElement = document.createElement('div');
            testElement.innerHTML = `<p>${testText}</p>`;
            testElement.style.display = 'none';
            document.body.appendChild(testElement);

            // Симулируем клик по кнопке копирования
            const mockEvent = {
                target: {
                    closest: () => ({
                        innerHTML: '<svg>copy</svg>',
                        classList: {
                            add: () => {},
                            remove: () => {}
                        },
                        title: 'Копировать'
                    })
                }
            };

            // Временно заменяем элемент с промптом
            const originalElement = document.getElementById('generated-prompt');
            const tempElement = document.createElement('div');
            tempElement.id = 'generated-prompt';
            tempElement.innerHTML = `<p>${testText}</p>`;
            originalElement.parentNode.replaceChild(tempElement, originalElement);

            // Тестируем копирование
            copyToClipboard(mockEvent);

            // Восстанавливаем оригинальный элемент
            setTimeout(() => {
                tempElement.parentNode.replaceChild(originalElement, tempElement);
                document.body.removeChild(testElement);
            }, 1000);
        }

        // Функция для загрузки информации о лимитах
        async function loadLimitsInfo() {
            try {
                const response = await fetch('{{ route('api.limits') }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                updateLimitsDisplay(data);
            } catch (error) {
                console.error('Ошибка при загрузке лимитов:', error);
                // Скрываем блок лимитов при ошибке
                document.getElementById('limits-info').classList.add('hidden');
            }
        }

        // Функция для обновления отображения лимитов
        function updateLimitsDisplay(data) {
            const limitsInfo = document.getElementById('limits-info');
            const limitsText = document.getElementById('limits-text');

            if (!data.is_authenticated) {
                // Показываем информацию для незарегистрированных пользователей
                if (data.remaining_requests !== null) {
                    if (data.remaining_requests > 0) {
                        limitsText.textContent =
                            `У вас осталось ${data.remaining_requests} из ${data.daily_limit_guest} запросов сегодня`;
                        limitsInfo.classList.remove('hidden');
                    } else {
                        limitsText.textContent = `Вы использовали все ${data.daily_limit_guest} запросов сегодня`;
                        limitsInfo.classList.remove('hidden');
                        limitsInfo.className = limitsInfo.className.replace('bg-blue-50 border-blue-200',
                            'bg-red-50 border-red-200');
                        limitsText.className = limitsText.className.replace('text-blue-800', 'text-red-800');
                    }
                }
            } else {
                // Скрываем для зарегистрированных пользователей (если у них нет лимита)
                if (data.remaining_requests === null) {
                    limitsInfo.classList.add('hidden');
                } else {
                    // Показываем для зарегистрированных с лимитом
                    if (data.remaining_requests > 0) {
                        limitsText.textContent =
                            `У вас осталось ${data.remaining_requests} из ${data.daily_limit_user} запросов сегодня`;
                        limitsInfo.classList.remove('hidden');
                    } else {
                        limitsText.textContent = `⚠️ Вы использовали все ${data.daily_limit_user} запросов сегодня`;
                        limitsInfo.classList.remove('hidden');
                        limitsInfo.className = limitsInfo.className.replace('bg-blue-50 border-blue-200',
                            'bg-red-50 border-red-200');
                        limitsText.className = limitsText.className.replace('text-blue-800', 'text-red-800');
                    }
                }
            }
        }

        // Функция toggleAdvancedOptions теперь обрабатывается в PromptFormManager

        // Функция для копирования промпта в буфер обмена
        window.copyToClipboard = function(event) {
            const promptElement = document.getElementById('generated-prompt').querySelector('.formatted-content');
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
        };

        // Функция для передачи промпта в чат-бот
        window.sendToChatBot = function(event) {
            const promptElement = document.getElementById('generated-prompt').querySelector('.formatted-content');
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
                'perplexity': `https://www.perplexity.ai/?q=${encodedPrompt}`,
                'deepseek': `https://chat.deepseek.com/?prompt=${encodedPrompt}`,
                'qwen': `https://chat.qwen.ai/?prompt=${encodedPrompt}`,
                'alice': `https://alice.yandex.ru/?prompt=${encodedPrompt}`
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
                        <button onclick="openChatBot('deepseek', '${encodedPrompt}')"
                            class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white text-sm font-bold">D</span>
                                </div>
                                <span class="font-medium">DeepSeek</span>
                            </div>
                        </button>
                        <button onclick="openChatBot('qwen', '${encodedPrompt}')"
                            class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white text-sm font-bold">Q</span>
                                </div>
                                <span class="font-medium">Qwen</span>
                            </div>
                        </button>
                        <button onclick="openChatBot('alice', '${encodedPrompt}')"
                            class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white text-sm font-bold">Я</span>
                                </div>
                                <span class="font-medium">Яндекс Алиса</span>
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
                if (bot === 'gemini' || bot === 'deepseek' || bot === 'qwen' || bot === 'alice') {
                    // Для этих ботов используем алгоритм с копированием в буфер обмена
                    handleCopyPrompt(bot, encodedPrompt);
                } else {
                    const url = chatBots[bot];
                    if (url) {
                        window.open(url, '_blank');
                    }
                }
                closeChatBotModal();
            };

            // Функция для закрытия модального окна
            window.closeChatBotModal = function() {
                document.body.removeChild(modal);
                delete window.openChatBot;
                delete window.closeChatBotModal;
            };

            // Функция для обработки ботов с копированием в буфер обмена
            window.handleCopyPrompt = function(bot, encodedPrompt) {
                const promptText = decodeURIComponent(encodedPrompt);

                // Копируем промпт в буфер обмена
                navigator.clipboard.writeText(promptText).then(() => {
                    // Показываем модальное окно с инструкцией
                    showCopyPromptModal(bot);
                }).catch(() => {
                    // Fallback для старых браузеров
                    const textArea = document.createElement('textarea');
                    textArea.value = promptText;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);

                    // Показываем модальное окно с инструкцией
                    showCopyPromptModal(bot);
                });
            };

            // Функция для показа модального окна с инструкцией по копированию промпта
            window.showCopyPromptModal = function(bot) {
                // Определяем данные для каждого бота
                const botData = {
                    'gemini': {
                        name: 'Gemini',
                        url: 'https://gemini.google.com/',
                        iconBgClass: 'bg-blue-100',
                        iconTextClass: 'text-blue-600',
                        buttonClass: 'bg-blue-500 hover:bg-blue-600',
                        icon: 'G'
                    },
                    'deepseek': {
                        name: 'DeepSeek',
                        url: 'https://chat.deepseek.com/',
                        iconBgClass: 'bg-indigo-100',
                        iconTextClass: 'text-indigo-600',
                        buttonClass: 'bg-indigo-500 hover:bg-indigo-600',
                        icon: 'D'
                    },
                    'qwen': {
                        name: 'Qwen',
                        url: 'https://chat.qwen.ai/',
                        iconBgClass: 'bg-red-100',
                        iconTextClass: 'text-red-600',
                        buttonClass: 'bg-red-500 hover:bg-red-600',
                        icon: 'Q'
                    },
                    'alice': {
                        name: 'Яндекс Алиса',
                        url: 'https://alice.yandex.ru/',
                        iconBgClass: 'bg-yellow-100',
                        iconTextClass: 'text-yellow-600',
                        buttonClass: 'bg-yellow-500 hover:bg-yellow-600',
                        icon: 'Я'
                    }
                };

                const currentBot = botData[bot];
                if (!currentBot) return;

                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <div class="text-center">
                            <div class="w-16 h-16 ${currentBot.iconBgClass} rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="${currentBot.iconTextClass} text-2xl font-bold">${currentBot.icon}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Промпт скопирован!</h3>
                            <p class="text-gray-600 mb-6">
                                Ваш промпт скопирован в буфер обмена. После открытия ${currentBot.name} вставьте его в поле для ввода (Ctrl+V).
                            </p>
                            <div class="flex justify-center space-x-3">
                                <button onclick="openBotAndClose('${bot}')"
                                    class="px-6 py-2 ${currentBot.buttonClass} text-white rounded-lg transition-colors">
                                    Открыть ${currentBot.name}
                                </button>
                                <button onclick="closeCopyPromptModal()"
                                    class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                    Отмена
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.appendChild(modal);

                // Функция для открытия бота и закрытия модального окна
                window.openBotAndClose = function(botName) {
                    const bot = botData[botName];
                    if (bot) {
                        window.open(bot.url, '_blank');
                    }
                    closeCopyPromptModal();
                };

                // Функция для закрытия модального окна
                window.closeCopyPromptModal = function() {
                    document.body.removeChild(modal);
                    delete window.openBotAndClose;
                    delete window.closeCopyPromptModal;
                };

                // Закрытие по клику на фон
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        window.closeCopyPromptModal();
                    }
                });
            };

            // Закрытие по клику на фон
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    window.closeChatBotModal();
                }
            });
        };

        // Функция для отправки промпта в Telegram (использует новый компонент)
        window.sendToTelegram = function(event) {
            const promptElement = document.getElementById('generated-prompt').querySelector('.formatted-content');
            if (!promptElement) {
                console.error('Элемент с промптом не найден');
                return;
            }

            const promptText = promptElement.textContent || promptElement.innerText;
            const encodedPrompt = encodeURIComponent(promptText);

            // Отправляем событие для открытия модального окна Telegram
            document.dispatchEvent(new CustomEvent('open-telegram-modal', {
                detail: {
                    prompt: promptText,
                    encodedPrompt: encodedPrompt
                }
            }));
        };

        // Функция для генерации промпта
        /*
        async function generatePrompt() {
            const form = document.getElementById('prompt-form');
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;

            console.log('Запуск генерации промпта');

            // Скрываем секцию результата во время генерации
            // document.getElementById('result-section').classList.add('hidden');
            if (window.promptResultManager) {
                console.log('Скрываем панель результата');
                window.promptResultManager.hide();
            }

            // Показываем индикатор загрузки
            submitButton.innerHTML =
                '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>Создаем промпт...';
            submitButton.disabled = true;

            // Собираем данные формы
            const formData = new FormData(form);
            console.log('Данные формы:', Object.fromEntries(formData));

            // Проверяем CSRF токен
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            console.log('CSRF токен найден:', csrfToken ? 'ДА' : 'НЕТ');
            console.log('CSRF токен значение:', csrfToken ? csrfToken.getAttribute('content') : 'НЕТ');

            try {
                const url = '{{ route('generate-prompt') }}';
                console.log('Отправляем запрос на:', url);

                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                    }
                });

                console.log('Ответ получен:', response.status);

                if (!response.ok) {
                    // Если это ошибка 429 (Too Many Requests), получаем детали из ответа
                    if (response.status === 429) {
                        try {
                            const errorData = await response.json();
                            throw new Error(`429: ${errorData.error || 'Превышен лимит запросов'}`);
                        } catch (jsonError) {
                            throw new Error('429: Превышен дневной лимит запросов');
                        }
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Данные ответа:', data);

                // Показываем результат
                showResult(data);

                // Обновляем информацию о лимитах
                loadLimitsInfo();

                // Прокручиваем к результату
                document.getElementById('result-section').scrollIntoView({
                    behavior: 'smooth'
                });

            } catch (error) {
                console.error('Ошибка при генерации промпта:', error);

                // Проверяем, является ли это ошибкой лимита
                if (error.message.includes('429')) {
                    showLimitExceededModal();
                } else {
                    showErrorModal('Ошибка генерации', 'Произошла ошибка при генерации промпта. Попробуйте еще раз.');
                }
            } finally {
                // Восстанавливаем кнопку
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
            }
        }
        */

        // Функция для пересоздания промпта с уточнениями
        async function regenerateWithClarification() {
            const clarificationInput = document.getElementById('clarification-input');
            const originalRequest = document.getElementById('prompt-input');
            const clarificationText = clarificationInput.value.trim();

            if (clarificationText) {
                // Добавляем уточнения к исходному запросу
                const combinedRequest = originalRequest.value + '\n\nДополнительные уточнения: ' + clarificationText;
                originalRequest.value = combinedRequest;

                // Очищаем поле уточнений
                clarificationInput.value = '';
            }

            // Запускаем процесс генерации с parent_id
            await generatePromptWithParent();
        }

        // Функция для генерации промпта с parent_id (для уточнений)
        async function generatePromptWithParent() {
            const form = document.getElementById('prompt-form');
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;

            console.log('Запуск генерации промпта с уточнениями');

            // Скрываем секцию результата во время генерации
            document.getElementById('result-section').classList.add('hidden');

            // Показываем индикатор загрузки
            submitButton.innerHTML =
                '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>Создаем промпт...';
            submitButton.disabled = true;

            // Собираем данные формы
            const formData = new FormData(form);

            // Добавляем parent_id из последнего запроса
            const lastRequestId = window.lastRequestId;
            if (lastRequestId) {
                formData.append('parent_id', lastRequestId);
            }

            console.log('Данные формы:', Object.fromEntries(formData));

            // Проверяем CSRF токен
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            console.log('CSRF токен найден:', csrfToken ? 'ДА' : 'НЕТ');
            console.log('CSRF токен значение:', csrfToken ? csrfToken.getAttribute('content') : 'НЕТ');

            try {
                const url = '{{ route('generate-prompt') }}';
                console.log('Отправляем запрос на:', url);

                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                    }
                });

                console.log('Ответ получен:', response.status);

                if (!response.ok) {
                    // Если это ошибка 429 (Too Many Requests), получаем детали из ответа
                    if (response.status === 429) {
                        try {
                            const errorData = await response.json();
                            throw new Error(`429: ${errorData.error || 'Превышен лимит запросов'}`);
                        } catch (jsonError) {
                            throw new Error('429: Превышен дневной лимит запросов');
                        }
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Данные ответа:', data);

                // Показываем результат
                showResult(data);

                // Обновляем информацию о лимитах
                loadLimitsInfo();

                // Прокручиваем к результату
                document.getElementById('result-section').scrollIntoView({
                    behavior: 'smooth'
                });

            } catch (error) {
                console.error('Ошибка при генерации промпта:', error);

                // Проверяем, является ли это ошибкой лимита
                if (error.message.includes('429')) {
                    showLimitExceededModal();
                } else {
                    showErrorModal('Ошибка генерации', 'Произошла ошибка при генерации промпта. Попробуйте еще раз.');
                }
            } finally {
                // Восстанавливаем кнопку
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
            }
        }

        // Функция для пересоздания промпта
        async function regeneratePrompt() {
            // Скрываем секцию результата во время генерации
            document.getElementById('result-section').classList.add('hidden');

            // Показываем индикатор загрузки
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML =
                '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>Создаем...';
            button.disabled = true;

            try {
                // Собираем данные из формы
                const form = document.getElementById('prompt-form');
                const formData = new FormData(form);

                const response = await fetch('{{ route('generate-prompt') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Обновляем результат
                    showResult(data);
                } else {
                    showErrorModal('Ошибка пересоздания', data.error || 'Неизвестная ошибка');
                }
            } catch (error) {
                console.error('Ошибка:', error);

                // Проверяем, является ли это ошибкой лимита
                if (error.message.includes('429')) {
                    showLimitExceededModal();
                } else {
                    showErrorModal('Ошибка пересоздания', 'Произошла ошибка при пересоздании промпта.');
                }
            } finally {
                // Восстанавливаем кнопку
                button.innerHTML = originalText;
                button.disabled = false;
            }
        }

        // Функция для отображения результата (будет вызываться после генерации)
        // formatText теперь находится в PromptResultManager модуле



        // Обработка отправки формы
        // Обработчик отправки формы теперь в PromptFormManager

        // Добавляем обработчики для модального окна с информацией о промптах
        setupPromptInfoModalHandlers();

        // Добавляем обработчики для модального окна "Как это работает?"
        setupHowItWorksModalHandlers();
        // });

        /**
         * Настройка обработчиков для модального окна с информацией о промптах
         */
        function setupPromptInfoModalHandlers() {
            const modal = document.getElementById('prompt-info-modal');

            if (!modal) {
                console.error('Модальное окно prompt-info-modal не найдено');
                return;
            }

            // Закрытие по клику на фон
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closePromptInfoModal();
                }
            });

            // Закрытие по клавише Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closePromptInfoModal();
                }
            });
        }

        /**
         * Настройка обработчиков для модального окна "Как это работает?"
         */
        function setupHowItWorksModalHandlers() {
            const modal = document.getElementById('how-it-works-modal');

            if (!modal) {
                console.error('Модальное окно how-it-works-modal не найдено');
                return;
            }

            // Закрытие по клику на фон
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeHowItWorksModal();
                }
            });

            // Закрытие по клавише Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeHowItWorksModal();
                }
            });
        }
    </script>

    <!-- Компонент универсального модального окна -->
    <x-modals.info-modal modalId="notification-modal" />

    <!-- Компонент модального окна выбора чат-бота -->
    <x-modals.chatbot-modal modalId="chatbot-modal" />

    <!-- Компонент модального окна отправки в Telegram -->
    <x-modals.telegram-modal modalId="telegram-modal" />
@endsection
