<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Microsaas extends Model
{
    protected $table = 'microsaas';

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'description',
        'frontend_entry_url',
        'frontend_public_path',
        'backend_base_url',
        'price_label',
        'status',
        'is_featured',
        'activated_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'activated_at' => 'datetime',
    ];
}
