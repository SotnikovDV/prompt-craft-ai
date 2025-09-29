<!-- Уточняющие вопросы -->
@if($questions && count($questions) > 0)
<div class="flex items-start justify-start p-2 md:p-4">
    <div class="mr-2 md:mr-32 flex items-start space-x-3 max-w-sm md:max-w-3xl">
        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="flex-1">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 mb-3">Уточняющие вопросы:</h4>
                <ul class="space-y-2">
                    @foreach($questions as $question)
                    <li class="text-gray-700 text-sm flex items-start">
                        <span class="text-green-500 mr-2 mt-1">•</span>
                        <span>{{ $question }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
