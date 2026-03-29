<?php

namespace Mostafaarafat\DataChat\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mostafaarafat\DataChat\Models\ChatConfig;
use Mostafaarafat\DataChat\Models\Conversation;
use Mostafaarafat\DataChat\Services\ChatService;

class ProcessChatMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;
    public int $maxExceptions = 1;

    public function __construct(
        public ChatConfig $config,
        public Conversation $conversation,
        public string $message,
        public array $metadata = []
    ) {}

    public function handle(ChatService $chatService): void
    {
        $chatService->processMessage(
            $this->config,
            $this->conversation,
            $this->message,
            $this->metadata
        );
    }

    public function failed(\Throwable $exception): void
    {
        // Log failure
        \Log::error('DataChat job failed', [
            'config_id' => $this->config->id,
            'conversation_id' => $this->conversation->id,
            'message' => $this->message,
            'error' => $exception->getMessage(),
        ]);

        // Optionally add error message to conversation
        $this->conversation->addMessage('assistant',
            "I'm sorry, I'm experiencing technical difficulties. Please try again later.",
            ['error_message' => 'Job failed after retries']
        );
    }
}