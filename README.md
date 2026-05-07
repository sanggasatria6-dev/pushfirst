# Portal Editorial Starter

Starter ini disusun untuk dipindahkan ke project Laravel baru. Fokus utamanya sekarang:

- portal editorial dengan niche olahraga, perlengkapan olahraga, IT, dan hidroponik
- panel admin untuk branding, upload logo, upload gambar artikel, dan produksi konten
- generator artikel berbasis Vertex API dengan output JSON yang lebih rapi
- placement affiliate yang bisa diisi sambil jalan, termasuk untuk link Involve Asia
- cover image otomatis satu gambar per artikel berdasarkan kategori

## Kondisi workspace

Repo ini bukan project Laravel yang utuh. Isi `laravel-ready/` adalah paket file yang ditujukan untuk disalin ke aplikasi Laravel asli. Di workspace ini juga belum tersedia `php` dan `composer`, jadi verifikasi runtime penuh harus dilakukan setelah file dipindah ke project Laravel tujuan.

## Struktur

- `laravel-ready/` file aplikasi yang dipindahkan ke Laravel baru
- `docs/SETUP.md` panduan setup, storage, queue, dan deploy
- `scripts/install-into-laravel.sh` helper copy ke project Laravel
- `scripts/deploy-vps.sh` helper deploy manual ke VPS

## Highlight perubahan

- Branding website tidak lagi bergantung pada placeholder lama. Nama portal, tagline, hero copy, dan logo sekarang bisa dikelola dari admin.
- Homepage diarahkan ulang ke portal editorial, bukan katalog banner.
- Banner visual kasar di homepage dihapus. Placement affiliate diarahkan menjadi kartu rekomendasi yang lebih halus di dalam artikel.
- Tema generator diubah ke:
  - `sports_training`
  - `sports_places`
  - `sports_gear`
  - `it_insights`
  - `hydroponics`
- Artikel sekarang punya dukungan `source_references` agar referensi bisa ditampilkan di frontend.
- Gambar artikel bisa diupload per kategori dari panel admin. Sistem memilih satu cover image stabil untuk tiap artikel.

## Alur pakai singkat

1. Buat project Laravel baru.
2. Salin isi `laravel-ready/` ke root project Laravel tersebut.
3. Jalankan migration, seeder, dan `php artisan storage:link`.
4. Buat admin pertama.
5. Isi `.env` untuk Vertex, queue, dan branding dasar.
6. Upload logo dan gambar kategori dari panel admin.
7. Tambahkan placement affiliate saat link Involve Asia sudah siap.
8. Jalankan scheduler dan worker queue di VPS.

## Copy helper

```bash
chmod +x scripts/install-into-laravel.sh
./scripts/install-into-laravel.sh /path/to/laravel-project
```

Script ini memakai `rsync --ignore-existing`, jadi aman untuk setup awal tanpa menimpa file target yang sudah ada.

## Setelah dipindah ke Laravel asli

Lihat detail lengkap di [docs/SETUP.md](docs/SETUP.md).
