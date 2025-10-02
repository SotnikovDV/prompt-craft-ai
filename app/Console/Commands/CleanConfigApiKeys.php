<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanConfigApiKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:clean-api-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удаляет API ключи из конфигурационных файлов для безопасности';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Очистка API ключей из конфигурационных файлов...');

        $configPath = config_path('llm.php');

        if (!file_exists($configPath)) {
            $this->error('Файл config/llm.php не найден');
            return 1;
        }

        $config = include $configPath;
        $cleanedConfig = $this->filterApiKeys($config);

        $phpCode = "<?php\n\nreturn ".var_export($cleanedConfig, true).";\n";

        if (file_put_contents($configPath, $phpCode) === false) {
            $this->error('Не удалось записать в файл config/llm.php');
            return 1;
        }

        $this->info('API ключи успешно удалены из config/llm.php');
        $this->info('API ключи теперь хранятся только в .env файле для безопасности');

        return 0;
    }

    /**
     * Фильтрация API ключей из конфигурации
     */
    private function filterApiKeys(array $data): array
    {
        $sensitiveKeys = ['api_key', 'folder_id'];

        foreach ($data as $provider => &$config) {
            if (is_array($config)) {
                foreach ($sensitiveKeys as $key) {
                    unset($config[$key]);
                }
            }
        }

        return $data;
    }
}
