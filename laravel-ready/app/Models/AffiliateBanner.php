<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
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

    public function scopeActivePlacement($query, string $placement)
    {
        return $query
            ->where('placement', $placement)
            ->where('is_active', true);
    }

    public static function pickForPlacement(string $placement, int $limit = 1): Collection
    {
        $banners = static::query()
            ->activePlacement($placement)
            ->get();

        if ($banners->isEmpty()) {
            return new Collection();
        }

        return static::weightedShuffle($banners)->take($limit)->values();
    }

    public static function pickOneForPlacement(string $placement): ?self
    {
        return static::pickForPlacement($placement)->first();
    }

    private static function weightedShuffle(Collection $banners): Collection
    {
        return $banners
            ->map(function (self $banner): array {
                $weight = max(1, (int) $banner->weight);
                $random = random_int(1, PHP_INT_MAX) / PHP_INT_MAX;
                $score = -log(max($random, 1 / PHP_INT_MAX)) / $weight;

                return [
                    'score' => $score,
                    'banner' => $banner,
                ];
            })
            ->sortBy('score')
            ->pluck('banner')
            ->values();
    }
}
