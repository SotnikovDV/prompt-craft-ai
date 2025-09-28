@props([
    'resultId' => 'result-section',
    'showTitle' => true,
    'showParameters' => true
])

<section id="{{ $resultId }}" class="py-16 bg-violet-300 bg-opacity-30 hidden" data-prompt-result>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($showTitle)
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Ваш промпт готов!</h2>
                <p class="text-gray-600">Вот что получилось из вашего запроса:</p>
            </div>
        @endif

        <!-- Результат -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Результат анализа</h3>
            </div>

            <!-- Переключатели отображения -->
            <div class="flex flex-wrap gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <label class="flex items-center">
                    <input type="checkbox" data-show-reasoning class="mr-2">
                    <span class="text-sm font-medium text-gray-700">Показать ход рассуждений</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" data-show-questions class="mr-2" checked>
                    <span class="text-sm font-medium text-gray-700">Показать уточняющие вопросы</span>
                </label>
            </div>

            <!-- Ход рассуждений -->
            <div data-reasoning-section class="mb-6 hidden">
                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    Ход рассуждений
                </h4>
                <div data-reasoning-content class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                    <div class="text-gray-700 leading-relaxed prose prose-sm max-w-none">
                        <div class="formatted-content">
                            <!-- Здесь будет отображаться ход рассуждений -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Уточняющие вопросы -->
            <div data-questions-section class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Уточняющие вопросы
                </h4>
                <div data-questions-content class="bg-green-50 rounded-lg p-4 border border-green-200 mb-4">
                    <ul data-questions-list class="space-y-2">
                        <!-- Здесь будут отображаться уточняющие вопросы -->
                    </ul>
                </div>

                <!-- Поле для уточнений -->
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <label for="clarification-input" class="block text-sm font-medium text-gray-700 mb-2">
                        Добавьте уточнения к вашему запросу:
                    </label>
                    <textarea data-clarification-input rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Например: Целевая аудитория - ..."></textarea>
                    <div class="mt-3 flex justify-end">
                        <button data-regenerate-button
                            class="flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Пересоздать с уточнениями
                        </button>
                    </div>
                </div>
            </div>

            <!-- Сгенерированный промпт -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Сгенерированный промпт
                </h4>
                <div data-generated-prompt class="bg-gray-50 rounded-lg p-6 border border-gray-200 relative">
                    <div class="text-gray-700 leading-relaxed max-w-none pb-3">
                        <div class="formatted-content">
                            <!-- Здесь будет отображаться сгенерированный промпт -->
                        </div>
                    </div>
                    <!-- Кнопки действий в правом нижнем углу -->
                    <div class="absolute bottom-1 right-2 flex gap-2">
                        <!-- Кнопка копирования -->
                        <button data-copy-button
                            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white/80 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                            title="Копировать промпт">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>

                        <!-- Кнопка отправки в Telegram -->
                        <button data-telegram-button
                            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white/80 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                            title="Отправить в Telegram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                            </svg>
                        </button>

                        <!-- Кнопка передачи в чат-бот -->
                        <button data-chatbot-button
                            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white/80 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                            title="Отправить в чат-бот">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            @if($showParameters)
                <!-- Информация о параметрах -->
                <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div class="bg-blue-50 rounded-lg p-3">
                        <div class="font-medium text-blue-900">Область знаний</div>
                        <div class="text-blue-700" data-selected-domain>-</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3">
                        <div class="font-medium text-green-900">Модель</div>
                        <div class="text-green-700" data-selected-model>-</div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-3">
                        <div class="font-medium text-purple-900">Стиль</div>
                        <div class="text-purple-700" data-selected-style>-</div>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-3">
                        <div class="font-medium text-orange-900">Формат</div>
                        <div class="text-orange-700" data-selected-format>-</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
