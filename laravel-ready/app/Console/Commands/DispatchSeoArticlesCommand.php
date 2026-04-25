<?php

namespace App\Console\Commands;

use App\Jobs\GenerateSeoArticleJob;
use App\Services\VertexSeoFactoryService;
use Illuminate\Console\Command;

class DispatchSeoArticlesCommand extends Command
{
    protected $signature = 'seo:dispatch-daily {--limit=}';

    protected $description = 'Dispatch artikel SEO harian ke queue worker.';

    public function handle(VertexSeoFactoryService $service): int
    {
        $min = (int) config('portal.seo.daily_min_articles', 5);
        $max = (int) config('portal.seo.daily_max_articles', 7);
        $configuredLimit = config('portal.seo.dispatch_limit_per_run');
        $limit = $this->option('limit') !== null
            ? (int) $this->option('limit')
            : ($configuredLimit !== null
                ? (int) $configuredLimit
                : random_int($min, max($min, $max)));

        $topics = $service->pickTopicSlotsForBatch($limit);

        foreach ($topics as $topic) {
            GenerateSeoArticleJob::dispatch($topic->id);
        }

        $this->info("{$topics->count()} job artikel dikirim ke queue.");

        return self::SUCCESS;
    }
}
