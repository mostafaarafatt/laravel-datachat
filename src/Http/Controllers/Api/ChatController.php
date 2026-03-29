<?php

namespace Mostafaarafat\DataChat\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Mostafaarafat\DataChat\Jobs\ProcessChatMessageJob;
use Mostafaarafat\DataChat\Models\ChatConfig;
use Mostafaarafat\DataChat\Models\Conversation;
use Mostafaarafat\DataChat\Services\ConversationManager;

class ChatController extends Controller
{
    protected ConversationManager $conversationManager;

    public function __construct(ConversationManager $conversationManager)
    {
        $this->conversationManager = $conversationManager;
    }

    /**
     * Get widget configuration
     * GET /api/datachat/config
     */
    public function config(Request $request): JsonResponse
    {
        /** @var ChatConfig $config */
        $config = $request->get('datachat_config');

        return response()->json([
            'name' => $config->widget_name,
            'primary_color' => $config->primary_color,
            'position' => $config->position,
            'greeting_message' => $config->greeting_message ?? config('datachat.widget_defaults.greeting_message'),
            'suggestions' => $config->suggestions->pluck('question')->toArray(),
        ]);
    }

    /**
     * Send a message
     * POST /api/datachat/message
     */
    public function message(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'required|string|max:64',
            'user_id' => 'nullable|string|max:255',
            'metadata' => 'nullable|array',
        ]);

        /** @var ChatConfig $config */
        $config = $request->get('datachat_config');

        // Check daily limit
        if ($config->hasReachedDailyLimit()) {
            return response()->json([
                'error' => 'Daily message limit reached. Please try again tomorrow or upgrade your plan.'
            ], 429);
        }

        // Check rate limit (per minute)
        if ($config->hasReachedMinuteLimit()) {
            return response()->json([
                'error' => 'Too many requests. Please wait a moment before sending another message.'
            ], 429);
        }

        // Get or create conversation
        $conversation = $this->conversationManager->getOrCreate(
            $config,
            $validated['session_id'],
            $validated['user_id'] ?? null,
            $validated['metadata'] ?? []
        );

        // Save user message immediately
        $conversation->addMessage('user', $validated['message']);

        // Process message asynchronously
        if (config('datachat.queue.enabled')) {
            ProcessChatMessageJob::dispatch(
                $config,
                $conversation,
                $validated['message'],
                $validated['metadata'] ?? []
            )->onQueue(config('datachat.queue.queue_name'));
        } else {
            // Process synchronously (not recommended for production)
            app(\Mostafaarafat\DataChat\Services\ChatService::class)->processMessage(
                $config,
                $conversation,
                $validated['message'],
                $validated['metadata'] ?? []
            );
        }

        return response()->json([
            'status' => 'processing',
            'conversation_id' => $conversation->id,
        ]);
    }

    /**
     * Get conversation messages
     * GET /api/datachat/conversation/{id}
     */
    public function conversation(Request $request, int $conversationId): JsonResponse
    {
        /** @var ChatConfig $config */
        $config = $request->get('datachat_config');

        $conversation = Conversation::where('config_id', $config->id)
            ->where('id', $conversationId)
            ->with('messages')
            ->firstOrFail();

        return response()->json([
            'messages' => $conversation->messages->map->toApiResponse(),
        ]);
    }

    /**
     * Poll for new messages
     * GET /api/datachat/conversation/{id}/poll
     */
    public function poll(Request $request, int $conversationId): JsonResponse
    {
        $validated = $request->validate([
            'after_id' => 'required|integer',
        ]);

        /** @var ChatConfig $config */
        $config = $request->get('datachat_config');

        $conversation = Conversation::where('config_id', $config->id)
            ->where('id', $conversationId)
            ->firstOrFail();

        $newMessages = $conversation->messages()
            ->where('id', '>', $validated['after_id'])
            ->get()
            ->map->toApiResponse();

        return response()->json([
            'messages' => $newMessages,
        ]);
    }
}