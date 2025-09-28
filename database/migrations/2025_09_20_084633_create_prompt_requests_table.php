<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prompt_requests', function (Blueprint $table) {
            $table->id();

            // Пользователь и сессия
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); // для незарегистрированных пользователей

            // Иерархия запросов
            $table->foreignId('parent_id')->nullable()->constrained('prompt_requests')->onDelete('cascade');

            // Содержимое запроса
            $table->text('original_request'); // исходный запрос пользователя
            $table->text('clarification')->nullable(); // уточнения пользователя
            $table->text('full_request'); // полный запрос с уточнениями

            // Параметры генерации
            $table->string('domain')->nullable();
            $table->string('model')->nullable();
            $table->string('style')->nullable();
            $table->string('format')->nullable();

            // Результат LLM
            $table->text('reasoning')->nullable(); // ход рассуждений
            $table->json('questions')->nullable(); // уточняющие вопросы
            $table->text('generated_prompt'); // сгенерированный промпт

            // Статистика выполнения
            $table->integer('execution_time')->nullable(); // время выполнения в миллисекундах
            $table->integer('tokens_in')->nullable(); // входящие токены
            $table->integer('tokens_out')->nullable(); // исходящие токены

            $table->timestamps();

            // Индексы для производительности
            $table->index(['user_id', 'created_at']);
            $table->index(['session_id', 'created_at']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prompt_requests');
    }
};
