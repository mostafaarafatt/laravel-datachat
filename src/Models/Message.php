<?php

namespace Mostafaarafat\DataChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mostafaarafat\DataChat\Models\Conversation;

class Message extends Model
{
    protected $table = 'datachat_messages';

    public $timestamps = false;

    protected $fillable = [
        'conversation_id',
        'role',
        'content',
        'sql_query',
        'sql_results',
        'processing_time_ms',
        'error_message',
        'created_at',
    ];

    protected $casts = [
        'sql_results' => 'array',
        'created_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * Check if message has an error
     */
    public function hasError(): bool
    {
        return !empty($this->error_message);
    }

    /**
     * Check if this is a user message
     */
    public function isUserMessage(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if this is an assistant message
     */
    public function isAssistantMessage(): bool
    {
        return $this->role === 'assistant';
    }

    /**
     * Get formatted message for API response
     */
    public function toApiResponse(): array
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'content' => $this->content,
            'created_at' => $this->created_at->toIso8601String(),
            'has_error' => $this->hasError(),
        ];
    }
}