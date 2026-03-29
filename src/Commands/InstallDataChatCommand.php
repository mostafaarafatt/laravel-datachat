<?php

namespace MostafaArafat\DataChat\Commands;

use Illuminate\Console\Command;
use Mostafaarafat\DataChat\Models\ChatConfig;
use Mostafaarafat\DataChat\Models\Suggestion;

class InstallDataChatCommand extends Command
{
    protected $signature = 'datachat:install';
    protected $description = 'Install DataChat and create your first widget';

    public function handle(): int
    {
        $this->info('🚀 DataChat Installation');
        $this->newLine();

        // Check if user model exists
        if (!class_exists(config('auth.providers.users.model'))) {
            $this->error('User model not found. Please configure auth.providers.users.model');
            return self::FAILURE;
        }

        // Get first user or create demo user
        $userModel = config('auth.providers.users.model');
        $user = $userModel::first();

        if (!$user) {
            $this->warn('No users found in database.');

            if ($this->confirm('Would you like to create a demo user?', true)) {
                $user = $userModel::create([
                    'name' => 'Demo User',
                    'email' => 'demo@datachat.local',
                    'password' => bcrypt('password'),
                ]);

                $this->info('Demo user created: demo@datachat.local / password');
            } else {
                $this->error('Installation cancelled. Please create a user first.');
                return self::FAILURE;
            }
        }

        $this->info('Using user: ' . $user->email);
        $this->newLine();

        // Collect widget information
        $widgetName = $this->ask('Widget name?', 'DataChat Assistant');
        $primaryColor = $this->ask('Primary color (hex)?', '#3b82f6');
        $position = $this->choice('Widget position?', ['bottom-right', 'bottom-left'], 0);
        $maxMessages = $this->ask('Daily message limit?', '100');

        // Create widget
        $config = ChatConfig::create([
            'user_id' => $user->id,
            'widget_name' => $widgetName,
            'primary_color' => $primaryColor,
            'position' => $position,
            'max_messages_per_day' => (int)$maxMessages,
        ]);

        // Create default suggestions
        $defaultSuggestions = [
            'How many users do we have?',
            'What is our revenue this month?',
            'Show me recent orders',
            'What are the top selling products?',
        ];

        foreach ($defaultSuggestions as $index => $question) {
            Suggestion::create([
                'config_id' => $config->id,
                'question' => $question,
                'display_order' => $index,
            ]);
        }

        $this->newLine();
        $this->info('✅ Widget created successfully!');
        $this->newLine();

        $this->line('📋 Your API Key:');
        $this->line($config->api_key);
        $this->newLine();

        $this->line('📝 Installation Code:');
        $this->line('Add this to your HTML:');
        $this->newLine();

        $code = <<<HTML
<script src="{{ config('app.url') }}/vendor/datachat/datachat-widget.umd.js"></script>
<link rel="stylesheet" href="{{ config('app.url') }}/vendor/datachat/datachat-widget.css">

<script>
  DataChat.init({
    apiKey: '{$config->api_key}',
    apiUrl: '{{ config('app.url') }}'
  });
</script>
HTML;

        $this->line($code);
        $this->newLine();

        $this->info('🎉 Installation complete!');
        $this->line('Visit /datachat to manage your widgets');

        return self::SUCCESS;
    }
}