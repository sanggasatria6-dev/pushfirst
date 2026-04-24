# Setup Laravel

Dokumen ini mengasumsikan Anda membuat project baru, misalnya:

```bash
composer create-project laravel/laravel mega-portal
```

Lalu salin isi folder `laravel-ready/` ke root project Laravel Anda.

## 1. File env

Tambahkan konfigurasi berikut ke `.env`:

```env
APP_NAME="Mega Portal"
APP_URL=http://localhost:8000

ADMIN_PATH=studio-panel
MICROSAAS_PUBLIC_PATH=microsaas
MICROSAAS_RELEASES_DISK=local

VERTEX_API_KEY=your-vertex-api-key
VERTEX_PROJECT_ID=your-gcp-project-id
VERTEX_LOCATION=global
VERTEX_MODEL=gemini-2.5-flash
VERTEX_PUBLISHER=google
SEO_DAILY_MIN_ARTICLES=5
SEO_DAILY_MAX_ARTICLES=7

SEO_DEFAULT_AUTHOR="Admin Portal"
SEO_PLACEHOLDER_AD_CODE="<div data-ad-slot='placeholder-top'></div>"
```

Jika Anda memilih auth berbasis service account, ganti implementasi service HTTP di `VertexSeoFactoryService`.

File tambahan yang harus ikut disalin:

- `config/portal.php`

## 2. Database

Migration yang perlu dijalankan:

```bash
php artisan migrate
php artisan db:seed
```

Tambahkan admin pertama:

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('ChangeThisPassword123!'),
    'is_admin' => true,
]);
```

## 3. Middleware alias

Di Laravel 11, buka `bootstrap/app.php` dan tambahkan alias middleware:

```php
->withMiddleware(function ($middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\EnsureAdmin::class,
    ]);
})
```

## 4. User model

Pastikan model `User` punya kolom `is_admin` pada `$fillable` atau gunakan `$guarded = [];`.

## 5. Storage dan folder Micro-SaaS

Folder build Micro-SaaS disimpan ke:

- `storage/app/microsaas/{slug}/releases/{timestamp}`
- symlink/copy aktif di `public/microsaas/{slug}`

Pastikan web server nanti mengizinkan static file dari folder `public/microsaas`.

Setiap frontend yang diupload juga otomatis mendapat:

- `portal-runtime.json`
- `portal-runtime.js`

Isi file itu memuat `backend_base_url`, `slug`, dan metadata app, jadi frontend baru Anda cukup membaca runtime config itu tanpa rebuild ulang.

Contoh pemakaian frontend ada di:

- `resources/views/microsaas/runtime-example.blade.php`
- `../microsaas-sample/`

## 6. Scheduler

Tambahkan ke cron server nanti:

```bash
* * * * * cd /path/to/your/laravel-app && php artisan schedule:run >> /dev/null 2>&1
```

Di Laravel 11, scheduler didefinisikan di `routes/console.php`.

Untuk mode queue yang lebih aman, set juga `.env`:

```env
QUEUE_CONNECTION=database
```

Lalu jalankan:

```bash
php artisan queue:table
php artisan migrate
php artisan seo:dispatch-daily
php artisan queue:work --queue=seo
```

Di VPS nanti, queue worker sebaiknya dijalankan terus lewat Supervisor atau systemd.

## 7. Routing wildcard subdomain nanti di VPS

Saat pindah ke VPS, Anda bisa map subdomain ke folder:

- `public/microsaas/{slug}`

Tetapi starter ini tetap jalan dulu dengan pola URL:

- `https://domain.com/microsaas/{slug}/`

## 8. Catatan integrasi

- login admin: `/{$ADMIN_PATH}/login`
- dashboard: `/{$ADMIN_PATH}`
- upload Micro-SaaS: `/{$ADMIN_PATH}/microsaas`
- SEO factory: `/{$ADMIN_PATH}/seo`
- runtime config Micro-SaaS: `/microsaas/{slug}/config.json`

## 9. Vertex API

Starter ini memakai endpoint REST `generateContent` milik Vertex AI dan memaksa respons JSON agar parsing lebih stabil. Dokumentasi resmi:

- https://cloud.google.com/vertex-ai/generative-ai/docs/reference/rest

## 10. Uji upload pertama

Panduan praktis ada di:

- `docs/FIRST_MICROSAAS_TEST.md`

## 11. Fokus tema artikel

Starter ini sekarang dibatasi ke 3 kategori topik:

- `buyer_guides`
- `iot`
- `informatics_learning`

Generator harian akan mengambil topik aktif dari kategori itu saja, lalu mengirim 5-7 job artikel per hari secara acak.
