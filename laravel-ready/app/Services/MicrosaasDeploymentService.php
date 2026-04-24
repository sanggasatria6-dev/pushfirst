<?php

namespace App\Services;

use App\Models\Microsaas;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class MicrosaasDeploymentService
{
    public function deploy(array $attributes, UploadedFile $uploadedZip): Microsaas
    {
        $slug = Str::slug($attributes['slug']);
        $timestamp = now()->format('YmdHis');
        $releaseRoot = storage_path("app/microsaas/{$slug}/releases/{$timestamp}");
        $zip = new ZipArchive();

        File::ensureDirectoryExists($releaseRoot);

        if ($zip->open($uploadedZip->getRealPath()) !== true) {
            throw new RuntimeException('ZIP build tidak bisa dibuka.');
        }

        $zip->extractTo($releaseRoot);
        $zip->close();

        $normalizedRoot = $this->normalizeExtractedBuild($releaseRoot);
        $this->assertIndexExists($normalizedRoot);

        $microsaas = Microsaas::create([
            ...$attributes,
            'slug' => $slug,
            'frontend_public_path' => config('portal.microsaas_public_path', 'microsaas')."/{$slug}",
            'frontend_entry_url' => url(trim(config('portal.microsaas_public_path', 'microsaas'), '/')."/{$slug}/"),
            'status' => 'draft',
        ]);

        $this->publishRelease($slug, $normalizedRoot);

        $microsaas->update([
            'status' => 'active',
            'activated_at' => now(),
        ]);

        $this->writeRuntimeConfig($microsaas->fresh());

        return $microsaas->fresh();
    }

    public function activate(Microsaas $microsaas): void
    {
        $releasesPath = storage_path("app/microsaas/{$microsaas->slug}/releases");
        $latestRelease = collect(File::directories($releasesPath))->sortDesc()->first();

        if (! $latestRelease) {
            throw new RuntimeException('Release tidak ditemukan.');
        }

        $this->publishRelease($microsaas->slug, $latestRelease);

        $microsaas->update([
            'status' => 'active',
            'activated_at' => now(),
        ]);

        $this->writeRuntimeConfig($microsaas->fresh());
    }

    public function remove(Microsaas $microsaas): void
    {
        File::deleteDirectory(storage_path("app/microsaas/{$microsaas->slug}"));
        File::deleteDirectory(public_path(trim(config('portal.microsaas_public_path', 'microsaas'), '/')."/{$microsaas->slug}"));
        $microsaas->delete();
    }

    private function normalizeExtractedBuild(string $releaseRoot): string
    {
        $children = collect(File::directories($releaseRoot));

        if ($children->count() !== 1) {
            return $releaseRoot;
        }

        $onlyChild = $children->first();

        if (File::exists($onlyChild.'/index.html')) {
            return $onlyChild;
        }

        return $releaseRoot;
    }

    private function assertIndexExists(string $path): void
    {
        if (! File::exists($path.'/index.html')) {
            throw new RuntimeException('Build frontend wajib memiliki file index.html di root hasil extract.');
        }
    }

    private function publishRelease(string $slug, string $releasePath): void
    {
        $publicBase = public_path(trim(config('portal.microsaas_public_path', 'microsaas'), '/'));
        $publicTarget = "{$publicBase}/{$slug}";

        File::ensureDirectoryExists($publicBase);
        File::deleteDirectory($publicTarget);
        File::copyDirectory($releasePath, $publicTarget);
    }

    private function writeRuntimeConfig(Microsaas $microsaas): void
    {
        $publicDir = public_path(trim(config('portal.microsaas_public_path', 'microsaas'), '/')."/{$microsaas->slug}");

        File::put(
            "{$publicDir}/portal-runtime.json",
            json_encode([
                'name' => $microsaas->name,
                'slug' => $microsaas->slug,
                'backend_base_url' => $microsaas->backend_base_url,
                'frontend_entry_url' => $microsaas->frontend_entry_url,
                'generated_at' => now()->toIso8601String(),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        File::put(
            "{$publicDir}/portal-runtime.js",
            'window.__PORTAL_RUNTIME__ = '.json_encode([
                'name' => $microsaas->name,
                'slug' => $microsaas->slug,
                'backend_base_url' => $microsaas->backend_base_url,
                'frontend_entry_url' => $microsaas->frontend_entry_url,
            ], JSON_UNESCAPED_SLASHES).';'
        );
    }
}
