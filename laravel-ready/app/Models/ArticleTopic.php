<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleTopic extends Model
{
    protected $fillable = [
        'keyword',
        'category',
        'search_intent',
        'language',
        'country_code',
        'is_active',
        'last_generated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_generated_at' => 'datetime',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'topic_id');
    }
}
