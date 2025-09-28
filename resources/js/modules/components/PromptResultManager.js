/**
 * Менеджер для компонента отображения результата промпта
 * Управляет отображением результата, копированием, отправкой в Telegram и чат-боты
 */
export class PromptResultManager {
    constructor(container) {
        this.container = container;
        // Если контейнер сам является элементом с data-prompt-result, используем его
        if (container.hasAttribute('data-prompt-result')) {
            this.resultSection = container;
        } else {
            this.resultSection = container.querySelector('[data-prompt-result]');
        }
        // Используем правильный контейнер для поиска элементов
        const searchContainer = this.resultSection || container;

        this.reasoningSection = searchContainer.querySelector('[data-reasoning-section]');
        this.questionsSection = searchContainer.querySelector('[data-questions-section]');
        this.reasoningContent = searchContainer.querySelector('[data-reasoning-content]');
        this.questionsList = searchContainer.querySelector('[data-questions-list]');
        this.generatedPrompt = searchContainer.querySelector('[data-generated-prompt]');
        this.clarificationInput = searchContainer.querySelector('[data-clarification-input]');
        this.regenerateButton = searchContainer.querySelector('[data-regenerate-button]');
        this.copyButton = searchContainer.querySelector('[data-copy-button]');
        this.telegramButton = searchContainer.querySelector('[data-telegram-button]');
        this.chatbotButton = searchContainer.querySelector('[data-chatbot-button]');
        this.showReasoningCheckbox = searchContainer.querySelector('[data-show-reasoning]');
        this.showQuestionsCheckbox = searchContainer.querySelector('[data-show-questions]');

        // Элементы параметров
        this.selectedDomain = searchContainer.querySelector('[data-selected-domain]');
        this.selectedModel = searchContainer.querySelector('[data-selected-model]');
        this.selectedStyle = searchContainer.querySelector('[data-selected-style]');
        this.selectedFormat = searchContainer.querySelector('[data-selected-format]');

        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Переключатели видимости
        if (this.showReasoningCheckbox) {
            this.showReasoningCheckbox.addEventListener('change', () => {
                this.toggleReasoningSection();
            });
        }

        if (this.showQuestionsCheckbox) {
            this.showQuestionsCheckbox.addEventListener('change', () => {
                this.toggleQuestionsSection();
            });
        }

        // Кнопка копирования
        if (this.copyButton) {
            this.copyButton.addEventListener('click', (e) => {
                this.copyPrompt(e);
            });
        }

        // Кнопка отправки в Telegram
        if (this.telegramButton) {
            this.telegramButton.addEventListener('click', (e) => {
                this.sendToTelegram(e);
            });
        }

        // Кнопка отправки в чат-бот
        if (this.chatbotButton) {
            this.chatbotButton.addEventListener('click', (e) => {
                this.sendToChatBot(e);
            });
        }

        // Кнопка пересоздания с уточнениями
        if (this.regenerateButton) {
            this.regenerateButton.addEventListener('click', () => {
                this.regenerateWithClarification();
            });
        }
    }

