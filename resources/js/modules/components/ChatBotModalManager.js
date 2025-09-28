/**
 * Менеджер для модального окна выбора чат-бота
 * Управляет отображением списка чат-ботов и отправкой промпта в выбранный бот
 */
export class ChatBotModalManager {
    constructor(container) {
        this.container = container;

        // Проверяем, является ли сам контейнер модальным окном
        if (container.hasAttribute('data-chatbot-modal')) {
            this.modal = container;
        } else {
            this.modal = container.querySelector('[data-chatbot-modal]');
        }

        this.modalContent = container.querySelector('[data-modal-content]');
        this.modalTitle = container.querySelector('[data-modal-title]');
        this.modalClose = container.querySelector('[data-modal-close]');
        this.modalCancel = container.querySelector('[data-chatbot-modal-cancel]');
        this.copyPromptButton = container.querySelector('[data-copy-prompt]');

        this.chatbotOptions = container.querySelectorAll('[data-chatbot-option]');

        this.isOpen = false;
        this.currentPrompt = '';
        this.currentEncodedPrompt = '';

        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        if (this.modalClose) {
            this.modalClose.addEventListener('click', () => this.hide());
        }

        if (this.modalCancel) {
            this.modalCancel.addEventListener('click', () => this.hide());
        }

        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.hide();
                }
            });
        }

        this.chatbotOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                const chatbotType = e.currentTarget.getAttribute('data-chatbot-option');
                this.handleChatbotSelection(chatbotType);
            });
        });

        if (this.copyPromptButton) {
            this.copyPromptButton.addEventListener('click', () => {
                this.copyPromptToClipboard();
            });
        }

        document.addEventListener('keydown', (e) => {
            if (this.isOpen && e.key === 'Escape') {
                this.hide();
            }
        });
    }

    show(prompt, encodedPrompt) {
        if (!this.modal || !this.modalContent) {
            console.error('ChatBotModalManager: Модальное окно или его контент не найдены!');
            return;
        }

        this.currentPrompt = prompt;
        this.currentEncodedPrompt = encodedPrompt;

        this.modal.classList.remove('hidden');
        this.isOpen = true;

        requestAnimationFrame(() => {
            this.modalContent.classList.remove('scale-95', 'opacity-0');
            this.modalContent.classList.add('scale-100', 'opacity-100');
        });

        if (this.chatbotOptions.length > 0) {
            this.chatbotOptions[0].focus();
        }
    }

    hide() {
        if (!this.isOpen) return;

        this.modalContent.classList.remove('scale-100', 'opacity-100');
        this.modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            this.modal.classList.add('hidden');
            this.isOpen = false;
        }, 300);
    }

    handleChatbotSelection(chatbotType) {
        const chatbotUrls = {
            'chatgpt': `https://chat.openai.com/?prompt=${this.currentEncodedPrompt}`,
            'claude': `https://claude.ai/?prompt=${this.currentEncodedPrompt}`,
            'perplexity': `https://www.perplexity.ai/?q=${this.currentEncodedPrompt}`
        };

        // Для некоторых ботов используем копирование в буфер обмена
        const copyToClipboardBots = ['gemini', 'deepseek', 'qwen', 'alice'];

        if (copyToClipboardBots.includes(chatbotType)) {
            this.handleCopyPrompt(chatbotType);
        } else {
            const url = chatbotUrls[chatbotType];
            if (url) {
                window.open(url, '_blank');
                // НЕ показываем уведомление для обычных ботов
                this.hide();
            } else {
                console.error('Неизвестный тип чат-бота:', chatbotType);
            }
        }
    }

    handleCopyPrompt(chatbotType) {
        // Копируем промпт в буфер обмена
        this.copyPromptToClipboard(() => {
            // После успешного копирования показываем специальное модальное окно
            this.showCopyPromptModal(chatbotType);
            // Закрываем основное модальное окно
            this.hide();
        });
    }

    async copyPromptToClipboard(onSuccess) {
        try {
            await navigator.clipboard.writeText(this.currentPrompt);
            if (onSuccess) onSuccess();
        } catch (err) {
            this.copyWithFallback(this.currentPrompt, onSuccess);
        }
    }

    copyWithFallback(text, onSuccess) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
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

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful && onSuccess) {
                onSuccess();
            }
        } catch (err) {
            console.error('Ошибка копирования:', err);
        }

        document.body.removeChild(textArea);
    }

    showCopySuccessNotification() {
        const originalText = this.copyPromptButton.textContent;
        this.copyPromptButton.textContent = 'Скопировано!';
        this.copyPromptButton.classList.add('bg-green-500', 'hover:bg-green-500');
        this.copyPromptButton.classList.remove('bg-blue-500', 'hover:bg-blue-600');

        setTimeout(() => {
            this.copyPromptButton.textContent = originalText;
            this.copyPromptButton.classList.remove('bg-green-500', 'hover:bg-green-500');
            this.copyPromptButton.classList.add('bg-blue-500', 'hover:bg-blue-600');
        }, 2000);
    }

    showCopyPromptModal(chatbotType) {
        const botData = {
            'gemini': {
                name: 'Gemini',
                url: 'https://gemini.google.com/',
                iconBgClass: 'bg-blue-100',
                iconTextClass: 'text-blue-600',
                buttonClass: 'bg-blue-500 hover:bg-blue-600',
                icon: 'G'
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

        const currentBot = botData[chatbotType];
        if (!currentBot) return;

        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="text-center">
                    <div class="w-16 h-16 ${currentBot.iconBgClass} rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="${currentBot.iconTextClass} text-2xl font-bold">${currentBot.icon}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Промпт скопирован!</h3>
                    <p class="text-gray-600 mb-6">
                        Ваш промпт скопирован в буфер обмена. После открытия ${currentBot.name} вставьте его в поле для ввода (Ctrl+V).
                    </p>
                    <div class="flex justify-center space-x-3">
                        <button onclick="openBotAndClose('${chatbotType}')"
                            class="px-6 py-2 ${currentBot.buttonClass} text-white rounded-lg transition-colors">
                            Открыть ${currentBot.name}
                        </button>
                        <button onclick="closeCopyPromptModal()"
                            class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            Отмена
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Функция для открытия бота и закрытия модального окна
        window.openBotAndClose = function(botName) {
            const bot = botData[botName];
            if (bot) {
                window.open(bot.url, '_blank');
            }
            closeCopyPromptModal();
        };

        // Функция для закрытия модального окна
        window.closeCopyPromptModal = function() {
            document.body.removeChild(modal);
            delete window.openBotAndClose;
            delete window.closeCopyPromptModal;
        };
    }

    isModalOpen() {
        return this.isOpen;
    }

    getCurrentPrompt() {
        return this.currentPrompt;
    }

    getCurrentEncodedPrompt() {
        return this.currentEncodedPrompt;
    }
}
