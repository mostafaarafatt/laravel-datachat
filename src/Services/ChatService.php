<?php

namespace Mostafaarafat\DataChat\Services;

use Mostafaarafat\DataChat\Models\ChatConfig;
use Mostafaarafat\DataChat\Models\Conversation;
use Mostafaarafat\DataChat\Models\Usage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatService
{
    /**
     * Process a chat message
     */
    public function processMessage(
        ChatConfig $config,
        Conversation $conversation,
        string $userMessage,
        array $metadata = []
    ): void {
        $startTime = microtime(true);

        try {
            // Build context from previous messages
            $context = $this->buildContext($conversation);

            // Apply user-specific scoping if needed
            $this->applyScopingRules($metadata);

            // Use your existing AI Database package
            $result = DB::askDetailed($userMessage);

            $processingTime = (int)((microtime(true) - $startTime) * 1000);

            // Save assistant response
            $conversation->addMessage('assistant', $result['answer'], [
                'sql_query' => $result['sql'] ?? null,
                'sql_results' => $result['results'] ?? null,
                'processing_time_ms' => $processingTime,
            ]);

            // Track usage for billing
            $this->trackUsage($config, $processingTime);

            Log::info('DataChat message processed', [
                'config_id' => $config->id,
                'conversation_id' => $conversation->id,
                'processing_time_ms' => $processingTime,
            ]);

        } catch (\Exception $e) {
            Log::error('DataChat processing error', [
                'config_id' => $config->id,
                'conversation_id' => $conversation->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Save error message
            $conversation->addMessage('assistant',
                "I'm sorry, I encountered an error processing your request. Please try rephrasing your question or contact support if this persists.",
                [
                    'error_message' => $e->getMessage(),
                    'processing_time_ms' => (int)((microtime(true) - $startTime) * 1000),
                ]
            );
        }
    }

    /**
     * Build conversation context from recent messages
     */
    protected function buildContext(Conversation $conversation): string
    {
        $contextLimit = config('datachat.conversation.context_messages', 10);
        $recentMessages = $conversation->getRecentMessages($contextLimit);

        if (empty($recentMessages)) {
            return '';
        }

        $context = "Previous conversation context:\n";
        foreach ($recentMessages as $message) {
            $role = ucfirst($message['role']);
            $context .= "{$role}: {$message['content']}\n";
        }

        return $context . "\n";
    }

    /**
     * Apply scoping rules based on user metadata
     */
    protected function applyScopingRules(array $metadata): void
    {
        // For MVP, we'll pass metadata to the AI prompt
        // In future versions, this could modify the DB query scope

        if (!empty($metadata)) {
            // Store in session or pass to AI Database package
            // This is a placeholder for future user-scoped queries
            session(['datachat_user_scope' => $metadata]);
        }
    }

    /**
     * Track usage for billing and analytics
     */
    protected function trackUsage(ChatConfig $config, int $processingTimeMs): void
    {
        $today = now()->toDateString();

        // Estimate API cost (adjust based on your AI provider)
        // Anthropic: ~$0.03 per 1K tokens, average message = 500 tokens = $0.015
        // OpenAI GPT-4: ~$0.03 per 1K tokens
        $estimatedCost = 0.02; // $0.02 per message (conservative estimate)

        Usage::updateOrCreate(
            [
                'config_id' => $config->id,
                'date' => $today,
            ],
            [
                'message_count' => DB::raw('message_count + 1'),
                'ai_api_cost' => DB::raw("ai_api_cost + {$estimatedCost}"),
            ]
        );
    }
}