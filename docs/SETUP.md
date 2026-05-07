# Setup Portal Editorial

Dokumen ini mengasumsikan Anda membuat project Laravel baru, lalu menyalin isi `laravel-ready/` ke root project tersebut.

## 1. Buat project Laravel

```bash
composer create-project laravel/laravel portal-editorial
cd portal-editorial
```

Salin starter ini:

```bash
bash /home/sangga/Documents/project_SEO_Pabrik/scripts/install-into-laravel.sh "$(pwd)"
```

## 2. Konfigurasi `.env`

Minimal isi:

```env
APP_NAME="Arena Nalar"
APP_URL=https://domain-anda.com

ADMIN_PATH=studio-panel
MICROSAAS_PUBLIC_PATH=microsaas
MICROSAAS_RELEASES_DISK=local

QUEUE_CONNECTION=database
SESSION_DRIVER=database

VERTEX_API_KEY=your-vertex-api-key
VERTEX_PROJECT_ID=your-gcp-project-id
VERTEX_LOCATION=global
VERTEX_MODEL=gemini-2.5-pro
VERTEX_PUBLISHER=google
VERTEX_MAX_OUTPUT_TOKENS=2100
VERTEX_TEMPERATURE=0.28
VERTEX_TOP_P=0.9
VERTEX_ARTICLES_PER_RUN=12

SEO_DAILY_MIN_ARTICLES=12
SEO_DAILY_MAX_ARTICLES=18
SEO_ARTICLE_MIN_WORDS=700
SEO_ARTICLE_MAX_WORDS=1200

PORTAL_SITE_NAME="Arena Nalar"
PORTAL_SITE_TAGLINE="Olahraga, perlengkapan, IT, dan hidroponik dalam satu portal editorial."
PORTAL_HERO_TITLE="Portal editorial yang putar arah ke olahraga, IT, dan hidroponik."
PORTAL_HERO_DESCRIPTION="Bangun mesin konten yang rutin terbit, punya gambar artikel, siap menampung link affiliate, dan tetap enak dibaca di production."
PORTAL_AFFILIATE_DISCLOSURE="Sebagian rekomendasi dapat berisi link affiliate yang memberi komisi tanpa menambah harga untuk pembaca."
```

Catatan:

- `PORTAL_LOGO_URL` tidak wajib jika Anda akan upload logo dari admin.
- `VERTEX_API_KEY` tetap hanya dipakai di backend Laravel.

## 3. Middleware alias

Di Laravel 11, buka `bootstrap/app.php` lalu tambahkan:

```php
->withMiddleware(function ($middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\EnsureAdmin::class,
    ]);
})
```

## 4. Database dan storage

Jalankan:

```bash
php artisan key:generate
php artisan session:table
php artisan queue:table
php artisan migrate --seed
php artisan storage:link
```

Buat admin pertama:

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

`storage:link` penting karena logo upload dan gambar artikel kategori memakai disk `public`.

## 5. Panel admin

Masuk ke:

- `/studio-panel/login`

Di menu `Konten & Media`, Anda sekarang bisa:

- mengubah nama website, tagline, copy hero, dan disclosure affiliate
- upload logo website
- upload banyak gambar artikel per kategori
- menyimpan placement affiliate
- generate batch artikel

## 6. Kategori konten aktif

Starter ini diarahkan ke 5 pilar:

- `sports_training`
- `sports_places`
- `sports_gear`
- `it_insights`
- `hydroponics`

Artikel yang dibuat akan mengambil satu cover image berdasarkan kategori. Jika belum ada gambar yang diupload, sistem jatuh ke cover SVG otomatis.

## 7. Sistem gambar artikel

Tempat uploadnya ada di panel admin `Konten & Media`.

Alurnya:

1. Pilih kategori.
2. Upload beberapa gambar sekaligus.
3. Sistem menyimpan gambar ke `storage/app/public/article-images/...`.
4. Frontend mengambil satu gambar cover stabil untuk tiap artikel dari kategori yang sesuai.

Ini berarti Anda tidak perlu upload gambar satu per satu ke setiap artikel kalau workflow Anda memang berbasis tag atau kategori.

## 8. Sistem affiliate

Saat akun Involve Asia sudah siap:

1. Buka panel `Konten & Media`.
2. Tambahkan placement affiliate.
3. Isi `target_url` dengan link affiliate Anda.
4. `image_url` opsional.

Placement inline akan tampil sebagai kartu rekomendasi di artikel, bukan banner besar yang merusak layout.

## 9. Queue dan scheduler

Jalankan worker:

```bash
php artisan queue:work --queue=seo
```

Tambahkan cron:

```bash
* * * * * cd /path/to/your/laravel-app && php artisan schedule:run >> /dev/null 2>&1
```

Di VPS production, queue worker sebaiknya dijalankan permanen lewat Supervisor atau systemd.

## 10. Produksi artikel volume tinggi

Command manual:

```bash
php artisan seo:dispatch-daily
php artisan seo:generate-daily --limit=12
```

Yang pertama mendorong job ke queue. Yang kedua menjalankan generate langsung tanpa queue.

## 11. Catatan deploy

Untuk deploy manual:

```bash
bash /home/sangga/Documents/project_SEO_Pabrik/scripts/deploy-vps.sh /home/sangga/Documents/project_SEO_Pabrik /var/www/domain-anda php8.3-fpm
```

Sebelum deploy, pastikan:

- `.env` production sudah benar
- `storage` writable oleh user web server
- `php artisan config:cache` dan `php artisan route:cache` dijalankan di app final jika environment sudah stabil
- worker queue hidup setelah deploy

## 12. Batasan saat ini

- Workspace starter ini tidak membawa skeleton Laravel penuh.
- Verifikasi sintaks PHP di repo ini tidak bisa dijalankan di workspace sekarang karena binary `php` belum tersedia.
- Referensi artikel sekarang sudah punya struktur tersendiri, tetapi validasi kebenaran sumber tetap perlu dipantau di environment production dan proses editorial Anda.
