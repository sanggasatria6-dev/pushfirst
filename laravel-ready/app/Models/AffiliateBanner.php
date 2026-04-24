<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateBanner extends Model
{
    protected $fillable = [
        'name',
        'placement',
        'image_url',
        'target_url',
        'cta_text',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
