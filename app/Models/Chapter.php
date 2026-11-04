<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    protected $fillable = [
        'novel_id',
        'title',
        'content',
        'chapter_number',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'chapter_number' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    // الرواية التي ينتمي إليها الفصل
    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}