<!-- Корневой запрос пользователя -->
<div class="flex items-start justify-end p-2 md:p-4">
    <div class="ml-2 md:ml-16 flex items-start space-x-3 max-w-sm md:max-w-2xl">
        <div class="flex-1 relative">

            <div class="bg-blue-500 text-white rounded-lg p-3 ml-auto">
                <p class="font-semibold">{{ $message }}</p>
            </div>
            <div class="absolute bottom-1 right-1 flex flex-col gap-1">
                <!-- Кнопка копирования -->
                <button type="button" data-copy-prompt
                    class="p-1 text-gray-500 hover:text-gray-700 hover:bg-white/50 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                    title="Копировать текст">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
            <span class="text-white text-sm font-bold">{{ substr(Auth::user()->name ?? Auth::user()->email, 0, 1) }}</span>
        </div>
    </div>
</div>
