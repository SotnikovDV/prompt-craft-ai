import './bootstrap';

import Alpine from 'alpinejs';

// Импортируем менеджеры компонентов
import { PromptFormManager } from './modules/components/PromptFormManager.js';
import { PromptResultManager } from './modules/components/PromptResultManager.js';
import { InfoModalManager } from './modules/components/InfoModalManager.js';
import { ChatBotModalManager } from './modules/components/ChatBotModalManager.js';
import { TelegramModalManager } from './modules/components/TelegramModalManager.js';
import { ChatHistoryManager } from './modules/components/ChatHistoryManager.js';
import { ChatDisplayManager } from './modules/components/ChatDisplayManager.js';

// Делаем классы доступными глобально
window.PromptFormManager = PromptFormManager;
window.PromptResultManager = PromptResultManager;
window.InfoModalManager = InfoModalManager;
window.ChatBotModalManager = ChatBotModalManager;
window.TelegramModalManager = TelegramModalManager;
window.ChatHistoryManager = ChatHistoryManager;
window.ChatDisplayManager = ChatDisplayManager;

window.Alpine = Alpine;

Alpine.start();
