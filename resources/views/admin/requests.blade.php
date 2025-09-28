@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Заголовок страницы -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Управление запросами</h1>
            <p class="mt-2 text-gray-600">Просмотр и управление запросами пользователей</p>
        </div>

        <!-- Фильтры -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Фильтры</h3>
            </div>
            <form method="GET" action="{{ route('admin.requests') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">ID пользователя</label>
                        <input type="number"
                               id="user_id"
                               name="user_id"
                               value="{{ request('user_id') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="domain" class="block text-sm font-medium text-gray-700 mb-2">Область знаний</label>
                        <select id="domain"
                                name="domain"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Все области</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain }}" {{ request('domain') == $domain ? 'selected' : '' }}>
                                    {{ $domain }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Дата от</label>
                        <input type="date"
                               id="date_from"
                               name="date_from"
                               value="{{ request('date_from') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Дата до</label>
                        <input type="date"
                               id="date_to"
                               name="date_to"
                               value="{{ request('date_to') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-4">
                    <a href="{{ route('admin.requests') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Сбросить
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Применить фильтры
                    </button>
                </div>
            </form>
        </div>

        <!-- Список запросов -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    Запросы ({{ $requests->total() }})
                </h3>
            </div>

            @if($requests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Пользователь</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Запрос</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Область</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Время</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Токены</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $request->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($request->user)
                                            <div>
                                                <div class="font-medium">{{ $request->user->name }}</div>
                                                <div class="text-gray-500">{{ $request->user->email }}</div>
                                            </div>
                                        @else
                                            <span class="text-gray-500">Гость</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="max-w-xs truncate" title="{{ $request->original_request }}">
                                            {{ Str::limit($request->original_request, 50) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($request->domain)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $request->domain }}
                                            </span>
                                        @else
                                            <span class="text-gray-500">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>
                                            <div>{{ $request->created_at->format('d.m.Y H:i') }}</div>
                                            @if($request->execution_time)
                                                <div class="text-gray-500">{{ $request->execution_time }}мс</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($request->tokens_in || $request->tokens_out)
                                            <div>
                                                <div>Вход: {{ $request->tokens_in ?? 0 }}</div>
                                                <div>Выход: {{ $request->tokens_out ?? 0 }}</div>
                                            </div>
                                        @else
                                            <span class="text-gray-500">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="showRequestDetails({{ $request->id }})"
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                            Просмотр
                                        </button>
                                        @if($request->parent_id)
                                            <span class="text-green-600 text-xs">Уточнение</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Пагинация -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $requests->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Нет запросов</h3>
                    <p class="mt-1 text-sm text-gray-500">Запросы не найдены по заданным фильтрам.</p>
                </div>
            @endif
        </div>

        <!-- Навигация -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('admin.settings') }}"
               class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                Настройки
            </a>
            <a href="{{ route('admin.statistics') }}"
               class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                Статистика
            </a>
        </div>
    </div>
</div>

<!-- Модальное окно для просмотра деталей запроса -->
<div id="request-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Детали запроса</h3>
                <button onclick="closeRequestModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div id="request-details">
                    <!-- Содержимое будет загружено через AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showRequestDetails(requestId) {
    const modal = document.getElementById('request-modal');
    const modalContent = document.getElementById('modal-content');
    const detailsContainer = document.getElementById('request-details');

    // Показываем модальное окно
    modal.classList.remove('hidden');

    // Анимация появления
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);

    // Загружаем детали запроса
    detailsContainer.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div><p class="mt-2 text-gray-600">Загрузка...</p></div>';

    // Здесь можно добавить AJAX запрос для получения деталей запроса
    // Пока что показываем заглушку
    setTimeout(() => {
        detailsContainer.innerHTML = `
            <div class="space-y-4">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Исходный запрос:</h4>
                    <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">Загрузка деталей запроса #${requestId}...</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Сгенерированный промпт:</h4>
                    <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">Загрузка...</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Ход рассуждений:</h4>
                    <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">Загрузка...</p>
                </div>
            </div>
        `;
    }, 1000);
}

function closeRequestModal() {
    const modal = document.getElementById('request-modal');
    const modalContent = document.getElementById('modal-content');

    // Анимация закрытия
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Закрытие по клику на фон
document.getElementById('request-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRequestModal();
    }
});

// Закрытие по клавише Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('request-modal').classList.contains('hidden')) {
        closeRequestModal();
    }
});
</script>
@endsection
