<?php

namespace App\Console\Commands;

use App\Services\VertexSeoFactoryService;
use Illuminate\Console\Command;

class GenerateSeoArticlesCommand extends Command
{
    protected $signature = 'seo:generate-daily {--limit=5}';

    protected $description = 'Generate artikel SEO harian dari topik aktif menggunakan Vertex API.';

    public function handle(VertexSeoFactoryService $service): int
    {
        $count = $service->generateDailyBatch((int) $this->option('limit'));

        $this->info("{$count} artikel dibuat.");

        return self::SUCCESS;
    }
}
