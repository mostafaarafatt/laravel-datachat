<?php

namespace Mostafaarafat\DataChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mostafaarafat\DataChat\Models\ChatConfig;
use Mostafaarafat\DataChat\Models\Message;

class Conversation extends Model
{
    protected $table = 'datachat_conversations';

    public $timestamps = false;

    protected $fillable = [
        'config_id',
        'session_id',
        'end_user_id',
        'metadata',
        'started_at',
        'last_message_at',
        'message_count',
    ];

    protected $casts = [
        'metadata' => 'array',
        'started_at' => 'datetime',
        'last_message_at' => 'datetime',
    ];

    public function config(): BelongsTo
    {
        return $this->belongsTo(ChatConfig::class, 'config_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id')
            ->orderBy('created_at');
    }

    /**
     * Add a message to the conversation
     */
    public function addMessage(string $role, string $content, array $extra = []): Message
    {
        $message = $this->messages()->create(array_merge([
            'role' => $role,
            'content' => $content,
            'created_at' => now(),
        ], $extra));

        $this->increment('message_count');
        $this->update(['last_message_at' => now()]);

        return $message;
    }

    /**
     * Get recent messages for context
     */
    public function getRecentMessages(int $limit = 10): array
    {
        return $this->messages()
            ->latest('created_at')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'role' => $message->role,
                    'content' => $message->content,
                    'created_at' => $message->created_at->toIso8601String(),
                ];
            })
            ->toArray();
    }

    /**
     * Get the last message
     */
    public function getLastMessage(): ?Message
    {
        return $this->messages()
            ->latest('created_at')
            ->first();
    }

    /**
     * Check if conversation is stale
     */
    public function isStale(): bool
    {
        $timeout = config('datachat.conversation.session_timeout', 3600);
        return $this->last_message_at->diffInSeconds(now()) > $timeout;
    }
}