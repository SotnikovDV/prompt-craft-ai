@props([
    'modalId' => 'info-modal',
    'title' => 'Уведомление',
    'message' => '',
    'type' => 'info', // info, success, error, warning
    'size' => 'md', // sm, md, lg, xl
    'showCancel' => false,
    'confirmText' => 'OK',
    'cancelText' => 'Отмена',
    'closable' => true
])

<div id="{{ $modalId }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" data-info-modal>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="modal-brand rounded-2xl shadow-2xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0 {{ $size === 'sm' ? 'max-w-sm' : ($size === 'md' ? 'max-w-md' : ($size === 'lg' ? 'max-w-2xl' : 'max-w-4xl')) }} {{ $size === 'lg' || $size === 'xl' ? 'max-h-[85vh] flex flex-col' : '' }}"
            data-modal-content>

            <!-- Заголовок модального окна -->
            <div class="modal-header-brand flex items-center justify-between px-6 py-4 border-b border-gray-200 {{ $size === 'lg' || $size === 'xl' ? 'flex-shrink-0' : '' }}">
                <div class="flex items-center">
                    <div data-modal-icon class="w-8 h-8 rounded-full flex items-center justify-center mr-3">
                        <!-- Иконка будет добавлена через JavaScript -->
                    </div>
                    <h3 data-modal-title class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                </div>
                @if($closable)
                    <button data-modal-close class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>

            <!-- Содержимое модального окна -->
            <div class="p-6 {{ $size === 'lg' || $size === 'xl' ? 'flex-1 overflow-y-auto' : '' }}">
                <div data-modal-message class="text-gray-700 leading-relaxed mb-6">
                    @if($message)
                        {!! $message !!}
                    @else
                        <!-- Сообщение будет добавлено через JavaScript -->
                    @endif
                </div>

                <!-- Кнопки действий -->
                <div class="flex justify-end space-x-3">
                    @if($showCancel)
                        <button data-modal-cancel
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                            {{ $cancelText }}
                        </button>
                    @endif
                    <button data-modal-confirm
                        class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                        {{ $confirmText }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
