<?php

namespace Mostafaarafat\DataChat\Services;

use Mostafaarafat\DataChat\Models\ChatConfig;
use Mostafaarafat\DataChat\Models\Conversation;

class ConversationManager
{
    /**
     * Get or create a conversation
     */
    public function getOrCreate(
        ChatConfig $config,
        string $sessionId,
        ?string $endUserId = null,
        array $metadata = []
    ): Conversation {
        return Conversation::firstOrCreate(
            [
                'config_id' => $config->id,
                'session_id' => $sessionId,
            ],
            [
                'end_user_id' => $endUserId,
                'metadata' => $metadata,
                'started_at' => now(),
                'last_message_at' => now(),
                'message_count' => 0,
            ]
        );
    }

    /**
     * Clean up stale conversations
     */
    public function cleanupStale(): int
    {
        $timeout = config('datachat.conversation.session_timeout', 3600);
        $cutoff = now()->subSeconds($timeout);

        return Conversation::where('last_message_at', '<', $cutoff)
            ->where('message_count', '<', 2) // Only cleanup conversations with few messages
            ->delete();
    }

    /**
     * Get active conversations for a config
     */
    public function getActive(ChatConfig $config, int $limit = 20)
    {
        $timeout = config('datachat.conversation.session_timeout', 3600);
        $cutoff = now()->subSeconds($timeout);

        return $config->conversations()
            ->where('last_message_at', '>=', $cutoff)
            ->with('messages')
            ->latest('last_message_at')
            ->limit($limit)
            ->get();
    }
}