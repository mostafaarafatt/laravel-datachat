<?php

namespace Mostafaarafat\DataChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mostafaarafat\DataChat\Models\ChatConfig;

class Suggestion extends Model
{
    protected $table = 'datachat_suggestions';

    protected $fillable = [
        'config_id',
        'question',
        'category',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function config(): BelongsTo
    {
        return $this->belongsTo(ChatConfig::class, 'config_id');
    }

    /**
     * Scope to active suggestions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to ordered suggestions
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('id');
    }
}