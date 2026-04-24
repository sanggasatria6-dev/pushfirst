<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleTopic;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class VertexSeoFactoryService
{
    public function pickTopicsForBatch(int $limit = 5)
    {
        return ArticleTopic::query()
            ->where('is_active', true)
            ->whereIn('category', config('portal.seo.allowed_categories', []))
            ->orderByRaw('COALESCE(last_generated_at, "1970-01-01 00:00:00") asc')
            ->limit($limit)
            ->get();
    }

    public function generateDailyBatch(int $limit = 5): int
    {
        $topics = $this->pickTopicsForBatch($limit);

        $created = 0;

        foreach ($topics as $topic) {
            $payload = $this->generateArticlePayload($topic);
            $this->persistArticle($topic, $payload);
            $topic->update(['last_generated_at' => now()]);
            $created++;
        }

        return $created;
    }

    public function generateForTopic(ArticleTopic $topic): Article
    {
        $payload = $this->generateArticlePayload($topic);
        $article = $this->persistArticle($topic, $payload);
        $topic->update(['last_generated_at' => now()]);

        return $article;
    }

    public function generateArticlePayload(ArticleTopic $topic): array
    {
        $prompt = $this->buildPrompt($topic);
        $model = config('portal.vertex.model');
        $publisher = config('portal.vertex.publisher', 'google');
        $projectId = config('portal.vertex.project_id');
        $location = config('portal.vertex.location', 'global');
        $apiKey = config('portal.vertex.api_key');

        $url = "https://aiplatform.googleapis.com/v1/projects/{$projectId}/locations/{$location}/publishers/{$publisher}/models/{$model}:generateContent";

        $response = Http::timeout(120)
            ->acceptJson()
            ->withHeaders([
                'X-Goog-Api-Key' => $apiKey,
            ])
            ->post($url, [
                'contents' => [[
                    'role' => 'user',
                    'parts' => [[
                        'text' => $prompt,
                    ]],
                ]],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'topP' => 0.9,
                    'responseMimeType' => 'application/json',
                ],
            ])
            ->throw()
            ->json();

        $rawText = data_get($response, 'candidates.0.content.parts.0.text');

        if (! $rawText) {
            throw new RuntimeException('Vertex tidak mengembalikan body artikel.');
        }

        $decoded = json_decode($rawText, true);

        if (! is_array($decoded)) {
            throw new RuntimeException('Respons Vertex bukan JSON valid.');
        }

        foreach (['title', 'slug', 'meta_description', 'excerpt', 'content_html'] as $requiredKey) {
            if (blank($decoded[$requiredKey] ?? null)) {
                throw new RuntimeException("Field {$requiredKey} kosong pada respons Vertex.");
            }
        }

        return [
            ...$decoded,
            'source_prompt' => $prompt,
            'generation_model' => $model,
        ];
    }

    public function persistArticle(ArticleTopic $topic, array $payload): Article
    {
        $slug = Str::slug($payload['slug']);

        if (Article::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::lower(Str::random(5));
        }

        return Article::create([
            'topic_id' => $topic->id,
            'title' => $payload['title'],
            'slug' => $slug,
            'meta_description' => $payload['meta_description'],
            'excerpt' => $payload['excerpt'],
            'content_html' => $payload['content_html'],
            'source_prompt' => $payload['source_prompt'],
            'generation_model' => $payload['generation_model'],
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    private function buildPrompt(ArticleTopic $topic): string
    {
        $adPlaceholder = config('portal.ad_placeholder', '<div data-ad-slot="article-inline"></div>');
        $categoryGuide = match ($topic->category) {
            'buyer_guides' => 'Fokus artikel pada saran pembelian, rekomendasi produk, perbandingan produk, buying guide, value for money, dan evaluasi fitur.',
            'iot' => 'Fokus artikel pada Internet of Things, sensor, embedded systems, otomasi, smart devices, dan penerapan IoT secara praktis.',
            'informatics_learning' => 'Fokus artikel pada pembelajaran teknik informatika, database, algoritma, pemrograman, sistem informasi, dan konsep software engineering untuk pelajar atau pemula menengah.',
            default => 'Fokus artikel pada topik yang diberikan.',
        };

        return <<<PROMPT
Anda adalah SEO content engine untuk portal affiliate dan micro-SaaS.

Tugas:
- Tulis artikel evergreen berkualitas tinggi dalam bahasa {$topic->language}
- Keyword utama: {$topic->keyword}
- Kategori topik: {$topic->category}
- Search intent: {$topic->search_intent}
- Negara target: {$topic->country_code}
- {$categoryGuide}
- Gaya bahasa natural, tidak kaku, tidak terasa seperti AI
- Hindari klaim palsu, angka palsu, atau referensi yang tidak bisa diverifikasi
- Jangan menulis tentang pengalaman pribadi palsu
- Fokus pada usefulness, readability, dan SEO on-page
- Jangan menulis tema di luar buyer guides, IoT, atau pembelajaran teknik informatika

Aturan konten:
- Panjang 1200-1800 kata
- Wajib ada satu H1, beberapa H2, dan beberapa H3
- Gunakan HTML valid saja: <h2>, <h3>, <p>, <ul>, <li>, <strong>
- Sisipkan placeholder iklan ini tepat satu kali di sekitar pertengahan artikel: {$adPlaceholder}
- Jangan gunakan markdown
- Jangan gunakan code fence
- Hindari keyword stuffing

Format output wajib:
Kembalikan JSON valid tanpa teks tambahan dengan struktur tepat seperti ini:
{
  "title": "judul artikel",
  "slug": "slug-url-artikel",
  "meta_description": "meta description maksimal 155 karakter",
  "excerpt": "ringkasan artikel 1-2 kalimat",
  "content_html": "<p>...</p>"
}
PROMPT;
    }
}
