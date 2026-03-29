<?php

namespace MostafaArafat\DataChat\Commands;

use Illuminate\Console\Command;
use Mostafaarafat\DataChat\Models\ChatConfig;

class GenerateApiKeyCommand extends Command
{
    protected $signature = 'datachat:generate-key {config_id}';
    protected $description = 'Generate a new API key for a widget';

    public function handle(): int
    {
        $configId = $this->argument('config_id');

        $config = ChatConfig::find($configId);

        if (!$config) {
            $this->error("Widget with ID {$configId} not found.");
            return self::FAILURE;
        }

        $this->warn('Current API Key: ' . $config->api_key);
        $this->newLine();

        if (!$this->confirm('This will invalidate the current API key. Continue?')) {
            $this->info('Cancelled.');
            return self::SUCCESS;
        }

        $newKey = $config->regenerateApiKey();

        $this->newLine();
        $this->info('✅ New API key generated successfully!');
        $this->newLine();
        $this->line('New API Key: ' . $newKey);
        $this->newLine();
        $this->warn('⚠️  Make sure to update your frontend code with the new key!');

        return self::SUCCESS;
    }
}