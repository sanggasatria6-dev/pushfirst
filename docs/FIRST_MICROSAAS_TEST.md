# Test Upload Micro-SaaS Pertama

Pakai folder:

- `microsaas-sample/`

## Cara test

1. Zip isi folder `microsaas-sample`.
2. Login ke admin portal.
3. Buka menu `Micro-SaaS`.
4. Isi:
   - `name`: nama produk
   - `slug`: slug produk
   - `backend_base_url`: URL backend VPS/app Anda
5. Upload file ZIP.
6. Buka URL hasil deploy:
   - `/microsaas/{slug}/`

## Yang akan terjadi

- sistem extract ZIP
- sistem publish ke folder public
- sistem tulis `portal-runtime.json`
- sistem tulis `portal-runtime.js`
- homepage menampilkan card produk baru

## Aturan build frontend Anda

Build frontend ZIP idealnya punya `index.html` di root hasil extract.

Contoh struktur yang aman:

```text
index.html
assets/
app.js
style.css
```

Kalau hasil ZIP Anda membungkus semua file di dalam satu folder, service deploy sekarang masih mencoba menormalkan itu selama `index.html` ada di folder tunggal tersebut.
