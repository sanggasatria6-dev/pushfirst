<?php

namespace Database\Seeders;

use App\Models\AffiliateBanner;
use App\Models\ArticleTopic;
use Illuminate\Database\Seeder;

class DemoPortalSeeder extends Seeder
{
    public function run(): void
    {
        ArticleTopic::query()
            ->whereNotIn('category', config('portal.seo.allowed_categories', []))
            ->delete();

        $topics = [
            ['keyword' => 'teknik footwork badminton untuk pemula', 'search_intent' => 'informational', 'category' => 'sports_training'],
            ['keyword' => 'cara memilih lapangan badminton terdekat yang bagus', 'search_intent' => 'commercial', 'category' => 'sports_places'],
            ['keyword' => 'cara memilih raket badminton untuk intermediate', 'search_intent' => 'commercial', 'category' => 'sports_gear'],
            ['keyword' => 'cara deploy laravel ke vps dengan aman', 'search_intent' => 'informational', 'category' => 'it_insights'],
            ['keyword' => 'cara menanam selada hidroponik untuk pemula', 'search_intent' => 'informational', 'category' => 'hydroponics'],
        ];

        foreach ($topics as $topic) {
            ArticleTopic::updateOrCreate(
                ['keyword' => $topic['keyword']],
                [
                    'category' => $topic['category'],
                    'language' => 'id',
                    'country_code' => 'ID',
                    'search_intent' => $topic['search_intent'],
                    'is_active' => true,
                ]
            );
        }

        $banners = [
            [
                'name' => 'Rekomendasi Partner Olahraga',
                'placement' => 'article_inline',
                'image_url' => null,
                'target_url' => 'https://affiliate.example.com/sports-gear',
                'cta_text' => 'Cek Rekomendasi',
            ],
        ];

        AffiliateBanner::query()->delete();

        foreach ($banners as $banner) {
            AffiliateBanner::updateOrCreate(
                ['name' => $banner['name'], 'placement' => $banner['placement']],
                [
                    ...$banner,
                    'weight' => 10,
                    'is_active' => true,
                ]
            );
        }
    }
}
