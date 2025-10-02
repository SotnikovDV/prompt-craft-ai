<!-- Ответ ИИ с закладками -->
<div class="flex items-start justify-start p-2 md:p-4">
    <div class="mr-2 md:mr-32 flex items-start space-x-3 max-w-sm md:max-w-3xl">
        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
        </div>
        <div class="flex-1 relative">
        <div class="bg-white border border-gray-200 rounded-lg p-4 relative">
            <!-- Закладки -->
            <div class="flex border-b border-gray-200 mb-4">
                <button class="tab-button active px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600"
                        data-tab="prompt">
                    Сгенерированный промпт
                </button>
                @if($reasoning)
                <button class="tab-button px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:border-blue-600"
                        data-tab="reasoning">
                    Ход рассуждений
                </button>
                @endif
            </div>

            <!-- Содержимое закладок -->
            <div class="tab-content">
                <!-- Сгенерированный промпт -->
                <div id="tab-prompt" class="tab-panel">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $generatedPrompt }}</p>
                    </div>
                </div>

                <!-- Ход рассуждений -->
                @if($reasoning)
                <div id="tab-reasoning" class="tab-panel hidden">
                    <div class="bg-blue-50 rounded-lg p-3">
                        <p class="text-gray-700 text-sm whitespace-pre-wrap">{{ $reasoning }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
            <!-- Кнопки действий -->
            {{-- <div class="flex gap-2 mt-4">
                <button data-copy-button data-prompt-text="{{ $generatedPrompt }}"
                    class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transition-colors">
                    Копировать
                </button>
                <button data-telegram-button data-prompt-text="{{ $generatedPrompt }}"
                    class="px-3 py-1 bg-blue-400 hover:bg-blue-500 text-white text-sm rounded-lg transition-colors">
                    Telegram
                </button>
                <button data-chatbot-button data-prompt-text="{{ $generatedPrompt }}"
                    class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg transition-colors">
                    Чат-бот
                </button>
            </div> --}}
            <!-- Кнопки действий в правом нижнем углу -->
            <div class="absolute bottom-1 right-5 flex gap-2">
                <!-- Кнопка копирования -->
                <button data-copy-button data-prompt-text="{{ $generatedPrompt }}"
                    class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white/80 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                    title="Копировать промпт">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </button>

                <!-- Кнопка отправки в Telegram -->
                <button data-telegram-button data-prompt-text="{{ $generatedPrompt }}"
                    class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white/80 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                    title="Отправить в Telegram">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                    </svg>
                </button>

                <!-- Кнопка передачи в чат-бот -->
                <button data-chatbot-button data-prompt-text="{{ $generatedPrompt }}"
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

<script>
// Обработка переключения закладок
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Убираем активный класс со всех кнопок
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'text-blue-600', 'border-b-2', 'border-blue-600');
                btn.classList.add('text-gray-600', 'hover:text-blue-600', 'hover:border-blue-600');
            });

            // Добавляем активный класс к текущей кнопке
            this.classList.add('active', 'text-blue-600', 'border-b-2', 'border-blue-600');
            this.classList.remove('text-gray-600', 'hover:text-blue-600', 'hover:border-blue-600');

            // Скрываем все панели
            tabPanels.forEach(panel => {
                panel.classList.add('hidden');
            });

            // Показываем нужную панель
            const targetPanel = document.getElementById('tab-' + targetTab);
            if (targetPanel) {
                targetPanel.classList.remove('hidden');
            }
        });
    });
});
</script>
