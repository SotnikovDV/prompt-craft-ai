@props([
    'modalId' => 'chatbot-modal',
    'title' => 'Выберите чат-бот',
    'prompt' => '',
    'encodedPrompt' => '',
])

<div id="{{ $modalId }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" data-chatbot-modal>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="modal-brand max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0"
            data-modal-content>

            <!-- Заголовок модального окна -->
            <div class="flex items-center justify-between px-6 py-4">
                <h3 data-modal-title class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                <button data-modal-close class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Список чат-ботов -->
            <div class="px-6 pb-6">
                <div class="space-y-3">
                    <!-- ChatGPT -->
                    <button data-chatbot-option="chatgpt"
                        class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">C</span>
                            </div>
                            <span class="font-medium">ChatGPT</span>
                        </div>
                    </button>

                    <!-- Claude -->
                    <button data-chatbot-option="claude"
                        class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">C</span>
                            </div>
                            <span class="font-medium">Claude</span>
                        </div>
                    </button>

                    <!-- Gemini -->
                    <button data-chatbot-option="gemini"
                        class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">G</span>
                            </div>
                            <span class="font-medium">Gemini</span>
                        </div>
                    </button>

                    <!-- Perplexity -->
                    <button data-chatbot-option="perplexity"
                        class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">P</span>
                            </div>
                            <span class="font-medium">Perplexity</span>
                        </div>
                    </button>

                    <!-- DeepSeek -->
                    <button data-chatbot-option="deepseek"
                        class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">D</span>
                            </div>
                            <span class="font-medium">DeepSeek</span>
                        </div>
                    </button>

                    <!-- Qwen -->
                    <button data-chatbot-option="qwen"
                        class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">Q</span>
                            </div>
                            <span class="font-medium">Qwen</span>
                        </div>
                    </button>

                    <!-- Яндекс Алиса -->
                    <button data-chatbot-option="alice"
                        class="w-full p-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">Я</span>
                            </div>
                            <span class="font-medium">Яндекс Алиса</span>
                        </div>
                    </button>
                </div>

                <!-- Кнопка отмены -->
                <div class="my-3 flex justify-end">
                    <button data-chatbot-modal-cancel
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Отмена
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
