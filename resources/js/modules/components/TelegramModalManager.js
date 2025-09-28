/**
 * Менеджер для модального окна отправки в Telegram
 * Управляет отображением опций отправки промпта в Telegram
 */
export class TelegramModalManager {
    constructor(container) {
        this.container = container;

        // Проверяем, является ли сам контейнер модальным окном
        if (container.hasAttribute('data-telegram-modal')) {
            this.modal = container;
        } else {
            this.modal = container.querySelector('[data-telegram-modal]');
        }

        this.modalContent = container.querySelector('[data-telegram-modal-content]');
        this.modalTitle = container.querySelector('[data-telegram-modal-title]');
        this.modalClose = container.querySelector('[data-telegram-modal-close]');
        this.modalCancel = container.querySelector('[data-telegram-modal-cancel]');

        this.telegramOptions = container.querySelectorAll('[data-telegram-option]');

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

        this.telegramOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                const telegramType = e.currentTarget.getAttribute('data-telegram-option');
                this.handleTelegramSelection(telegramType);
            });
        });

        document.addEventListener('keydown', (e) => {
            if (this.isOpen && e.key === 'Escape') {
                this.hide();
            }
        });
    }

    show(prompt, encodedPrompt) {
        if (!this.modal || !this.modalContent) {
            console.error('TelegramModalManager: Модальное окно или его контент не найдены!');
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

        if (this.telegramOptions.length > 0) {
            this.telegramOptions[0].focus();
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

    handleTelegramSelection(telegramType) {
        switch (telegramType) {
            case 'web':
                this.openTelegramWeb();
                break;
            case 'app':
                this.openTelegramApp();
                break;
            case 'copy':
                this.copyTelegramLink();
                break;
            default:
                console.error('Неизвестный тип отправки в Telegram:', telegramType);
        }
    }

    openTelegramWeb() {
        const url = `https://web.telegram.org/k/#@?text=${this.currentEncodedPrompt}`;
        window.open(url, '_blank');
        this.hide();
    }

    openTelegramApp() {
        const url = `tg://msg?text=${this.currentEncodedPrompt}`;
        window.location.href = url;
        this.hide();
    }

    async copyTelegramLink() {
        const url = `https://web.telegram.org/k/#@?text=${this.currentEncodedPrompt}`;

        try {
            await navigator.clipboard.writeText(url);
            this.showCopySuccessNotification();
        } catch (err) {
            this.copyWithFallback(url);
        }

        this.hide();
    }

    copyWithFallback(text) {
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
            if (successful) {
                this.showCopySuccessNotification();
            }
        } catch (err) {
            console.error('Ошибка копирования:', err);
        }

        document.body.removeChild(textArea);
    }

    showCopySuccessNotification() {
        if (window.infoModalManager) {
            window.infoModalManager.showSuccess('Ссылка Telegram скопирована в буфер обмена!');
        } else {
            console.log('Ссылка Telegram скопирована в буфер обмена!');
        }
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
