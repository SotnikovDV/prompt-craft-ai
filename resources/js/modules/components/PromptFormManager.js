/**
 * Менеджер для компонента формы промптов
 * Управляет отправкой формы, валидацией, счетчиком символов и дополнительными опциями
 */
export class PromptFormManager {
    constructor(container) {
        this.container = container;
        this.form = container.querySelector('[data-prompt-form-element]');
        this.promptInput = container.querySelector('[data-prompt-input]');
        this.charCount = container.querySelector('[data-char-count]');
        this.submitButton = container.querySelector('[data-prompt-submit]');
        this.clearButton = container.querySelector('[data-clear-prompt]');
        this.copyButton = container.querySelector('[data-copy-prompt]');
        this.advancedOptionsToggle = container.querySelector('[data-advanced-options-toggle]');
        this.advancedOptions = container.querySelector('[data-advanced-options]');
        this.toggleIcon = container.querySelector('[data-toggle-icon]');

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupCharacterCounter();
        this.loadLimitsInfo();
    }

    setupEventListeners() {
        // Обработка отправки формы
        if (this.form) {
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleSubmit();
            });
        }

        // Очистка поля
        if (this.clearButton) {
            this.clearButton.addEventListener('click', () => {
                this.clearPrompt();
            });
        }

        // Копирование текста
        if (this.copyButton) {
            this.copyButton.addEventListener('click', () => {
                this.copyPromptText();
            });
        }

        // Переключение дополнительных параметров
        if (this.advancedOptionsToggle) {
            this.advancedOptionsToggle.addEventListener('click', () => {
                this.toggleAdvancedOptions();
            });
        }

        // Обработка изменений в селектах
        if (this.form) {
            const selects = this.form.querySelectorAll('select');
            selects.forEach(select => {
                select.addEventListener('change', () => {
                    this.emitFormChange();
                });
            });
        }
    }

    setupCharacterCounter() {
        if (this.promptInput && this.charCount) {
            this.promptInput.addEventListener('input', () => {
                const count = this.promptInput.value.length;
                this.charCount.textContent = count;

                if (count < 10) {
                    this.charCount.classList.add('text-red-500');
                    this.charCount.classList.remove('text-gray-500');
                } else {
                    this.charCount.classList.remove('text-red-500');
                    this.charCount.classList.add('text-gray-500');
                }

                this.emitFormChange();
            });
        }
    }

    async handleSubmit() {
        if (!this.validateForm()) {
            return;
        }

        // Скрываем панель результатов при начале новой генерации
        if (window.promptResultManager) {
            window.promptResultManager.hide();
        } else {
            // Альтернативный способ скрытия
            const resultSection = document.getElementById('result-section');
            if (resultSection) {
                resultSection.classList.add('hidden');
            }
        }

        const formData = new FormData(this.form);
        this.setLoadingState(true);

        try {
            const response = await fetch('/generate-prompt', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                if (response.status === 429) {
                    throw new Error('Превышен дневной лимит запросов.');
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                this.container.dispatchEvent(new CustomEvent('prompt-generated', {
                    detail: data
                }));
            } else {
                throw new Error(data.error || 'Неизвестная ошибка');
            }

        } catch (error) {
            console.error('Ошибка при генерации промпта:', error);

            // Проверяем, является ли это ошибкой лимита
            if (error.message.includes('429') || error.message.includes('лимит')) {
                this.container.dispatchEvent(new CustomEvent('prompt-limit-exceeded', {
                    detail: { error: error.message }
                }));
            } else {
                this.container.dispatchEvent(new CustomEvent('prompt-error', {
                    detail: { error: error.message }
                }));
            }
        } finally {
            this.setLoadingState(false);
        }
    }

    validateForm() {
        if (!this.promptInput) return false;

        const promptText = this.promptInput.value.trim();

        if (promptText.length < 10) {
            this.showError('Минимальная длина запроса - 10 символов');
            return false;
        }

        if (promptText.length > 3000) {
            this.showError('Максимальная длина запроса - 3000 символов');
            return false;
        }

        return true;
    }

    setLoadingState(loading) {
        if (!this.submitButton) return;

        if (loading) {
            this.submitButton.disabled = true;
            this.submitButton.innerHTML = `
                <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Создаем промпт...
            `;
        } else {
            this.submitButton.disabled = false;
            this.submitButton.innerHTML = `
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Создать промпт
            `;
        }
    }

    clearPrompt() {
        if (this.promptInput) {
            this.promptInput.value = '';
            this.promptInput.focus();

            if (this.charCount) {
                this.charCount.textContent = '0';
                this.charCount.classList.remove('text-red-500');
                this.charCount.classList.add('text-gray-500');
            }

            this.emitFormChange();
        }
    }

    async copyPromptText() {
        if (!this.promptInput || !this.promptInput.value.trim()) {
            this.showError('Поле ввода пустое. Нечего копировать.');
            return;
        }

        try {
            await navigator.clipboard.writeText(this.promptInput.value);
            this.showSuccess('Текст скопирован в буфер обмена');
        } catch (err) {
            this.promptInput.select();
            document.execCommand('copy');
            this.showSuccess('Текст скопирован в буфер обмена');
        }
    }

    toggleAdvancedOptions() {
        console.log('PromptFormManager: Переключение дополнительных параметров', {
            advancedOptions: !!this.advancedOptions,
            toggleIcon: !!this.toggleIcon
        });

        if (!this.advancedOptions || !this.toggleIcon) {
            console.error('PromptFormManager: Не найдены элементы для переключения');
            return;
        }

        const isHidden = this.advancedOptions.classList.contains('hidden');

        if (isHidden) {
            this.advancedOptions.classList.remove('hidden');
            this.toggleIcon.style.transform = 'rotate(180deg)';
        } else {
            this.advancedOptions.classList.add('hidden');
            this.toggleIcon.style.transform = 'rotate(0deg)';
        }
    }

    async loadLimitsInfo() {
        console.log('PromptFormManager: loadLimitsInfo() вызван');
        const limitsInfo = document.getElementById('limits-info');
        const limitsText = document.getElementById('limits-text');

        if (!limitsInfo || !limitsText) {
            console.log('PromptFormManager: Элементы limits-info или limits-text не найдены');
            return;
        }

        try {
            const response = await fetch('/api/limits', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('PromptFormManager: Получены данные лимитов:', data);
            this.updateLimitsDisplay(data, limitsInfo, limitsText);
        } catch (error) {
            console.error('Ошибка при загрузке лимитов:', error);
            limitsInfo.classList.add('hidden');
        }
    }

    updateLimitsDisplay(data, limitsInfo, limitsText) {
        console.log('PromptFormManager: Обновление отображения лимитов:', data);

        // Сбрасываем классы к исходному состоянию
        limitsInfo.className = limitsInfo.className.replace(/bg-(red|blue)-50/, 'bg-blue-50');
        limitsInfo.className = limitsInfo.className.replace(/border-(red|blue)-200/, 'border-blue-200');
        limitsText.className = limitsText.className.replace(/text-(red|blue)-800/, 'text-blue-800');

        if (!data.is_authenticated) {
            if (data.remaining_requests !== null) {
                if (data.remaining_requests > 0) {
                    limitsText.textContent = `У вас осталось ${data.remaining_requests} из ${data.daily_limit_guest} запросов сегодня`;
                    limitsInfo.classList.remove('hidden');
                } else {
                    limitsText.textContent = `Вы использовали все ${data.daily_limit_guest} запросов сегодня`;
                    limitsInfo.classList.remove('hidden');
                    limitsInfo.className = limitsInfo.className.replace('bg-blue-50 border-blue-200', 'bg-red-50 border-red-200');
                    limitsText.className = limitsText.className.replace('text-blue-800', 'text-red-800');
                }
            }
        } else {
            if (data.remaining_requests === null) {
                limitsInfo.classList.add('hidden');
            } else {
                if (data.remaining_requests > 0) {
                    limitsText.textContent = `У вас осталось ${data.remaining_requests} из ${data.daily_limit_user} запросов сегодня`;
                    limitsInfo.classList.remove('hidden');
                } else {
                    limitsText.textContent = `⚠️ Вы использовали все ${data.daily_limit_user} запросов сегодня`;
                    limitsInfo.classList.remove('hidden');
                    limitsInfo.className = limitsInfo.className.replace('bg-blue-50 border-blue-200', 'bg-red-50 border-red-200');
                    limitsText.className = limitsText.className.replace('text-blue-800', 'text-red-800');
                }
            }
        }
    }

    emitFormChange() {
        this.container.dispatchEvent(new CustomEvent('prompt-form-change', {
            detail: {
                prompt: this.promptInput ? this.promptInput.value : '',
                formData: this.form ? new FormData(this.form) : null
            }
        }));
    }

    showError(message) {
        this.container.dispatchEvent(new CustomEvent('show-notification', {
            detail: { type: 'error', message: message }
        }));
    }

    showSuccess(message) {
        this.container.dispatchEvent(new CustomEvent('show-notification', {
            detail: { type: 'success', message: message }
        }));
    }
}
