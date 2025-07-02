# Toko - CodeIgniter 4 Application

## Deskripsi
Toko adalah aplikasi web sederhana berbasis CodeIgniter 4 untuk manajemen produk, kategori, diskon, transaksi, dan keranjang belanja. Aplikasi ini cocok digunakan sebagai starter project toko online skala kecil-menengah, dengan fitur CRUD produk, kategori, checkout, serta integrasi diskon harian.

---

## Fitur

- **Manajemen Produk**
  - Tambah, edit, hapus produk dengan form sederhana.
  - Upload foto produk yang tersimpan di folder `img/`.
  - Download daftar produk dalam format PDF menggunakan Dompdf.

- **Manajemen Kategori**
  - Tambah, edit, hapus kategori produk untuk pengelompokan produk.

- **Manajemen Diskon**
  - Diskon harian otomatis berdasarkan tanggal pada tabel `diskon`.
  - Diskon diterapkan langsung pada transaksi di keranjang.

- **Keranjang Belanja**
  - Tambah produk ke keranjang.
  - Edit dan hapus item keranjang.
  - Kosongkan seluruh keranjang dengan satu klik.

- **Transaksi & Checkout**
  - Proses pembelian produk dari keranjang.
  - Checkout dengan perhitungan diskon otomatis.
  - Riwayat transaksi tersimpan di database.

- **Autentikasi**
  - Login dan logout user.
  - Proteksi halaman dengan filter autentikasi.

- **Lainnya**
  - Halaman profil dan kontak (opsional).
  - Integrasi API ongkir (jika diaktifkan).
  - Tampilan responsif dengan template admin.

---

## Instalasi

1. **Clone repository**
   ```
   git clone <url-repo-anda>
   cd rheno_ci_tugas
   ```

2. **Install dependency dengan Composer**
   ```
   composer install
   ```

3. **Copy file environment dan konfigurasi**
   ```
   cp env .env
   ```
   Edit file `.env` dan sesuaikan bagian berikut:
   ```
   CI_ENVIRONMENT = development
   app.baseURL = 'http://localhost:8080/'
   database.default.hostname = localhost
   database.default.database = nama_database
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
   ```

4. **Buat database dan import struktur**
   - Buat database di MySQL, misal: `toko_ci`
   - Import file SQL jika tersedia, atau buat tabel berikut minimal:
     - `produk` (id, nama, harga, jumlah, foto, created_at, updated_at)
     - `kategori` (id, nama)
     - `diskon` (id, nominal, tanggal)
     - `transaksi`, `transaksi_detail` (untuk riwayat transaksi)
   - Pastikan tabel `diskon` memiliki kolom `tanggal` bertipe DATE.

5. **Jalankan server**
   ```
   php spark serve
   ```
   atau gunakan XAMPP dan akses via browser ke `http://localhost:8080`

6. **(Opsional) Install Dompdf**
   ```
   composer require dompdf/dompdf
   ```

---

## Struktur Proyek

```
rheno_ci_tugas/
│
├── app/
│   ├── Config/           # Konfigurasi aplikasi & routes
│   ├── Controllers/      # Semua controller (ProdukController, TransaksiController, AuthController, dsb)
│   ├── Models/           # Semua model (ProductModel, TransactionModel, DiskonModel, dsb)
│   ├── Views/            # Semua view (v_login, v_produk, v_keranjang, v_produkPDF, dsb)
│   └── Filters/          # Filter autentikasi
│
├── public/               # Root web server (index.php, asset statis, gambar produk)
├── writable/             # Folder untuk logs, cache, uploads
├── vendor/               # Dependency composer (otomatis)
├── .env                  # Konfigurasi environment (baseURL, database, dsb)
├── composer.json         # Daftar dependency PHP
└── README.md             # Dokumentasi proyek
```

---

## Catatan

- Pastikan PHP 8.1+ dan ekstensi yang dibutuhkan sudah aktif.
- Untuk keamanan, arahkan root web server ke folder `public/`.
- Jika ada error "Whoops!", cek log di `writable/logs` dan pastikan `.env` sudah benar.
- Untuk fitur diskon, pastikan kolom `tanggal` ada di tabel `diskon`.
- Jika ingin menambah fitur, sesuaikan struktur tabel dan controller
