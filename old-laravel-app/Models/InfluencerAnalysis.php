<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InfluencerAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'influencer_id',
        'platform',
        'username',
        'full_name',
        'followers_count',
        'following_count',
        'posts_count',
        'biography',
        'external_url',
        'is_verified',
        'is_private',
        'profile_pic_url',
        'category',
        'engagement_rate',
        'analysis_data',
        'status',
        'error_message',
        'analyzed_at'
    ];

    protected $casts = [
        'analysis_data' => 'array',
        'is_verified' => 'boolean',
        'is_private' => 'boolean',
        'analyzed_at' => 'datetime',
        'engagement_rate' => 'decimal:2'
    ];

    public function influencer(): BelongsTo
    {
        return $this->belongsTo(Influencer::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
