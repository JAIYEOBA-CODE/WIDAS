<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThreatScore extends Model
{
    protected $fillable = [
        'user_id',
        'source_ip',
        'score',
        'breakdown',
        'risk_level',
        'is_active',
        'last_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'breakdown' => 'array',
            'is_active' => 'boolean',
            'last_updated_at' => 'datetime',
            'score' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
