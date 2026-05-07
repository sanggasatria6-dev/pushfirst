<?php

namespace App\Console\Commands;

use App\Services\VertexSeoFactoryService;
use Illuminate\Console\Command;

class GenerateSeoArticlesCommand extends Command
{
    protected $signature = 'seo:generate-daily {--limit=}';

    protected $description = 'Generate batch artikel editorial dari topik aktif menggunakan Vertex API.';

    public function handle(VertexSeoFactoryService $service): int
    {
        $limit = $this->option('limit') !== null
            ? (int) $this->option('limit')
            : (int) config('portal.vertex.articles_per_run', 12);

        $count = $service->generateDailyBatch($limit);

        $this->info("{$count} artikel dibuat.");

        return self::SUCCESS;
    }
}
