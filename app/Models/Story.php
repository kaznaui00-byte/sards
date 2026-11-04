<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Story extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'story_type',
        'summary',
        'content',
        'cover_path',
        'status',
    ];

    // صاحب القصة
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}