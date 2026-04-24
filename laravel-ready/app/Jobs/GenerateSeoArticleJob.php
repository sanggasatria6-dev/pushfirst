<?php

namespace App\Jobs;

use App\Models\ArticleTopic;
use App\Services\VertexSeoFactoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateSeoArticleJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 180;

    public function __construct(public int $topicId)
    {
        $this->onQueue('seo');
    }

    public function handle(VertexSeoFactoryService $service): void
    {
        $topic = ArticleTopic::query()->find($this->topicId);

        if (! $topic || ! $topic->is_active) {
            return;
        }

        $service->generateForTopic($topic);
    }
}
