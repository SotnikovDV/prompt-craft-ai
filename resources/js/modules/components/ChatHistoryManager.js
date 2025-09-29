/**
 * ChatHistoryManager - управление историей чатов
 * Отвечает за загрузку, отображение и управление историей чатов в левой панели
 */
export class ChatHistoryManager {
    constructor(container) {
        this.container = container;
        this.historyContainer = container.querySelector('#chat-history');
        this.loadingElement = container.querySelector('#history-loading');
        this.emptyElement = container.querySelector('#history-empty');
        this.currentChatId = null;
        this.chats = [];

        this.init();
    }

    /**
     * Инициализация менеджера
     */
    init() {
        console.log('ChatHistoryManager: Инициализация');

        // Определяем активный чат из URL
        this.setActiveChatFromUrl();

        this.loadChatHistory();
    }

    /**
     * Установка активного чата на основе URL
     */
    setActiveChatFromUrl() {
        const path = window.location.pathname;
        const chatMatch = path.match(/^\/chat\/(\d+)$/);
        if (chatMatch) {
            this.currentChatId = chatMatch[1];
        }
    }

    /**
     * Загрузка истории чатов с сервера
     */
    async loadChatHistory() {
        try {
            console.log('ChatHistoryManager: Загрузка истории чатов');

            const response = await fetch('/api/sessions', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load chat history');
            }

            const data = await response.json();
            // Преобразуем sessions в chats для совместимости
            this.chats = (data.sessions || []).map(session => ({
                id: session.id,
                name: session.name,
                description: session.description,
                messages: [{
                    content: session.latest_request || session.name
                }],
                created_at: session.created_at,
                updated_at: session.updated_at
            }));
            this.renderChatHistory();

        } catch (error) {
            console.error('ChatHistoryManager: Ошибка загрузки истории:', error);
            this.showHistoryError();
        }
    }

    /**
     * Отображение истории чатов
     */
    renderChatHistory() {
        console.log('ChatHistoryManager: Отображение истории чатов', this.chats.length);

        // Скрываем индикатор загрузки
        if (this.loadingElement) {
            this.loadingElement.classList.add('hidden');
        }

        // Показываем пустое состояние если нет чатов
        if (this.chats.length === 0) {
            if (this.emptyElement) {
                this.emptyElement.classList.remove('hidden');
            }
            return;
        }

        // Скрываем пустое состояние
        if (this.emptyElement) {
            this.emptyElement.classList.add('hidden');
        }

        // Очищаем контейнер и добавляем чаты
        this.historyContainer.innerHTML = '';
        this.chats.forEach(chat => {
            const chatElement = this.createChatHistoryElement(chat);
            this.historyContainer.appendChild(chatElement);
        });
    }

    /**
     * Создание элемента истории чата
     */
    createChatHistoryElement(chat) {
        const div = document.createElement('div');
        div.className = `chat-item p-1 rounded-lg hover:bg-gray-100 transition-colors ${
            this.currentChatId === chat.id ? 'bg-blue-50 border-l-2 border-blue-500' : ''
        }`;
        div.setAttribute('data-chat-id', chat.id);

        const timeAgo = this.formatTimeAgo(chat.updated_at);
        const firstMessage = chat.messages && chat.messages[0]
            ? chat.messages[0].content.substring(0, 50) + '...'
            : 'Новый чат';

        div.innerHTML = `
            <div class="flex items-start justify-between">
                <a href="/chat/${chat.id}" class="flex-1 min-w-0 block hover:no-underline">
                    <h3 class="text-sm font-medium text-gray-900 truncate">${this.escapeHtml(firstMessage)}</h3>
                    <p class="text-xs text-gray-400 mt-0">${timeAgo}</p>
                </a>
                <button onclick="event.stopPropagation(); window.chatHistoryManager.deleteChat('${chat.id}')"
                    class="p-1 text-gray-400 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100"
                    title="Удалить чат">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;

        return div;
    }

    /**
     * Загрузка конкретного чата
     */
    async loadChat(chatId) {
        console.log('ChatHistoryManager: Загрузка чата', chatId);

        // Обновляем активный чат
        this.setActiveChat(chatId);

        // Загружаем данные чата с сервера
        try {
            const response = await fetch(`/api/sessions/${chatId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load chat data');
            }

            const chatData = await response.json();

            // Показываем чат через ChatDisplayManager
            if (window.chatDisplayManager) {
                window.chatDisplayManager.showChatHistory(chatData);
            }

        } catch (error) {
            console.error('ChatHistoryManager: Ошибка загрузки чата:', error);
            if (window.infoModalManager) {
                window.infoModalManager.showError('Ошибка загрузки', 'Не удалось загрузить чат');
            }
        }

