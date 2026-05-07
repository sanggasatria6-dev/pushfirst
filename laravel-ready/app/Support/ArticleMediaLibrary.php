<?php

namespace App\Support;

use App\Models\Article;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleMediaLibrary
{
    public function gallery(): array
    {
        $gallery = [];

        foreach (array_keys(config('portal.seo.article_images.directories', [])) as $category) {
            $gallery[$category] = $this->listByCategory($category);
        }

        return $gallery;
    }

    public function listByCategory(string $category): array
    {
        return $this->collectForCategory($category)
            ->values()
            ->all();
    }

    public function storeMany(string $category, array $images): array
    {
        $stored = [];
        $directory = $this->primaryDirectory($category);

        foreach ($images as $image) {
            if (! $image instanceof UploadedFile) {
                continue;
            }

            $filename = now()->format('YmdHis').'-'.Str::random(8).'.'.$image->getClientOriginalExtension();
            $path = $image->storeAs($directory, $filename, 'public');

            $stored[] = $path;
        }

        return $stored;
    }

    public function delete(string $path): void
    {
        $path = ltrim($path, '/');

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return;
        }

        $publicAbsolute = public_path($path);

        if (is_file($publicAbsolute)) {
            File::delete($publicAbsolute);
        }
    }

    public function pickForArticle(Article $article): ?array
    {
        $category = $article->topic?->category;

        if (! $category) {
            return null;
        }

        $files = $this->collectForCategory($category)->values();

        if ($files->isEmpty()) {
            return null;
        }

        $seed = crc32((string) ($article->slug ?: $article->id));
        $index = (int) ($seed % $files->count());

        return $files[$index];
    }

    private function collectForCategory(string $category): Collection
    {
        $directories = (array) config('portal.seo.article_images.directories', []);
        $candidates = collect($directories[$category] ?? [])
            ->filter(fn ($dir) => is_string($dir) && trim($dir) !== '')
            ->map(fn ($dir) => trim($dir, '/'))
            ->unique()
            ->values();

        $images = collect();

        foreach ($candidates as $directory) {
            $images = $images->merge($this->collectFromStorageDirectory($directory));
            $images = $images->merge($this->collectFromPublicDirectory($directory));
        }

        return $images
            ->unique(fn ($image) => ($image['storage'] ?? 'public').':'.$image['path'])
            ->sortBy('path')
            ->values();
    }

    private function collectFromStorageDirectory(string $directory): Collection
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($directory)) {
            return collect();
        }

        return collect($disk->files($directory))
            ->filter(fn ($path) => $this->isImage($path))
            ->map(fn ($path) => [
                'path' => $path,
                'name' => basename($path),
                'url' => $disk->url($path),
                'storage' => 'public',
            ])
            ->values();
    }

    private function collectFromPublicDirectory(string $directory): Collection
    {
        $absolute = public_path($directory);

        if (! is_dir($absolute)) {
            return collect();
        }

        return collect(File::files($absolute))
            ->filter(fn ($file) => $this->isImage($file->getFilename()))
            ->map(fn ($file) => [
                'path' => $directory.'/'.$file->getFilename(),
                'name' => $file->getFilename(),
                'url' => asset($directory.'/'.$file->getFilename()),
                'storage' => 'public-root',
            ])
            ->values();
    }

    private function primaryDirectory(string $category): string
    {
        $directories = (array) config('portal.seo.article_images.directories', []);
        $first = collect($directories[$category] ?? [])
            ->filter(fn ($dir) => is_string($dir) && trim($dir) !== '')
            ->map(fn ($dir) => trim($dir, '/'))
            ->first();

        return $first ?: 'article-images/'.$category;
    }

    private function isImage(string $path): bool
    {
        $ext = Str::lower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true);
    }
}
