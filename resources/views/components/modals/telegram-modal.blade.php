@props([
    'modalId' => 'telegram-modal',
    'title' => 'Отправить в Telegram',
    'prompt' => '',
    'encodedPrompt' => '',
])

<div id="{{ $modalId }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" data-telegram-modal>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="modal-brand max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0"
            data-telegram-modal-content>

            <!-- Заголовок модального окна -->
            <div class="modal-header-brand flex items-center justify-between px-6 py-4">
                <h3 data-telegram-modal-title class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                <button data-telegram-modal-close class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Список способов отправки -->
            <div class="px-6 pb-6">
                <div class="space-y-3">
                    <!-- Telegram Web -->
                    <button data-telegram-option="web"
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

                    <!-- Telegram App -->
                    <button data-telegram-option="app"
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

                    <!-- Копировать ссылку -->
                    <button data-telegram-option="copy"
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

                <!-- Кнопка отмены -->
                <div class="my-3 flex justify-end">
                    <button data-telegram-modal-cancel
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Отмена
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
