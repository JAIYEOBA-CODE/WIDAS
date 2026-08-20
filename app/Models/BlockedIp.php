<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockedIp extends Model
{
    use HasFactory;
    protected $fillable = [
        'ip_address',
        'reason',
        'blocked_by',
        'is_permanent',
        'blocked_at',
        'expires_at',
        'attempts',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_permanent' => 'boolean',
            'blocked_at' => 'datetime',
            'expires_at' => 'datetime',
            'attempts' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function blocker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('is_permanent', true)
                ->orWhere('expires_at', '>', now());
        });
    }

    public function isExpired(): bool
    {
        return !$this->is_permanent && $this->expires_at && now()->greaterThan($this->expires_at);
    }
}
