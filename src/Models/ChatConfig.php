<?php

namespace Mostafaarafat\DataChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Mostafaarafat\DataChat\Models\Conversation;
use Mostafaarafat\DataChat\Models\Suggestion;
use Mostafaarafat\DataChat\Models\Usage;

class ChatConfig extends Model
{
    protected $table = 'datachat_configs';

    protected $fillable = [
        'user_id',
        'api_key',
        'widget_name',
        'primary_color',
        'position',
        'allowed_domains',
        'max_messages_per_day',
        'max_messages_per_minute',
        'greeting_message',
        'is_active',
    ];

    protected $casts = [
        'allowed_domains' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($config) {
            if (empty($config->api_key)) {
                $config->api_key = 'dc_' . Str::random(48);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'config_id');
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class, 'config_id')
            ->where('is_active', true)
            ->orderBy('display_order');
    }

    public function usage(): HasMany
    {
        return $this->hasMany(Usage::class, 'config_id');
    }

    /**
     * Check if daily message limit has been reached
     */
    public function hasReachedDailyLimit(): bool
    {
        $today = now()->toDateString();
        $usage = $this->usage()
            ->where('date', $today)
            ->first();

        if (!$usage) {
            return false;
        }

        return $usage->message_count >= $this->max_messages_per_day;
    }

    /**
     * Check if minute rate limit has been reached
     */
    public function hasReachedMinuteLimit(): bool
    {
        $oneMinuteAgo = now()->subMinute();

        $count = $this->conversations()
            ->whereHas('messages', function ($query) use ($oneMinuteAgo) {
                $query->where('created_at', '>=', $oneMinuteAgo);
            })
            ->count();

        return $count >= $this->max_messages_per_minute;
    }

    /**
     * Check if origin is allowed
     */
    public function isOriginAllowed(?string $origin): bool
    {
        if (empty($this->allowed_domains)) {
            return true;
        }

        if (!$origin) {
            return false;
        }

        foreach ($this->allowed_domains as $domain) {
            if (Str::contains($origin, $domain)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get today's usage statistics
     */
    public function getTodayUsage(): ?Usage
    {
        return $this->usage()
            ->where('date', now()->toDateString())
            ->first();
    }

    /**
     * Regenerate API key
     */
    public function regenerateApiKey(): string
    {
        $this->api_key = 'dc_' . Str::random(48);
        $this->save();

        return $this->api_key;
    }
}