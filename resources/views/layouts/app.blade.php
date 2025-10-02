<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Толкователь ИИ') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Шрифты -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Стили / Скрипты -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen relative" x-data="{ mobileMenuOpen: false }">
        <div class="relative z-10">
            <!-- Заголовок -->
            <header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100 shadow-md">
                <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-14">
                        <!-- Логотип (кликабельный на мобильных) -->
                        <div class="flex items-center">
                            <div class="flex items-center space-x-2 cursor-pointer md:cursor-default"
                                @click="mobileMenuOpen = !mobileMenuOpen">

                                <div class="relative rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="p-1 lucide lucide-sparkles h-8 w-8 rounded-xl bg-gradient-to-br from-blue-500 to-orange-500 text-blue-700">
                                        <path
                                            d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z">
                                        </path>
                                        <path d="M20 3v4"></path>
                                        <path d="M22 5h-4"></path>
                                        <path d="M4 17v2"></path>
                                        <path d="M5 18H3"></path>
                                    </svg>

                                </div>
                                <div class="flex items-center space-x-1">
                                    <span class="text-xl font-bold text-gradient-hero hidden md:block">Толкователь
                                        ИИ</span>
                                    @auth
                                        <span class="text-xl font-bold text-gradient-hero md:hidden">Толкователь ИИ</span>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- Навигация -->
                        <nav class="hidden md:flex items-center space-x-8">
                            <a href="{{ url('/') }}" class="text-gray-700 hover:text-gray-900 font-medium">Главная</a>
                            <a href="{{route('chat')}}" class="text-gray-700 hover:text-gray-900 font-medium">Чат</a>
                            <a href="#" class="text-gray-700 hover:text-gray-900 font-medium">Библиотека</a>

                            <a href="#" class="text-gray-700 hover:text-gray-900 font-medium">Помощь</a>
                        </nav>


                        <!-- Кнопки аутентификации -->
                        <div class="flex items-center space-x-4">
                            @auth
                                <!-- Выпадающее меню пользователя -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open"
                                        class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 font-medium px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                                        <span>{{ Auth::user()->name }}</span>

                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center">
                                            <span
                                                class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        </div>

                                    </button>

                                    <!-- Выпадающее меню -->
                                    <div x-show="open" @click.away="open = false" x-transition
                                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                        <a href="{{ url('/chat') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            Чат
                                        </a>
                                        <a href="{{ route('profile.edit') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            Профиль
                                        </a>
                                        @if (in_array(Auth::user()->email, config('app.admin_emails', [])))
                                            <hr class="my-1">
                                            <a href="{{ route('admin.settings') }}"
                                                class="block px-4 py-2 text-sm text-blue-600 hover:bg-blue-50">
                                                ⚙️ Админ панель
                                            </a>
                                        @endif
                                        <hr class="my-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                Выйти
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-gray-700 hover:text-gray-900 font-medium border border-gray-300 hover:border-gray-400 px-4 py-2 rounded-lg transition-colors">
                                    Войти
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="bg-gradient-to-r from-blue-500 to-orange-500 hover:from-blue-600 hover:to-orange-600 text-white font-medium px-4 py-2 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                                        Регистрация
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            <!-- Мобильное меню -->
            <div x-show="mobileMenuOpen" x-transition
                class="pt-16 md:hidden bg-white border-b border-gray-100 shadow-lg">
                <div class="px-4 py-2 space-y-1">
                    <a href="{{ url('/') }}"
                        class="block px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md font-medium">Главная</a>
                    <a href="{{route('chat')}}"
                        class="block px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md font-medium">Чат</a>
                    <a href="#"
                        class="block px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md font-medium">Библиотека</a>
                    <a href="#"
                        class="block px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md font-medium">Помощь</a>
                </div>
            </div>

            <!-- Основной контент с отступом для закрепленного заголовка -->
            <main class="pt-16 relative">
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-fixed"
                    style="background-image: url('/images/bg_ai.jpg'); opacity: 0.3; z-index: -1;"></div>
                <div class="relative z-10">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Футер сайта -->
        <x-footer />
    </div>
</body>

</html>
