/**
 * Менеджер для модального окна выбора чат-бота
 *
 * Основные функции:
 * - Отображение модального окна с выбором чат-ботов
 * - Обработка выбора пользователя (открытие бота или копирование промпта)
 * - Копирование промпта в буфер обмена
 * - Управление анимациями открытия/закрытия
 *
 * Поддерживаемые чат-боты:
 * - ChatGPT, Claude, Perplexity - открываются с промптом в URL
 * - Gemini, DeepSeek, Qwen, Яндекс Алиса - промпт копируется в буфер обмена
 */
export class ChatBotModalManager {
    /**
     * Конструктор класса ChatBotModalManager
     *
     * @param {HTMLElement} container - DOM-элемент контейнера модального окна
     *
     * Инициализирует все необходимые DOM-элементы и состояние менеджера
     */
    constructor(container) {
        // Основной контейнер модального окна
        this.container = container;

        // Проверяем, является ли сам контейнер модальным окном
        // или модальное окно находится внутри контейнера
        if (container.hasAttribute('data-chatbot-modal')) {
            this.modal = container;
        } else {
            this.modal = container.querySelector('[data-chatbot-modal]');
        }

        // Контентная часть модального окна (для анимаций)
        this.modalContent = container.querySelector('[data-modal-content]');

        // Заголовок модального окна
        this.modalTitle = container.querySelector('[data-modal-title]');

        // Кнопка закрытия (крестик в правом верхнем углу)
        this.modalClose = container.querySelector('[data-modal-close]');

        // Кнопка отмены (внизу модального окна)
        this.modalCancel = container.querySelector('[data-chatbot-modal-cancel]');

        // Кнопка "Скопировать промпт"
        this.copyPromptButton = container.querySelector('[data-copy-prompt]');

        // Все опции чат-ботов (кнопки выбора)
        this.chatbotOptions = container.querySelectorAll('[data-chatbot-option]');

        // Флаг состояния модального окна
        this.isOpen = false;

        // Текущий промпт (обычный текст)
        this.currentPrompt = '';

        // Текущий промпт (URL-encoded для передачи в URL)
        this.currentEncodedPrompt = '';

        // Запускаем инициализацию
        this.init();
    }

    /**
     * Инициализация менеджера
     * Вызывается автоматически из конструктора
     */
    init() {
        this.setupEventListeners();
    }

