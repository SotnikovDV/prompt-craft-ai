<!-- Приветственное сообщение -->
<div id="welcome-message" class="p-8 text-center">
    {{-- <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
        </svg>
    </div> --}}
    <h2 class="text-gradient-hero text-2xl font-bold mb-2">Добро пожаловать в Толкователь ИИ</h2>
    <p class="text-gray-600 mb-6">Напишите что хотите, а я превращу это в профессиональный промпт для любой ИИ-модели</p>
    <div class="flex flex-wrap justify-center gap-2">
        <button onclick="setExamplePrompt('Напиши статью о машинном обучении')"
            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition-colors">
            Напиши статью о машинном обучении
        </button>
        <button onclick="setExamplePrompt('Создай план маркетинговой кампании')"
            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition-colors">
            Создай план маркетинговой кампании
        </button>
        <button onclick="setExamplePrompt('Объясни квантовую физику простыми словами')"
            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition-colors">
            Объясни квантовую физику простыми словами
        </button>
    </div>
</div>
