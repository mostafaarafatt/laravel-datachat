<?php

namespace Mostafaarafat\DataChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mostafaarafat\DataChat\Models\ChatConfig;

class Usage extends Model
{
    protected $table = 'datachat_usage';

    protected $fillable = [
        'config_id',
        'date',
        'message_count',
        'ai_api_cost',
    ];

    protected $casts = [
        'date' => 'date',
        'ai_api_cost' => 'decimal:4',
    ];

    public function config(): BelongsTo
    {
        return $this->belongsTo(ChatConfig::class, 'config_id');
    }

    /**
     * Increment message count
     */
    public function incrementMessages(int $count = 1): void
    {
        $this->increment('message_count', $count);
    }

    /**
     * Add API cost
     */
    public function addCost(float $cost): void
    {
        $this->increment('ai_api_cost', $cost);
    }
}