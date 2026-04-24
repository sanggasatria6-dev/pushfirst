<?php

return [
    'admin_path' => env('ADMIN_PATH', 'studio-panel'),
    'microsaas_public_path' => env('MICROSAAS_PUBLIC_PATH', 'microsaas'),
    'ad_placeholder' => env('SEO_PLACEHOLDER_AD_CODE', '<div data-ad-slot="article-inline"></div>'),
    'vertex' => [
        'api_key' => env('VERTEX_API_KEY'),
        'project_id' => env('VERTEX_PROJECT_ID'),
        'location' => env('VERTEX_LOCATION', 'global'),
        'publisher' => env('VERTEX_PUBLISHER', 'google'),
        'model' => env('VERTEX_MODEL', 'gemini-2.5-flash'),
        'articles_per_run' => (int) env('VERTEX_ARTICLES_PER_RUN', 5),
    ],
    'seo' => [
        'daily_min_articles' => (int) env('SEO_DAILY_MIN_ARTICLES', 5),
        'daily_max_articles' => (int) env('SEO_DAILY_MAX_ARTICLES', 7),
        'allowed_categories' => [
            'buyer_guides',
            'iot',
            'informatics_learning',
        ],
    ],
];
