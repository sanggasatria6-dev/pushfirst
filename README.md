# Mega Portal Laravel Backend Starter

Starter ini disusun untuk dipindahkan ke project Laravel baru. Fokusnya:

- login admin custom
- dashboard backend
- upload frontend Micro-SaaS
- pabrik artikel SEO dengan Vertex AI API
- katalog home yang otomatis membaca Micro-SaaS dan banner affiliate
- queue job untuk generate artikel SEO agar proses AI tidak membebani request admin

## Kondisi workspace saat ini

Environment ini belum memiliki `php`, `composer`, dan skeleton Laravel, jadi file di repo ini dibuat sebagai paket implementasi siap-tempel.

## Struktur

- `laravel-ready/` berisi file Laravel yang bisa dipindahkan ke app baru
- `docs/SETUP.md` berisi langkah integrasi

## Alur singkat

1. Buat project Laravel baru di mesin Anda.
2. Salin isi `laravel-ready/` ke project tersebut.
3. Jalankan migration.
4. Buat admin pertama via seeder atau Tinker.
5. Set `.env` untuk admin path, Vertex API, dan path upload Micro-SaaS.
6. Jalankan scheduler untuk pabrik SEO.

## Helper install

Jika Anda sudah punya project Laravel baru, Anda bisa copy starter ini dengan:

```bash
chmod +x scripts/install-into-laravel.sh
./scripts/install-into-laravel.sh /path/to/laravel-project
```

Script ini memakai `rsync --ignore-existing`, jadi file baru akan disalin tanpa menimpa file target yang sudah ada.






• Sudah saya ubah:

  - Artikel harian sekarang acak 5-7 per hari.
  - Tema dibatasi cuma 3:
      - buyer_guides
      - iot
      - informatics_learning
  - Prompt Vertex juga saya kunci agar tidak keluar dari 3 tema itu.
  - Topik admin sekarang punya field category.

  File penting yang berubah:

  - laravel-ready/config/portal.php
  - laravel-ready/app/Services/VertexSeoFactoryService.php
  - laravel-ready/app/Console/Commands/DispatchSeoArticlesCommand.php
  - laravel-ready/database/seeders/DemoPortalSeeder.php
  - docs/SETUP.md

  Cara test lokal
  Paling gampang pakai Laravel baru + SQLite.

  1. Buat project Laravel baru:

  composer create-project laravel/laravel mega-portal
  cd mega-portal

  2. Copy starter ini:

  bash /home/sangga/Documents/project_SEO_Pabrik/scripts/install-into-laravel.sh $(pwd)

  3. Tambahkan middleware alias admin ke bootstrap/app.php pakai snippet ini:

  - docs/bootstrap-app-snippet.php

  4. Buat file database SQLite:

  touch database/database.sqlite

  5. Buat .env di root Laravel Anda. Isinya minimal begini:

  APP_NAME="Mega Portal"
  APP_ENV=local
  APP_KEY=
  APP_DEBUG=true
  APP_URL=http://127.0.0.1:8000

  DB_CONNECTION=sqlite
  DB_DATABASE=/full/path/ke/project-laravel/database/database.sqlite

  SESSION_DRIVER=database
  CACHE_STORE=file
  QUEUE_CONNECTION=database

  ADMIN_PATH=studio-panel
  MICROSAAS_PUBLIC_PATH=microsaas

  VERTEX_API_KEY=isi_api_key_anda
  VERTEX_PROJECT_ID=isi_project_id_anda
  VERTEX_LOCATION=global
  VERTEX_MODEL=gemini-2.5-flash
  VERTEX_PUBLISHER=google

  SEO_DAILY_MIN_ARTICLES=5
  SEO_DAILY_MAX_ARTICLES=7

  SEO_PLACEHOLDER_AD_CODE="<div data-ad-slot='placeholder-top'></div>"

  6. Generate app key dan migrate:

  php artisan key:generate
  php artisan session:table
  php artisan queue:table
  php artisan migrate --seed

  7. Jalankan lokal:

  php artisan serve
  php artisan queue:work --queue=seo

  Login admin lokal
  Seeder default:

  - email: admin@example.com
  - password: ChangeThisPassword123!

  Buka:

  - http://127.0.0.1:8000/studio-panel/login

  Cara lihat “geraknya”

  - Home: http://127.0.0.1:8000/
  - Dashboard admin: http://127.0.0.1:8000/studio-panel
  - Upload Micro-SaaS: menu Micro-SaaS
  - SEO Factory: menu SEO Factory

  Untuk test upload app pertama:

  1. Zip folder microsaas-sample
  2. Upload di dashboard
  3. Isi backend_base_url
  4. Buka hasilnya di /microsaas/{slug}/

  Untuk test generator artikel:

  php artisan seo:dispatch-daily

  Lalu worker queue:work akan memproses job satu-satu.


  - jangan commit .env ke git
  - jangan taruh API key di public/
  - jangan taruh key di frontend
  - permission file ketat saat di VPS, misalnya chmod 600 .env

  Untuk kasus Anda, belum perlu tempat lain. .env sudah cukup aman kalau servernya benar. Yang
  penting VERTEX_API_KEY hanya dipakai di backend Laravel.

  Kalau setelah ini Anda mau, saya bisa bantu tahap berikut yang paling pas: saya pandu Anda
  langkah demi langkah saat Anda mulai bikin project Laravel lokalnya, lalu kita cek kalau ada
  error satu-satu.