        // Отправляем событие для загрузки чата
        this.container.dispatchEvent(new CustomEvent('chat-loaded', {
            detail: { chatId }
        }));
    }

    /**
     * Установка активного чата
     */
    setActiveChat(chatId) {
        this.currentChatId = chatId;

        // Обновляем визуальное состояние
        const chatItems = this.historyContainer.querySelectorAll('.chat-item');
        chatItems.forEach(item => {
            const itemChatId = item.getAttribute('data-chat-id');
            if (itemChatId === chatId) {
                item.classList.add('bg-blue-50', 'border-l-2', 'border-blue-500');
            } else {
                item.classList.remove('bg-blue-50', 'border-l-2', 'border-blue-500');
            }
        });
    }

    /**
     * Удаление чата
     */
    async deleteChat(chatId) {
        console.log('ChatHistoryManager: Удаление чата', chatId);

        if (!confirm('Вы уверены, что хотите удалить этот чат?')) {
            return;
        }

        try {
            const response = await fetch(`/api/sessions/${chatId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Failed to delete chat');
            }

            // Удаляем чат из локального списка
            this.chats = this.chats.filter(chat => chat.id !== chatId);

            // Если удаленный чат был активным, сбрасываем активность
            if (this.currentChatId === chatId) {
                this.currentChatId = null;
            }

            // Перерисовываем историю
            this.renderChatHistory();

            // Отправляем событие об удалении чата
            this.container.dispatchEvent(new CustomEvent('chat-deleted', {
                detail: { chatId }
            }));

        } catch (error) {
            console.error('ChatHistoryManager: Ошибка удаления чата:', error);
            if (window.infoModalManager) {
                window.infoModalManager.showError('Ошибка удаления', 'Не удалось удалить чат');
            }
        }
    }

    /**
     * Добавление нового чата в историю
     */
    addChat(chat) {
        console.log('ChatHistoryManager: Добавление нового чата', chat);

        // Добавляем чат в начало списка
        this.chats.unshift(chat);

        // Перерисовываем историю
        this.renderChatHistory();

        // Устанавливаем новый чат как активный
        this.setActiveChat(chat.id);
    }

    /**
     * Обновление существующего чата
     */
    updateChat(chatId, updates) {
        console.log('ChatHistoryManager: Обновление чата', chatId, updates);

        const chatIndex = this.chats.findIndex(chat => chat.id === chatId);
        if (chatIndex !== -1) {
            this.chats[chatIndex] = { ...this.chats[chatIndex], ...updates };
            this.renderChatHistory();
        }
    }

    /**
     * Очистка истории
     */
    clearHistory() {
        console.log('ChatHistoryManager: Очистка истории');

        this.chats = [];
        this.currentChatId = null;
        this.renderChatHistory();
    }

    /**
     * Получение активного чата
     */
    getActiveChat() {
        return this.chats.find(chat => chat.id === this.currentChatId);
    }

    /**
     * Получение всех чатов
     */
    getAllChats() {
        return this.chats;
    }

    /**
     * Показ ошибки загрузки истории
     */
    showHistoryError() {
        if (this.loadingElement) {
            this.loadingElement.classList.add('hidden');
        }

        if (this.historyContainer) {
            this.historyContainer.innerHTML = `
                <div class="p-4 text-center">
                    <svg class="w-8 h-8 text-red-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <p class="text-xs text-red-500">Ошибка загрузки</p>
                    <button onclick="window.chatHistoryManager.loadChatHistory()"
                        class="mt-1 text-xs text-blue-500 hover:text-blue-700">
                        Попробовать снова
                    </button>
                </div>
            `;
        }
    }

    /**
     * Форматирование времени (время назад)
     */
    formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) {
            return 'Только что';
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            return `${minutes} мин назад`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return `${hours} ч назад`;
        } else if (diffInSeconds < 604800) {
            const days = Math.floor(diffInSeconds / 86400);
            return `${days} дн назад`;
        } else {
            return date.toLocaleDateString('ru-RU');
        }
    }

    /**
     * Экранирование HTML
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}
