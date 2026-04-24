<?php

namespace Database\Seeders;

use App\Models\AffiliateBanner;
use App\Models\ArticleTopic;
use Illuminate\Database\Seeder;

class DemoPortalSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            ['keyword' => 'cara memilih tools SEO untuk bisnis kecil', 'search_intent' => 'commercial', 'category' => 'buyer_guides'],
            ['keyword' => 'perbandingan database mysql vs postgresql untuk pemula', 'search_intent' => 'informational', 'category' => 'informatics_learning'],
            ['keyword' => 'pengertian iot dan contoh implementasinya di industri', 'search_intent' => 'informational', 'category' => 'iot'],
            ['keyword' => 'rekomendasi laptop coding untuk mahasiswa informatika', 'search_intent' => 'commercial', 'category' => 'buyer_guides'],
            ['keyword' => 'belajar normalisasi database dengan contoh sederhana', 'search_intent' => 'informational', 'category' => 'informatics_learning'],
            ['keyword' => 'sensor iot untuk monitoring suhu ruangan', 'search_intent' => 'commercial', 'category' => 'iot'],
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
                'name' => 'Top Up Diamond Hero',
                'placement' => 'home_hero',
                'image_url' => 'https://via.placeholder.com/1200x628?text=Top+Up+Diamond',
                'target_url' => 'https://affiliate.example.com/topup-diamond',
                'cta_text' => 'Top Up Sekarang',
            ],
            [
                'name' => 'Banner Header Tech',
                'placement' => 'article_header',
                'image_url' => 'https://via.placeholder.com/1200x400?text=Tech+Affiliate',
                'target_url' => 'https://affiliate.example.com/tech',
                'cta_text' => 'Cek Promo',
            ],
            [
                'name' => 'Banner Inline Tools',
                'placement' => 'article_inline',
                'image_url' => 'https://via.placeholder.com/1200x400?text=Tools+Affiliate',
                'target_url' => 'https://affiliate.example.com/tools',
                'cta_text' => 'Lihat Tools',
            ],
            [
                'name' => 'Banner Footer Gaming',
                'placement' => 'article_footer',
                'image_url' => 'https://via.placeholder.com/1200x400?text=Gaming+Affiliate',
                'target_url' => 'https://affiliate.example.com/gaming',
                'cta_text' => 'Ambil Penawaran',
            ],
        ];

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
