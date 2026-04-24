<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $fillable = [
        'topic_id',
        'title',
        'slug',
        'meta_description',
        'excerpt',
        'content_html',
        'source_prompt',
        'generation_model',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(ArticleTopic::class, 'topic_id');
    }
}
