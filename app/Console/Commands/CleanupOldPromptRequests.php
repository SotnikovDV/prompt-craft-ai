<?php

namespace App\Console\Commands;

use App\Models\PromptRequest;
use Illuminate\Console\Command;

class CleanupOldPromptRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prompt:cleanup {--days= : Количество дней для хранения запросов (по умолчанию из конфига)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Очистить старые запросы незарегистрированных пользователей';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = $this->option('days') ? (int) $this->option('days') : config('ai.cleanup_days_guest', 3);

        $this->info("Очистка запросов незарегистрированных пользователей старше {$days} дней...");

        $deletedCount = PromptRequest::cleanupOldRequests($days);

        if ($deletedCount > 0) {
            $this->info("Удалено запросов: {$deletedCount}");
        } else {
            $this->info("Нет запросов для удаления");
        }

        return Command::SUCCESS;
    }
}
