<!-- Обычный запрос пользователя -->
<div class="flex items-start justify-end p-2 md:p-4">
    <div class="ml-2 md:ml-16 flex items-start space-x-3 max-w-sm md:max-w-2xl">
        <div class="flex-1">
            <div class="bg-blue-500 text-white rounded-lg p-3 ml-auto">
                <p>{{ $message }}</p>
            </div>
        </div>
        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
            <span class="text-white text-sm font-bold">{{ substr(Auth::user()->name ?? Auth::user()->email, 0, 1) }}</span>
        </div>
    </div>
</div>
