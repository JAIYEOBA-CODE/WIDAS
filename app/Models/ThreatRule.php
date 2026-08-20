<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThreatRule extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'severity',
        'threat_score',
        'patterns',
        'config',
        'is_active',
        'auto_block',
        'threshold',
        'action',
    ];

    protected function casts(): array
    {
        return [
            'patterns' => 'array',
            'config' => 'array',
            'is_active' => 'boolean',
            'auto_block' => 'boolean',
            'threshold' => 'integer',
            'threat_score' => 'integer',
        ];
    }

    public function intrusionEvents(): HasMany
    {
        return $this->hasMany(IntrusionEvent::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
