@props([
    'showLimits' => true,
    'sessionId' => null,
    'formId' => 'prompt-form'
])

<div class="prompt-form-wrapper" data-prompt-form>
    <!-- Информация о лимитах для незарегистрированных пользователей -->
    @if($showLimits)
        <div id="limits-info" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="limits-text" class="text-sm font-medium text-blue-800">Загрузка информации о лимитах...</span>
                </div>
                <a href="{{ route('register') }}"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium self-center sm:self-auto">
                    Зарегистрироваться →
                </a>
            </div>
        </div>
    @endif

    <form id="{{ $formId }}" data-prompt-form-element class="space-y-6">
        <div>
            <label for="prompt-input" class="block text-sm font-medium text-gray-700 mb-2">Ваш запрос</label>
            <div class="relative">
                <textarea id="prompt-input" name="prompt" data-prompt-input rows="6"
                    class="w-full px-4 py-3 pr-24 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                    placeholder="Например: Я хочу написать техническую статью о нейросетях..." maxlength="3000"></textarea>

                <!-- Кнопки действий -->
                <div class="absolute top-1 right-1 flex flex-col gap-1">
                    <!-- Кнопка очистки -->
                    <button type="button" data-clear-prompt
                        class="p-1 text-gray-500 hover:text-gray-700 hover:bg-white/80 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                        title="Очистить поле">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="absolute bottom-2 right-1 flex flex-col gap-1">
                    <!-- Кнопка копирования -->
                    <button type="button" data-copy-prompt
                        class="p-1 text-gray-500 hover:text-gray-700 hover:bg-white/80 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                        title="Копировать текст">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex justify-between items-center mt-2">
                <span class="text-sm text-gray-500">Минимум 10 символов</span>
                <span class="text-sm text-gray-500">
                    <span data-char-count>0</span>/3000
                </span>
            </div>
        </div>

        <!-- Кнопка для раскрытия дополнительных параметров -->
        <div class="flex items-center justify-between text-blue-600 hover:text-white px-4 py-3 rounded-lg cursor-pointer hover:bg-orange-600 transition-colors"
            data-advanced-options-toggle>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-settings h-4 w-4">
                    <path
                        d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z">
                    </path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <span class="text-sm font-medium ml-2">Дополнительные параметры</span>
            </div>
            <svg data-toggle-icon class="w-5 h-5 transition-transform duration-200 text-gray-600"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7" />
            </svg>
        </div>

        <!-- Панель дополнительных параметров (скрыта по умолчанию) -->
        <div data-advanced-options class="hidden bg-gray-50 rounded-lg py-2 space-y-4">
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

                <!-- Стиль промпта -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Стиль промпта</label>
                    <select name="style"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Выберите стиль">Выберите стиль</option>
                        <option value="Формальный">Формальный</option>
                        <option value="Дружелюбный">Дружелюбный</option>
                        <option value="Технический">Технический</option>
                        <option value="Креативный">Креативный</option>
                        <option value="Академический">Академический</option>
                    </select>
                </div>

                <!-- Формат результата -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Формат результата</label>
                    <select name="format"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Выберите формат">Выберите формат</option>
                        <option value="Текст">Текст</option>
                        <option value="Список">Список</option>
                        <option value="Таблица">Таблица</option>
                        <option value="JSON">JSON</option>
                        <option value="Markdown">Markdown</option>
                        <option value="Изображение">Изображение</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" data-prompt-submit
            class="btn-brand w-full py-3 px-6 text-lg flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Создать промпт
        </button>
    </form>
</div>
