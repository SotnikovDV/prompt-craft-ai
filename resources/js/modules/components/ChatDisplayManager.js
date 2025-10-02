export class ChatDisplayManager {
    constructor() {
        this.chatMessagesContainer = document.getElementById("chat-messages");
        this.welcomeMessageContainer =
            document.getElementById("welcome-message");
        this.currentChatId = null;
        this.currentChatData = null;

        console.log('ChatDisplayManager инициализирован');
        console.log('chatMessagesContainer:', this.chatMessagesContainer);
        console.log('welcomeMessageContainer:', this.welcomeMessageContainer);
    }

    /**
     * Показать приветственное сообщение (новый чат)
     */
    showWelcomeMessage() {
        if (this.welcomeMessageContainer) {
            this.welcomeMessageContainer.classList.remove("hidden");
        }
        if (this.chatMessagesContainer) {
            this.chatMessagesContainer.classList.add("hidden");
            this.chatMessagesContainer.innerHTML = "";
        }
        this.currentChatId = null;
        this.currentChatData = null;
    }

    /**
     * Скрыть приветственное сообщение (начать новый чат)
     */
    hideWelcomeMessage() {
        console.log('hideWelcomeMessage вызван');
        console.log('welcomeMessageContainer:', this.welcomeMessageContainer);
        console.log('chatMessagesContainer:', this.chatMessagesContainer);
        if (this.welcomeMessageContainer) {
            this.welcomeMessageContainer.classList.add("hidden");
            console.log('Приветственное сообщение скрыто');
        }
        if (this.chatMessagesContainer) {
            this.chatMessagesContainer.classList.remove("hidden");
            console.log('Контейнер сообщений показан');
        }
    }

    /**
     * Показать чат из истории
     */
    showChatHistory(chatData) {
        this.currentChatId = chatData.id;
        this.currentChatData = chatData;

        // Скрываем приветственное сообщение
        if (this.welcomeMessageContainer) {
            this.welcomeMessageContainer.classList.add("hidden");
        }

        // Показываем контейнер сообщений
        if (this.chatMessagesContainer) {
            this.chatMessagesContainer.classList.remove("hidden");
            this.renderChatHistory(chatData);
        }
    }

    /**
     * Отобразить историю чата
     */
    renderChatHistory(chatData) {
        if (!this.chatMessagesContainer || !chatData.requests) {
            return;
        }

        this.chatMessagesContainer.innerHTML = "";

        // Сортируем запросы по времени создания
        const sortedRequests = chatData.requests.sort(
            (a, b) => new Date(a.created_at) - new Date(b.created_at)
        );

        let isFirstRequest = true;

        sortedRequests.forEach((request, index) => {
            // Корневой запрос пользователя (жирный шрифт)
            if (isFirstRequest) {
                this.renderRootUserMessage(request.original_request);
                isFirstRequest = false;
            } else {
                // Разделитель перед следующим запросом
                this.renderDivider();
                // Обычный запрос пользователя
                this.renderUserMessage(request.original_request);
            }

            // Ответ ИИ с закладками
            this.renderAIResponse({
                generated_prompt: request.generated_prompt,
                generatedPrompt: request.generated_prompt,
                reasoning: request.reasoning,
                request_id: request.id,
                allow_edit: true, // Разрешаем редактирование для всех промптов в истории
            });

            // Уточняющие вопросы
            if (request.questions && request.questions.length > 0) {
                this.renderClarifyingQuestions(request.questions);
            }
        });
    }

    /**
     * Отобразить корневой запрос пользователя
     */
    renderRootUserMessage(message) {
        const messageDiv = document.createElement("div");
        messageDiv.className = "flex items-start justify-end p-2 md:p-4";
        messageDiv.innerHTML = `
                <div class="flex ml-2 md:ml-16 items-start space-x-3 max-w-sm md:max-w-2xl">
                    <div class="flex-1 relative">
                        <div class="bg-blue-500 text-white rounded-lg p-3 ml-auto">
                            <p class="font-semibold">${this.escapeHtml(
                                message
                            )}</p>
                        </div>
                        <div class="absolute bottom-1 right-1 flex flex-col gap-1">
                            <button type="button" data-copy-prompt
                                class="p-1 text-gray-500 hover:text-gray-700 hover:bg-white/50 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                                title="Копировать текст">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-sm font-bold">${this.getUserInitial()}</span>
                    </div>
                </div>
            `;
        this.chatMessagesContainer.appendChild(messageDiv);

        // Настраиваем обработчик для кнопки копирования
        this.setupUserMessageCopyButton(messageDiv, message);
    }

    /**
     * Отобразить обычный запрос пользователя
     */
    renderUserMessage(message) {
        console.log('renderUserMessage вызван с сообщением:', message);
        console.log('chatMessagesContainer:', this.chatMessagesContainer);
        const messageDiv = document.createElement("div");
        messageDiv.className = "flex items-start justify-end p-2 md:p-4";
        messageDiv.innerHTML = `
                <div class="flex ml-2 md:ml-16 items-start space-x-3 max-w-sm md:max-w-2xl">
                    <div class="flex-1">
                        <div class="bg-blue-500 text-white rounded-lg p-3 ml-auto">
                            <p>${this.escapeHtml(message)}</p>
                        </div>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-sm font-bold">${this.getUserInitial()}</span>
                    </div>
                </div>
            `;
        this.chatMessagesContainer.appendChild(messageDiv);
        console.log('Сообщение пользователя добавлено в DOM');
    }

    /**
     * Отобразить ответ ИИ с закладками
     */
    renderAIResponse(data) {
        console.log('renderAIResponse вызван с данными:', data);
        console.log('chatMessagesContainer:', this.chatMessagesContainer);
        const messageDiv = document.createElement("div");
        messageDiv.className = "flex items-start justify-start p-2 md:p-4";

        const hasReasoning = data.reasoning && data.reasoning.trim() !== "";
        const uniqueId =
            "tab-" + Date.now() + "-" + Math.floor(Math.random() * 10000);

        messageDiv.innerHTML = `
                <div class="flex mr-2 md:mr-32 items-start space-x-3 max-w-sm md:max-w-3xl">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div class="flex-1 relative">
                <div class="bg-white border border-gray-200 rounded-lg p-4 relative">
                    <!-- Закладки -->
                    <div class="flex border-b border-gray-200 mb-4">
                        <button class="tab-button active px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600"
                                data-tab="prompt">
                            Сгенерированный промпт
                        </button>
                        ${
                            hasReasoning
                                ? `
                        <button class="tab-button px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:border-blue-600"
                                data-tab="reasoning">
                            Ход рассуждений
                        </button>
                        `
                                : ""
                        }
                    </div>

                    <!-- Содержимое закладок -->
                    <div class="tab-content">
                        <!-- Сгенерированный промпт -->
                        <div id="${uniqueId}-prompt" class="tab-panel" data-request-id="${data.request_id || ''}">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="formatted-content prompt-text text-gray-800">${this.formatText(
                                    data.generated_prompt ||
                                        data.generatedPrompt
                                )}</div>
                            </div>
                        </div>

                        <!-- Ход рассуждений -->
                        ${
                            hasReasoning
                                ? `
                        <div id="${uniqueId}-reasoning" class="tab-panel hidden">
                            <div class="bg-blue-50 rounded-lg p-3">
                                <div class="formatted-content text-gray-700 text-sm">${this.formatText(
                                    data.reasoning
                                )}</div>
                            </div>
                        </div>
                        `
                                : ""
                        }
                    </div>
                </div>
                    <!-- Кнопки действий -->
                    <div class="absolute bottom-1 right-5 flex gap-2">
                        ${data.allow_edit && data.request_id ? `
                        <button data-edit-prompt-button
                                data-request-id="${data.request_id}"
                                data-prompt-text="${this.escapeHtml(data.generated_prompt || data.generatedPrompt)}"
                                class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white/80 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                                title="Редактировать промпт">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        ` : ''}
                        <button data-copy-button data-prompt-text="${this.escapeHtml(
                            data.generated_prompt || data.generatedPrompt
                        )}"
                            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white/80 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                            title="Копировать промпт">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <button data-telegram-button data-prompt-text="${this.escapeHtml(
                            data.generated_prompt || data.generatedPrompt
                        )}"
                            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white/80 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                            title="Отправить в Telegram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                            </svg>
                        </button>
                        <button data-chatbot-button data-prompt-text="${this.escapeHtml(
                            data.generated_prompt || data.generatedPrompt
                        )}"
                            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white/80 rounded-md transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 group"
                            title="Отправить в чат-бот">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </button>
                    </div>

            </div>
        `;

        this.chatMessagesContainer.appendChild(messageDiv);
        console.log('Ответ ИИ добавлен в DOM');

        // Настраиваем обработчики для кнопок
        this.setupMessageButtons(
            messageDiv,
            data.generated_prompt || data.generatedPrompt
        );

        // Настраиваем обработчики для закладок
        this.setupTabHandlers(messageDiv, uniqueId);
    }

    /**
     * Отобразить уточняющие вопросы
     */
    renderClarifyingQuestions(questions) {
        if (!questions || questions.length === 0) return;

        const messageDiv = document.createElement("div");
        messageDiv.className = "flex items-start justify-start p-2 md:p-4";
        messageDiv.innerHTML = `
                <div class="flex mr-2 md:mr-32 items-start space-x-3 max-w-sm md:max-w-3xl">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Уточняющие вопросы:</h4>
                        <ul class="space-y-2">
                            ${questions
                                .map(
                                    (q) => `
                            <li class="text-gray-700 text-sm flex items-start">
                                <span class="text-green-500 mr-2 mt-1">•</span>
                                <span>${this.escapeHtml(q)}</span>
                            </li>
                            `
                                )
                                .join("")}
                        </ul>
                    </div>
                </div>
            </div>
        `;
        this.chatMessagesContainer.appendChild(messageDiv);
    }

    /**
     * Отобразить разделитель
     */
    renderDivider() {
        const dividerDiv = document.createElement("div");
        dividerDiv.className = "flex items-center justify-center py-4";
        dividerDiv.innerHTML = `
            <div class="flex-1 border-t border-gray-300"></div>
            <div class="px-4 text-xs text-gray-400 rounded-full border border-gray-300 bg-white/20">Уточняющий запрос</div>
            <div class="flex-1 border-t border-gray-300"></div>
        `;
        this.chatMessagesContainer.appendChild(dividerDiv);
    }

    /**
     * Настроить обработчики для кнопок сообщений
     */
    setupMessageButtons(messageDiv, promptText) {
        // Кнопка редактирования
        const editButton = messageDiv.querySelector("[data-edit-prompt-button]");
        if (editButton) {
            editButton.addEventListener("click", () => {
                const requestId = editButton.getAttribute("data-request-id");
                const promptText = editButton.getAttribute("data-prompt-text");
                if (requestId && promptText && window.openEditPromptModal) {
                    window.openEditPromptModal(requestId, promptText);
                } else {
                    console.error("Не удалось получить данные для редактирования:", { requestId, promptText });
                }
            });
        }

        // Кнопка копирования
        const copyButton = messageDiv.querySelector("[data-copy-button]");
        if (copyButton) {
            copyButton.addEventListener("click", () => {
                navigator.clipboard
                    .writeText(promptText)
                    .then(() => {
                        if (window.infoModalManager) {
                            window.infoModalManager.showSuccess(
                                "Промпт скопирован!",
                                "Промпт успешно скопирован в буфер обмена"
                            );
                        }
                    })
                    .catch(() => {
                        // Fallback для старых браузеров
                        const textArea = document.createElement("textarea");
                        textArea.value = promptText;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand("copy");
                        document.body.removeChild(textArea);

                        if (window.infoModalManager) {
                            window.infoModalManager.showSuccess(
                                "Промпт скопирован!",
                                "Промпт успешно скопирован в буфер обмена"
                            );
                        }
                    });
            });
        }

        // Кнопка Telegram
        const telegramButton = messageDiv.querySelector(
            "[data-telegram-button]"
        );
        if (telegramButton && window.telegramModalManager) {
            telegramButton.addEventListener("click", () => {
                const encodedPrompt = encodeURIComponent(promptText);
                window.telegramModalManager.show(promptText, encodedPrompt);
            });
        }

        // Кнопка чат-бота
        const chatbotButton = messageDiv.querySelector("[data-chatbot-button]");
        if (chatbotButton && window.chatbotModalManager) {
            chatbotButton.addEventListener("click", () => {
                const encodedPrompt = encodeURIComponent(promptText);
                window.chatbotModalManager.show(promptText, encodedPrompt);
            });
        }
    }

    /**
     * Настроить обработчик копирования для сообщений пользователя
     */
    setupUserMessageCopyButton(messageDiv, messageText) {
        const copyButton = messageDiv.querySelector("[data-copy-prompt]");
        if (copyButton) {
            copyButton.addEventListener("click", () => {
                navigator.clipboard
                    .writeText(messageText)
                    .then(() => {
                        if (window.infoModalManager) {
                            window.infoModalManager.showSuccess(
                                "Запрос скопирован!",
                                "Запрос пользователя успешно скопирован в буфер обмена"
                            );
                        }
                    })
                    .catch(() => {
                        // Fallback для старых браузеров
                        const textArea = document.createElement("textarea");
                        textArea.value = messageText;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand("copy");
                        document.body.removeChild(textArea);

                        if (window.infoModalManager) {
                            window.infoModalManager.showSuccess(
                                "Запрос скопирован!",
                                "Запрос пользователя успешно скопирован в буфер обмена"
                            );
                        }
                    });
            });
        }
    }

    /**
     * Настроить обработчики для закладок
     */
    setupTabHandlers(messageDiv, uniqueId) {
        const tabButtons = messageDiv.querySelectorAll(".tab-button");
        const tabPanels = messageDiv.querySelectorAll(".tab-panel");

        tabButtons.forEach((button) => {
            button.addEventListener("click", function () {
                const targetTab = this.getAttribute("data-tab");

                // Убираем активный класс со всех кнопок
                tabButtons.forEach((btn) => {
                    btn.classList.remove(
                        "active",
                        "text-blue-600",
                        "border-b-2",
                        "border-blue-600"
                    );
                    btn.classList.add(
                        "text-gray-600",
                        "hover:text-blue-600",
                        "hover:border-blue-600"
                    );
                });

                // Добавляем активный класс к текущей кнопке
                this.classList.add(
                    "active",
                    "text-blue-600",
                    "border-b-2",
                    "border-blue-600"
                );
                this.classList.remove(
                    "text-gray-600",
                    "hover:text-blue-600",
                    "hover:border-blue-600"
                );

                // Скрываем все панели
                tabPanels.forEach((panel) => {
                    panel.classList.add("hidden");
                });

                // Показываем нужную панель
                const targetPanel = messageDiv.querySelector(
                    `#${uniqueId}-${targetTab}`
                );
                if (targetPanel) {
                    targetPanel.classList.remove("hidden");
                }
            });
        });
    }

    /**
     * Получить первую букву имени пользователя
     */
    getUserInitial() {
        // Получаем инициал пользователя из скрытого элемента
        const userInitialElement = document.querySelector("#user-initial");
        if (userInitialElement) {
            return userInitialElement.textContent.trim().toUpperCase();
        }

        // Fallback: используем глобальную переменную
        if (typeof window.userInitial !== "undefined") {
            return window.userInitial.toUpperCase();
        }

        return "U"; // Заглушка по умолчанию
    }

    /**
     * Экранировать HTML
     */
    escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Форматировать текст с поддержкой Markdown
     */
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

    /**
     * Добавить новое сообщение в текущий чат
     */
    addNewMessage(message, response) {
        console.log('addNewMessage вызван с:', { message, response });
        console.log('this.currentChatId:', this.currentChatId);
        if (!this.currentChatId) {
            // Если это новый чат, скрываем приветственное сообщение
            console.log('Скрываем приветственное сообщение для нового чата');
            this.hideWelcomeMessage();
        }

        // Добавляем сообщение пользователя
        console.log('Рендерим сообщение пользователя:', message);
        this.renderUserMessage(message);

        // Добавляем ответ ИИ
        console.log('Рендерим ответ ИИ:', response);
        this.renderAIResponse(response);

        // Добавляем уточняющие вопросы если есть
        if (response.questions && response.questions.length > 0) {
            this.renderClarifyingQuestions(response.questions);
        }
    }

    /**
     * Добавить только ответ ИИ (сообщение пользователя уже отображено)
     */
    addAIResponse(response) {
        console.log('addAIResponse вызван с:', response);

        // Добавляем ответ ИИ
        console.log('Рендерим ответ ИИ:', response);
        this.renderAIResponse(response);

        // Добавляем уточняющие вопросы если есть
        if (response.questions && response.questions.length > 0) {
            this.renderClarifyingQuestions(response.questions);
        }
    }
}
