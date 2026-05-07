<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortalSettings
{
    private const DISK = 'local';

    private const PATH = 'portal/settings.json';

    public function all(): array
    {
        return array_replace_recursive($this->defaults(), $this->stored());
    }

    public function branding(): array
    {
        return $this->all()['branding'] ?? [];
    }

    public function homepage(): array
    {
        return $this->all()['homepage'] ?? [];
    }

    public function affiliate(): array
    {
        return $this->all()['affiliate'] ?? [];
    }

    public function update(array $payload): void
    {
        $settings = array_replace_recursive($this->stored(), $payload);

        Storage::disk(self::DISK)->put(
            self::PATH,
            json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }

    public function forgetLogo(): void
    {
        $settings = $this->stored();

        unset($settings['branding']['logo_url'], $settings['branding']['logo_alt']);

        Storage::disk(self::DISK)->put(
            self::PATH,
            json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }

    private function stored(): array
    {
        if (! Storage::disk(self::DISK)->exists(self::PATH)) {
            return [];
        }

        $decoded = json_decode((string) Storage::disk(self::DISK)->get(self::PATH), true);

        return is_array($decoded) ? $decoded : [];
    }

    private function defaults(): array
    {
        $siteName = env('PORTAL_SITE_NAME', config('app.name', 'Arena Nalar'));

        return [
            'branding' => [
                'site_name' => $siteName,
                'tagline' => env('PORTAL_SITE_TAGLINE', 'Olahraga, perlengkapan, IT, dan hidroponik dalam satu portal editorial.'),
                'logo_url' => config('portal.branding.logo_url'),
                'logo_alt' => config('portal.branding.logo_alt', $siteName),
                'mark_text' => config('portal.branding.mark_text', Str::upper(Str::substr($siteName, 0, 2))),
            ],
            'homepage' => [
                'hero_title' => env('PORTAL_HERO_TITLE', 'Portal editorial yang putar arah ke olahraga, IT, dan hidroponik.'),
                'hero_description' => env('PORTAL_HERO_DESCRIPTION', 'Bangun mesin konten yang rutin terbit, punya gambar artikel, siap menampung link affiliate, dan tetap enak dibaca di production.'),
                'footer_note' => env('PORTAL_FOOTER_NOTE', 'Konten terbaru, rekomendasi perlengkapan, wawasan IT, dan panduan hidroponik untuk pembaca Indonesia.'),
            ],
            'affiliate' => [
                'disclosure' => env('PORTAL_AFFILIATE_DISCLOSURE', 'Sebagian rekomendasi dapat berisi link affiliate yang memberi komisi tanpa menambah harga untuk pembaca.'),
            ],
        ];
    }
}
