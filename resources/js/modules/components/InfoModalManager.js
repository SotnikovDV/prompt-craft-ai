/**
 * Менеджер для универсального модального окна
 * Управляет отображением уведомлений, подтверждений и информационных сообщений
 */
export class InfoModalManager {
    constructor(container) {
        this.container = container;
        this.modal = container.querySelector('[data-info-modal]');
        this.modalContent = container.querySelector('[data-modal-content]');
        this.modalIcon = container.querySelector('[data-modal-icon]');
        this.modalTitle = container.querySelector('[data-modal-title]');
        this.modalMessage = container.querySelector('[data-modal-message]');
        this.modalClose = container.querySelector('[data-modal-close]');
        this.modalCancel = container.querySelector('[data-modal-cancel]');
        this.modalConfirm = container.querySelector('[data-modal-confirm]');

        this.isOpen = false;
        this.currentCallback = null;

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupKeyboardHandlers();
    }

    setupEventListeners() {
        if (this.modalClose) {
            this.modalClose.addEventListener('click', () => {
                this.close();
            });
        }

        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.close();
                }
            });
        }

        if (this.modalConfirm) {
            this.modalConfirm.addEventListener('click', () => {
                this.confirm();
            });
        }

        if (this.modalCancel) {
            this.modalCancel.addEventListener('click', () => {
                this.cancel();
            });
        }
    }

    setupKeyboardHandlers() {
        document.addEventListener('keydown', (e) => {
            if (this.isOpen) {
                if (e.key === 'Escape') {
                    this.close();
                } else if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.confirm();
                }
            }
        });
    }

    show(options = {}) {
        const {
            title = 'Уведомление',
            message = '',
            type = 'info',
            showCancel = false,
            confirmText = 'OK',
            cancelText = 'Отмена',
            confirmClass = null,
            onConfirm = null,
            onCancel = null,
            onClose = null
        } = options;

        console.log('InfoModalManager.show вызван:', { title, message, type });
        console.log('Элементы модального окна:', {
            modal: !!this.modal,
            modalContent: !!this.modalContent,
            modalTitle: !!this.modalTitle,
            modalMessage: !!this.modalMessage
        });

        this.currentCallback = { onConfirm, onCancel, onClose };
        this.updateContent(title, message, type, showCancel, confirmText, cancelText, confirmClass);

        if (this.modal) {
            this.modal.classList.remove('hidden');
            this.isOpen = true;
        } else {
            console.error('InfoModalManager: модальный элемент не найден');
        }

        requestAnimationFrame(() => {
            this.modalContent.classList.remove('scale-95', 'opacity-0');
            this.modalContent.classList.add('scale-100', 'opacity-100');
        });

        if (this.modalConfirm) {
            this.modalConfirm.focus();
        }
    }

    hide() {
        if (!this.isOpen) return;

        this.modalContent.classList.remove('scale-100', 'opacity-100');
        this.modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            this.modal.classList.add('hidden');
            this.isOpen = false;
            this.currentCallback = null;
        }, 300);
    }

    close() {
        if (this.currentCallback && this.currentCallback.onClose) {
            this.currentCallback.onClose();
        }
        this.hide();
    }

    confirm() {
        if (this.currentCallback && this.currentCallback.onConfirm) {
            this.currentCallback.onConfirm();
        }
        this.hide();
    }

    cancel() {
        if (this.currentCallback && this.currentCallback.onCancel) {
            this.currentCallback.onCancel();
        }
        this.hide();
    }

    updateContent(title, message, type, showCancel, confirmText, cancelText, confirmClass = null) {
        if (this.modalTitle) {
            this.modalTitle.textContent = title;
        }

        if (this.modalMessage) {
            this.modalMessage.innerHTML = message;
        }

        this.updateIcon(type);
        this.updateButtons(showCancel, confirmText, cancelText, confirmClass);
    }

    updateIcon(type) {
        if (!this.modalIcon) return;

        const iconConfig = {
            info: {
                bg: 'bg-blue-100',
                icon: `<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>`
            },
            success: {
                bg: 'bg-green-100',
                icon: `<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>`
            },
            error: {
                bg: 'bg-red-100',
                icon: `<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>`
            },
            warning: {
                bg: 'bg-yellow-100',
                icon: `<svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>`
            }
        };

        const config = iconConfig[type] || iconConfig.info;

        this.modalIcon.className = 'w-8 h-8 rounded-full flex items-center justify-center mr-3';
        this.modalIcon.classList.add(config.bg);
        this.modalIcon.innerHTML = config.icon;
    }

    updateButtons(showCancel, confirmText, cancelText, confirmClass = null) {
        if (this.modalCancel) {
            if (showCancel) {
                this.modalCancel.classList.remove('hidden');
                this.modalCancel.textContent = cancelText;
            } else {
                this.modalCancel.classList.add('hidden');
            }
        }

        if (this.modalConfirm) {
            this.modalConfirm.textContent = confirmText;

            // Применяем кастомный класс для кнопки подтверждения (например, красный для удаления)
            if (confirmClass) {
                // Удаляем стандартные классы кнопки
                this.modalConfirm.className = this.modalConfirm.className.replace(/bg-\w+-\d+/g, '').replace(/hover:bg-\w+-\d+/g, '');
                // Добавляем кастомные классы
                this.modalConfirm.classList.add(...confirmClass.split(' '));
            } else {
                // Возвращаем стандартные классы
                this.modalConfirm.className = 'px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors';
            }
        }
    }

    showInfo(title, message, options = {}) {
        this.show({ title, message, type: 'info', ...options });
    }

    showSuccess(title, message, options = {}) {
        this.show({ title, message, type: 'success', ...options });
    }

    showError(title, message, options = {}) {
        this.show({ title, message, type: 'error', ...options });
    }

    showWarning(title, message, options = {}) {
        this.show({ title, message, type: 'warning', ...options });
    }

    /**
     * Показать модальное окно подтверждения с промисом
     *
     * @param {string} title - Заголовок окна
     * @param {string} message - Текст сообщения
     * @param {Object} options - Дополнительные опции
     * @returns {Promise<boolean>} - true если пользователь подтвердил, false если отменил
     */
    showConfirm(title, message, options = {}) {
        return new Promise((resolve) => {
            this.show({
                title,
                message,
                type: 'warning',
                showCancel: true,
                confirmText: options.confirmText || 'Подтвердить',
                cancelText: options.cancelText || 'Отмена',
                onConfirm: () => {
                    resolve(true);
                },
                onCancel: () => {
                    resolve(false);
                },
                onClose: () => {
                    resolve(false);
                },
                ...options
            });
        });
    }

    isModalOpen() {
        return this.isOpen;
    }

    getCurrentOptions() {
        return this.currentCallback;
    }
}
