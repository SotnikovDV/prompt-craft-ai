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
        Schema::table('prompt_requests', function (Blueprint $table) {
            $table->string('gen_provider')->nullable()->after('domain')->comment('Провайдер LLM (perplexity, openrouter, yandex)');
            $table->string('gen_model')->nullable()->after('gen_provider')->comment('Модель LLM, использованная для генерации');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prompt_requests', function (Blueprint $table) {
            $table->dropColumn(['gen_provider', 'gen_model']);
        });
    }
};
