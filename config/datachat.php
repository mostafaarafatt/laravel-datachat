<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Provider Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which AI provider to use for generating responses.
    | This uses your laravel-ai-database package configuration.
    |
    */

    'ai_provider' => env('DATACHAT_AI_PROVIDER', env('AI_DATABASE_PROVIDER', 'anthropic')),

    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    |
    | Which database connection to use for queries.
    | Leave null to use your default connection.
    |
    */

    'connection' => env('DATACHAT_DB_CONNECTION', null),

    /*
    |--------------------------------------------------------------------------
    | Strict Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, only SELECT queries are allowed.
    | Inherits from ai-database package if not specified.
    |
    */

    'strict_mode' => env('DATACHAT_STRICT_MODE', true),

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    |
    | Chat messages are processed asynchronously via queues.
    |
    */

    'queue' => [
        'enabled' => env('DATACHAT_QUEUE_ENABLED', true),
        'connection' => env('DATACHAT_QUEUE_CONNECTION', env('QUEUE_CONNECTION', 'redis')),
        'queue_name' => env('DATACHAT_QUEUE_NAME', 'datachat'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Cache configuration and conversation context to improve performance.
    |
    */

    'cache' => [
        'enabled' => env('DATACHAT_CACHE_ENABLED', true),
        'ttl' => env('DATACHAT_CACHE_TTL', 3600), // 1 hour
        'prefix' => 'datachat',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Default rate limits for widgets.
    |
    */

    'rate_limits' => [
        'messages_per_day' => env('DATACHAT_MAX_MESSAGES_PER_DAY', 100),
        'messages_per_minute' => env('DATACHAT_MAX_MESSAGES_PER_MINUTE', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Widget Defaults
    |--------------------------------------------------------------------------
    |
    | Default configuration for new widgets.
    |
    */

    'widget_defaults' => [
        'name' => 'DataChat',
        'primary_color' => '#3b82f6',
        'position' => 'bottom-right', // bottom-right, bottom-left
        'greeting_message' => 'Hi! Ask me anything about your data.',
    ],

    /*
    |--------------------------------------------------------------------------
    | CORS Configuration
    |--------------------------------------------------------------------------
    |
    | Configure CORS for the widget API.
    |
    */

    'cors' => [
        'allowed_origins' => env('DATACHAT_ALLOWED_ORIGINS', '*'),
        'allowed_methods' => ['GET', 'POST', 'OPTIONS'],
        'allowed_headers' => ['Content-Type', 'X-DataChat-Key', 'X-Requested-With'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Conversation Settings
    |--------------------------------------------------------------------------
    |
    */

    'conversation' => [
        'context_messages' => 10, // Number of previous messages to include as context
        'session_timeout' => 3600, // 1 hour
    ],

];