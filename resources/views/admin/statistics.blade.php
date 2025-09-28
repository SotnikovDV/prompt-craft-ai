@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Заголовок страницы -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Статистика сервиса</h1>
            <p class="mt-2 text-gray-600">Аналитика использования ИИ-сервиса</p>
        </div>

        <!-- Основная статистика -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Всего запросов</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_requests']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Сегодня</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['today_requests']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Среднее время</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['average_execution_time'], 1) }}с</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Всего токенов</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_tokens_in'] + $stats['total_tokens_out']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Детальная статистика -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Статистика по типам пользователей -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">По типам пользователей</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Зарегистрированные пользователи</span>
                            <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['registered_users_requests']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Гости</span>
                            <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['guest_requests']) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total_requests'] > 0 ? ($stats['registered_users_requests'] / $stats['total_requests']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Статистика по периодам -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">По периодам</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Сегодня</span>
                            <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['today_requests']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">На этой неделе</span>
                            <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['this_week_requests']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">В этом месяце</span>
                            <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['this_month_requests']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Топ доменов -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Популярные области знаний</h3>
            </div>
            <div class="p-6">
                @if($topDomains->count() > 0)
                    <div class="space-y-3">
                        @foreach($topDomains as $domain)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">{{ $domain->domain }}</span>
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($domain->count / $topDomains->first()->count) * 100 }}%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $domain->count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Нет данных о доменах</p>
                @endif
            </div>
        </div>

        <!-- График активности по дням -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Активность за последние 30 дней</h3>
            </div>
            <div class="p-6">
                @if($dailyStats->count() > 0)
                    <div class="h-64 flex items-end space-x-1">
                        @php
                            $maxCount = $dailyStats->max('count');
                        @endphp
                        @foreach($dailyStats as $stat)
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-full bg-blue-600 rounded-t" style="height: {{ ($stat->count / $maxCount) * 200 }}px;" title="{{ $stat->date }}: {{ $stat->count }} запросов"></div>
                                <span class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($stat->date)->format('d.m') }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Нет данных за последние 30 дней</p>
                @endif
            </div>
        </div>

        <!-- Навигация -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('admin.settings') }}"
               class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                Настройки
            </a>
            <a href="{{ route('admin.requests') }}"
               class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                Управление запросами
            </a>
        </div>
    </div>
</div>
@endsection
