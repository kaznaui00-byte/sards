<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Novel extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'cover_image_path',
        'genre',
        'length_type',
        'status',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    // صاحب الرواية
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // فصول الرواية
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }
}