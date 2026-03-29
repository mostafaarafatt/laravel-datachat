<?php

namespace Mostafaarafat\DataChat;

use Illuminate\Support\ServiceProvider;
use Mostafaarafat\DataChat\Commands\InstallDataChatCommand;
use Mostafaarafat\DataChat\Commands\GenerateApiKeyCommand;
use Mostafaarafat\DataChat\Services\ChatService;
use Mostafaarafat\DataChat\Services\ConversationManager;

class DataChatServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/datachat.php',
            'datachat'
        );

        // Register services
        $this->app->singleton(ChatService::class);
        $this->app->singleton(ConversationManager::class);
    }

    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'datachat');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Publishing
        if ($this->app->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__.'/../config/datachat.php' => config_path('datachat.php'),
            ], 'datachat-config');

            // Publish views
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/datachat'),
            ], 'datachat-views');

            // Publish widget
            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/datachat'),
            ], 'datachat-assets');

            // Register commands
            $this->commands([
                InstallDataChatCommand::class,
                GenerateApiKeyCommand::class,
            ]);
        }
    }
}