    /**
     * Настройка всех обработчиков событий
     *
     * Обрабатывает:
     * - Клики по кнопкам закрытия
     * - Клик по оверлею (затемненная область вне модального окна)
     * - Выбор чат-бота из списка
     * - Копирование промпта
     * - Закрытие по клавише Escape
     */
    setupEventListeners() {
        // Обработчик кнопки закрытия (крестик)
        if (this.modalClose) {
            this.modalClose.addEventListener('click', () => this.hide());
        }

        // Обработчик кнопки отмены
        if (this.modalCancel) {
            this.modalCancel.addEventListener('click', () => this.hide());
        }

        // Закрытие при клике по оверлею (вне контента модального окна)
        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.hide();
                }
            });
        }

        // Обработчики выбора чат-бота
        this.chatbotOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                // Получаем тип чат-бота из атрибута data-chatbot-option
                const chatbotType = e.currentTarget.getAttribute('data-chatbot-option');
                this.handleChatbotSelection(chatbotType);
            });
        });

        // Обработчик кнопки "Скопировать промпт"
        if (this.copyPromptButton) {
            this.copyPromptButton.addEventListener('click', () => {
                this.copyPromptToClipboard();
            });
        }

        // Закрытие модального окна по клавише Escape
        document.addEventListener('keydown', (e) => {
            if (this.isOpen && e.key === 'Escape') {
                this.hide();
            }
        });
    }

    /**
     * Показать модальное окно с выбором чат-ботов
     *
     * @param {string} prompt - Текст промпта для отправки
     * @param {string} encodedPrompt - URL-encoded версия промпта
     *
     * Выполняет:
     * - Сохранение промпта в состояние
     * - Отображение модального окна
     * - Анимацию появления (scale + opacity)
     * - Установку фокуса на первую опцию
     */
    show(prompt, encodedPrompt) {
        // Проверка наличия необходимых DOM-элементов
        if (!this.modal || !this.modalContent) {
            console.error('ChatBotModalManager: Модальное окно или его контент не найдены!');
            return;
        }

        // Сохраняем промпт в состояние менеджера
        this.currentPrompt = prompt;
        this.currentEncodedPrompt = encodedPrompt;

        // Показываем модальное окно (убираем класс hidden)
        this.modal.classList.remove('hidden');
        this.isOpen = true;

        // Анимация появления через requestAnimationFrame для плавности
        // (гарантирует, что браузер применил изменения перед анимацией)
        requestAnimationFrame(() => {
            this.modalContent.classList.remove('scale-95', 'opacity-0');
            this.modalContent.classList.add('scale-100', 'opacity-100');
        });

        // Устанавливаем фокус на первую опцию чат-бота (для навигации с клавиатуры)
        if (this.chatbotOptions.length > 0) {
            this.chatbotOptions[0].focus();
        }
    }

    /**
     * Скрыть модальное окно
     *
     * Выполняет:
     * - Анимацию исчезновения (scale + opacity)
     * - Скрытие модального окна через 300ms (после завершения анимации)
     * - Обновление флага состояния
     */
    hide() {
        // Если модальное окно уже закрыто, ничего не делаем
        if (!this.isOpen) return;

        // Запускаем анимацию исчезновения
        this.modalContent.classList.remove('scale-100', 'opacity-100');
        this.modalContent.classList.add('scale-95', 'opacity-0');

        // Полностью скрываем модальное окно после завершения анимации (300ms)
        setTimeout(() => {
            this.modal.classList.add('hidden');
            this.isOpen = false;
        }, 300);
    }

    /**
     * Обработка выбора чат-бота пользователем
     *
     * @param {string} chatbotType - Тип выбранного чат-бота (chatgpt, claude, gemini и т.д.)
     *
     * Два режима работы:
     * 1. Прямая отправка (ChatGPT, Claude, Perplexity) - промпт добавляется в URL
     * 2. Копирование промпта (Gemini, DeepSeek, Qwen, Alice) - промпт копируется в буфер
     */
    handleChatbotSelection(chatbotType) {
        // URL чат-ботов, поддерживающих передачу промпта через URL-параметр
        const chatbotUrls = {
            'chatgpt': `https://chat.openai.com/?prompt=${this.currentEncodedPrompt}`,
            'claude': `https://claude.ai/?prompt=${this.currentEncodedPrompt}`,
            'perplexity': `https://www.perplexity.ai/?q=${this.currentEncodedPrompt}`
        };

        // Чат-боты, для которых нужно сначала скопировать промпт в буфер обмена
        // (не поддерживают передачу промпта через URL)
        const copyToClipboardBots = ['gemini', 'deepseek', 'qwen', 'alice'];

        if (copyToClipboardBots.includes(chatbotType)) {
            // Режим копирования: копируем промпт и показываем инструкцию
            this.handleCopyPrompt(chatbotType);
        } else {
            // Режим прямой отправки: открываем бота с промптом в URL
            const url = chatbotUrls[chatbotType];
            if (url) {
                // Открываем чат-бота в новой вкладке с промптом
                window.open(url, '_blank');
                // Закрываем модальное окно (уведомление не показываем)
                this.hide();
            } else {
                console.error('Неизвестный тип чат-бота:', chatbotType);
            }
        }
    }

    /**
     * Обработка копирования промпта для ботов, не поддерживающих URL-параметры
     *
     * @param {string} chatbotType - Тип чат-бота (gemini, deepseek, qwen, alice)
     *
     * Выполняет:
     * - Копирование промпта в буфер обмена
     * - Показ модального окна с инструкцией
     * - Предложение открыть чат-бота
     */
    handleCopyPrompt(chatbotType) {
        // Копируем промпт в буфер обмена
        this.copyPromptToClipboard(() => {
            // После успешного копирования показываем специальное модальное окно
            // с кнопкой перехода к чат-боту
            this.showCopyPromptModal(chatbotType);
            // Закрываем основное модальное окно выбора
            this.hide();
        });
    }

    /**
     * Копирование промпта в буфер обмена
     *
     * @param {Function} onSuccess - Callback-функция, вызываемая после успешного копирования
     *
     * Использует современный Clipboard API (navigator.clipboard).
     * При ошибке автоматически переключается на fallback-метод (document.execCommand)
     */
    async copyPromptToClipboard(onSuccess) {
        try {
            // Современный метод копирования через Clipboard API
            await navigator.clipboard.writeText(this.currentPrompt);
            // Вызываем callback при успешном копировании
            if (onSuccess) onSuccess();
        } catch (err) {
            // Если Clipboard API не поддерживается или недоступен,
            // используем старый метод через document.execCommand
            this.copyWithFallback(this.currentPrompt, onSuccess);
        }
    }

    /**
     * Fallback-метод копирования для старых браузеров
     *
     * @param {string} text - Текст для копирования
     * @param {Function} onSuccess - Callback-функция при успехе
     *
     * Использует document.execCommand('copy') - устаревший, но надежный метод.
     * Создает невидимый textarea, копирует из него текст и удаляет элемент.
     */
    copyWithFallback(text, onSuccess) {
        // Создаем невидимый textarea для копирования
        const textArea = document.createElement('textarea');
        textArea.value = text;

        // Стилизация для невидимости (но доступности для копирования)
        textArea.style.position = 'fixed';
        textArea.style.top = '0';
        textArea.style.left = '0';
        textArea.style.width = '2em';
        textArea.style.height = '2em';
        textArea.style.padding = '0';
        textArea.style.border = 'none';
        textArea.style.outline = 'none';
        textArea.style.boxShadow = 'none';
        textArea.style.background = 'transparent';
        textArea.style.opacity = '0';
        textArea.style.zIndex = '-1';

        // Добавляем textarea в DOM
        document.body.appendChild(textArea);
        // Устанавливаем фокус и выделяем текст
        textArea.focus();
        textArea.select();

        try {
            // Пытаемся скопировать выделенный текст
            const successful = document.execCommand('copy');
            if (successful && onSuccess) {
                onSuccess();
            }
        } catch (err) {
            console.error('Ошибка копирования:', err);
        }

        // Удаляем временный textarea
        document.body.removeChild(textArea);
    }

    /**
     * Показать визуальное уведомление об успешном копировании
     *
     * Изменяет текст и цвет кнопки "Скопировать промпт" на 2 секунды,
     * затем возвращает исходное состояние.
     *
     * Примечание: В текущей реализации эта функция не используется,
     * так как вместо этого показывается модальное окно через showCopyPromptModal()
     */
    showCopySuccessNotification() {
        // Сохраняем оригинальный текст кнопки
        const originalText = this.copyPromptButton.textContent;

        // Изменяем текст и стиль кнопки (зеленый = успех)
        this.copyPromptButton.textContent = 'Скопировано!';
        this.copyPromptButton.classList.add('bg-green-500', 'hover:bg-green-500');
        this.copyPromptButton.classList.remove('bg-blue-500', 'hover:bg-blue-600');

        // Возвращаем исходное состояние через 2 секунды
        setTimeout(() => {
            this.copyPromptButton.textContent = originalText;
            this.copyPromptButton.classList.remove('bg-green-500', 'hover:bg-green-500');
            this.copyPromptButton.classList.add('bg-blue-500', 'hover:bg-blue-600');
        }, 2000);
    }

    /**
     * Показать модальное окно с инструкцией после копирования промпта
     *
     * @param {string} chatbotType - Тип чат-бота (gemini, deepseek, qwen, alice)
     *
     * Создает и отображает модальное окно с:
     * - Информацией об успешном копировании
     * - Иконкой выбранного чат-бота
     * - Инструкцией для пользователя
     * - Кнопкой "Открыть [БотName]" для перехода к боту
     * - Кнопкой "Отмена"
     *
     * Модальное окно создается динамически и удаляется после закрытия
     */
    showCopyPromptModal(chatbotType) {
        // Данные о чат-ботах: название, URL, цвета, иконка
        const botData = {
            'gemini': {
                name: 'Gemini',
                url: 'https://gemini.google.com/',
                iconBgClass: 'bg-blue-100',          // Цвет фона иконки
                iconTextClass: 'text-blue-600',      // Цвет текста иконки
                buttonClass: 'bg-blue-500 hover:bg-blue-600',  // Цвет кнопки
                icon: 'G'                            // Буква для иконки
            },
            'deepseek': {
                name: 'DeepSeek',
                url: 'https://chat.deepseek.com/',
                iconBgClass: 'bg-indigo-100',
                iconTextClass: 'text-indigo-600',
                buttonClass: 'bg-indigo-500 hover:bg-indigo-600',
                icon: 'D'
            },
            'qwen': {
                name: 'Qwen',
                url: 'https://chat.qwen.ai/',
                iconBgClass: 'bg-red-100',
                iconTextClass: 'text-red-600',
                buttonClass: 'bg-red-500 hover:bg-red-600',
                icon: 'Q'
            },
            'alice': {
                name: 'Яндекс Алиса',
                url: 'https://alice.yandex.ru/',
                iconBgClass: 'bg-yellow-100',
                iconTextClass: 'text-yellow-600',
                buttonClass: 'bg-yellow-500 hover:bg-yellow-600',
                icon: 'Я'
            }
        };

        // Получаем данные выбранного бота
        const currentBot = botData[chatbotType];
        if (!currentBot) return;

        // Создаем модальное окно динамически
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="text-center">
                    <!-- Иконка чат-бота -->
                    <div class="w-16 h-16 ${currentBot.iconBgClass} rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="${currentBot.iconTextClass} text-2xl font-bold">${currentBot.icon}</span>
                    </div>

                    <!-- Заголовок -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Промпт скопирован!</h3>

                    <!-- Инструкция -->
                    <p class="text-gray-600 mb-6">
                        Ваш промпт скопирован в буфер обмена. После открытия ${currentBot.name} вставьте его в поле для ввода (Ctrl+V).
                    </p>

                    <!-- Кнопки действий -->
                    <div class="flex justify-center space-x-3">
                        <!-- Кнопка "Открыть [БотName]" -->
                        <button onclick="openBotAndClose('${chatbotType}')"
                            class="px-6 py-2 ${currentBot.buttonClass} text-white rounded-lg transition-colors">
                            Открыть ${currentBot.name}
                        </button>

                        <!-- Кнопка "Отмена" -->
                        <button onclick="closeCopyPromptModal()"
                            class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            Отмена
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Добавляем модальное окно в DOM
        document.body.appendChild(modal);

        /**
         * Глобальная функция для открытия чат-бота и закрытия модального окна
         * Вызывается при клике на кнопку "Открыть [БотName]"
         *
         * @param {string} botName - Тип чат-бота для открытия
         */
        window.openBotAndClose = function(botName) {
            const bot = botData[botName];
            if (bot) {
                // Открываем чат-бота в новой вкладке
                window.open(bot.url, '_blank');
            }
            // Закрываем модальное окно
            closeCopyPromptModal();
        };

        /**
         * Глобальная функция для закрытия модального окна
         * Вызывается при клике на кнопку "Отмена" или после открытия бота
         *
         * Удаляет модальное окно из DOM и очищает глобальные функции
         */
        window.closeCopyPromptModal = function() {
            // Удаляем модальное окно из DOM
            document.body.removeChild(modal);
            // Очищаем глобальные функции (предотвращаем утечки памяти)
            delete window.openBotAndClose;
            delete window.closeCopyPromptModal;
        };
    }

    /**
     * Проверить, открыто ли модальное окно
     *
     * @returns {boolean} true если модальное окно открыто, false если закрыто
     */
    isModalOpen() {
        return this.isOpen;
    }

    /**
     * Получить текущий промпт (обычный текст)
     *
     * @returns {string} Текст текущего промпта
     */
    getCurrentPrompt() {
        return this.currentPrompt;
    }

    /**
     * Получить текущий промпт в URL-encoded формате
     *
     * @returns {string} URL-encoded версия текущего промпта
     *
     * Используется для передачи промпта в URL чат-ботов,
     * поддерживающих URL-параметры (ChatGPT, Claude, Perplexity)
     */
    getCurrentEncodedPrompt() {
        return this.currentEncodedPrompt;
    }
}