    showResult(data) {
        console.log('PromptResultManager: Отображение результата', data);

        // Заполняем ход рассуждений
        if (this.reasoningContent) {
            const formattedContent = this.reasoningContent.querySelector('.formatted-content');
            if (formattedContent) {
                formattedContent.innerHTML = this.formatText(data.reasoning) || 'Ход рассуждений не предоставлен.';
            }
        }

        // Заполняем уточняющие вопросы
        if (this.questionsList) {
            this.questionsList.innerHTML = '';

            if (data.questions && data.questions.length > 0) {
                data.questions.forEach(question => {
                    const li = document.createElement('li');
                    li.className = 'flex items-start space-x-2';
                    li.innerHTML = `
                        <span class="text-green-600 font-medium">•</span>
                        <span class="text-gray-700">${this.escapeHtml(question)}</span>
                    `;
                    this.questionsList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.className = 'text-gray-500 italic';
                li.textContent = 'Дополнительных вопросов нет.';
                this.questionsList.appendChild(li);
            }
        }

        // Заполняем сгенерированный промпт
        if (this.generatedPrompt) {
            const formattedContent = this.generatedPrompt.querySelector('.formatted-content');
            if (formattedContent) {
                formattedContent.innerHTML = this.formatText(data.generated_prompt);
            }
        }

        // Заполняем параметры
        this.updateParameters(data.parameters);

        // Сохраняем request_id для будущих уточнений
        if (data.request_id) {
            this.currentRequestId = data.request_id;
            console.log('PromptResultManager: Сохранен request_id:', data.request_id);
        }

        // Показываем секцию результата
        this.show();

        // Плавно прокручиваем к результату
        this.scrollToResult();
    }

    show() {
        console.log('PromptResultManager.show() вызван');
        console.log('this.resultSection существует:', !!this.resultSection);
        if (this.resultSection) {
            console.log('Убираем класс hidden с resultSection');
            this.resultSection.classList.remove('hidden');
            console.log('Классы resultSection после удаления hidden:', this.resultSection.className);
        } else {
            console.error('resultSection не найден!');
        }
    }

    hide() {
        if (this.resultSection) {
            this.resultSection.classList.add('hidden');
        }
    }

    toggleReasoningSection() {
        if (!this.reasoningSection) return;

        const isHidden = this.reasoningSection.classList.contains('hidden');
        if (isHidden) {
            this.reasoningSection.classList.remove('hidden');
        } else {
            this.reasoningSection.classList.add('hidden');
        }
    }

    toggleQuestionsSection() {
        if (!this.questionsSection) return;

        const isHidden = this.questionsSection.classList.contains('hidden');
        if (isHidden) {
            this.questionsSection.classList.remove('hidden');
        } else {
            this.questionsSection.classList.add('hidden');
        }
    }

    updateParameters(parameters) {
        if (this.selectedDomain) {
            this.selectedDomain.textContent = parameters.domain || '-';
        }
        if (this.selectedModel) {
            this.selectedModel.textContent = parameters.model || '-';
        }
        if (this.selectedStyle) {
            this.selectedStyle.textContent = parameters.style || '-';
        }
        if (this.selectedFormat) {
            this.selectedFormat.textContent = parameters.format || '-';
        }
    }

    async copyPrompt(event) {
        const promptElement = this.generatedPrompt?.querySelector('.formatted-content');
        if (!promptElement) return;

        const promptText = promptElement.textContent || promptElement.innerText;
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;

        try {
            await navigator.clipboard.writeText(promptText);
            this.updateButtonOnSuccess(button, originalHTML);
        } catch (err) {
            this.copyWithFallback(promptText, () => {
                this.updateButtonOnSuccess(button, originalHTML);
            });
        }
    }

    updateButtonOnSuccess(button, originalHTML) {
        button.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        `;
        button.classList.add('bg-blue-500', 'hover:bg-blue-600');
        button.classList.remove('bg-transparent', 'text-gray-500', 'hover:bg-white/80', 'hover:text-gray-700');
        button.title = 'Скопировано!';

        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
            //button.classList.add('bg-transparent', 'border', 'border-gray-300', 'text-gray-600', 'hover:bg-gray-100', 'hover:text-gray-800', 'hover:border-gray-400');
            button.classList.add('bg-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:bg-white/80');
            button.title = 'Копировать промпт';
        }, 2000);
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
            if (successful) {
                onSuccess();
            }
        } catch (err) {
            console.error('Ошибка копирования:', err);
        }

        document.body.removeChild(textArea);
    }

    sendToTelegram(event) {
        const promptElement = this.generatedPrompt?.querySelector('.formatted-content');
        if (!promptElement) return;

        const promptText = promptElement.textContent || promptElement.innerText;
        const encodedPrompt = encodeURIComponent(promptText);

        this.container.dispatchEvent(new CustomEvent('open-telegram-modal', {
            detail: { prompt: promptText, encodedPrompt: encodedPrompt }
        }));
    }

    sendToChatBot(event) {
        const promptElement = this.generatedPrompt?.querySelector('.formatted-content');
        if (!promptElement) return;

        const promptText = promptElement.textContent || promptElement.innerText;
        const encodedPrompt = encodeURIComponent(promptText);

        this.container.dispatchEvent(new CustomEvent('open-chatbot-modal', {
            detail: { prompt: promptText, encodedPrompt: encodedPrompt }
        }));
    }

    async regenerateWithClarification() {
        if (!this.clarificationInput || !this.currentRequestId) return;

        const clarification = this.clarificationInput.value.trim();
        if (!clarification) {
            this.showError('Пожалуйста, введите уточнения');
            return;
        }

        this.container.dispatchEvent(new CustomEvent('regenerate-prompt', {
            detail: {
                clarification: clarification,
                parentId: this.currentRequestId
            }
        }));
    }

    scrollToResult() {
        console.log('scrollToResult() вызван');
        if (this.resultSection) {
            console.log('Прокручиваем к resultSection');
            this.resultSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        } else {
            console.error('resultSection не найден для прокрутки!');
        }
    }

    formatText(text) {
        if (!text) return '';

        let result = text
            // Экранируем HTML
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            // Выделяем жирный текст
            .replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold">$1</strong>')
            // Выделяем курсив
            .replace(/\*(.*?)\*/g, '<em class="italic">$1</em>')
            // Выделяем код
            .replace(/`(.*?)`/g, '<code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono">$1</code>')
            // Создаем маркированные списки из строк, начинающихся с - или •
            .replace(/^[\s]*[-•]\s*(.+)$/gm, '<li>$1</li>')
            // Создаем нумерованные списки
            .replace(/^[\s]*(\d+)\.\s*(.+)$/gm, '<li>$1. $2</li>')
            // Оборачиваем группы <li> в <ul> или <ol>
            .replace(/(<li>\d+\..*?<\/li>)(?=\s*<li>\d+\.|$)/gs,
                '<ol class="list-decimal list-inside space-y-2 my-4">$1</ol>')
            .replace(/(<li>•.*?<\/li>)(?=\s*<li>•|$)/gs, '<ul class="list-disc list-inside space-y-2 my-4">$1</ul>')
            // Обрабатываем одиночные элементы списка
            .replace(/(<li>\d+\..*?<\/li>)/gs, '<ol class="list-decimal list-inside space-y-2 my-4">$1</ol>')
            .replace(/(<li>•.*?<\/li>)/gs, '<ul class="list-disc list-inside space-y-2 my-4">$1</ul>')
            // Выделяем заголовки (строки, заканчивающиеся на :)
            .replace(/^(.+):\s*$/gm, '<h4 class="font-semibold text-gray-800 mt-3 mb-2 text-blue-700">$1</h4>')
            // Выделяем цитаты (строки, начинающиеся с >)
            .replace(/^>\s*(.+)$/gm,
                '<blockquote class="border-l-4 border-gray-300 pl-4 italic text-gray-600 my-2">$1</blockquote>')
            // Создаем разделители
            .replace(/^---$/gm, '<hr class="my-4 border-gray-300">')
            // Выделяем ссылки
            .replace(/\[([^\]]+)\]\(([^)]+)\)/g,
                '<a href="$2" class="text-blue-600 hover:text-blue-800 underline" target="_blank">$1</a>');

        return result;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
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

    getCurrentPrompt() {
        const promptElement = this.generatedPrompt?.querySelector('.formatted-content');
        return promptElement ? promptElement.textContent || promptElement.innerText : '';
    }

    clear() {
        if (this.reasoningContent) {
            const formattedContent = this.reasoningContent.querySelector('.formatted-content');
            if (formattedContent) {
                formattedContent.innerHTML = '';
            }
        }

        if (this.questionsList) {
            this.questionsList.innerHTML = '';
        }

        if (this.generatedPrompt) {
            const formattedContent = this.generatedPrompt.querySelector('.formatted-content');
            if (formattedContent) {
                formattedContent.innerHTML = '';
            }
        }

        if (this.clarificationInput) {
            this.clarificationInput.value = '';
        }

        this.currentRequestId = null;
        this.hide();
    }
}
