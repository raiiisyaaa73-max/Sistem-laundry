# DOKUMEN PENGUMPULAN PROYEK ASAS XII
## Sistem Informasi Manajemen Laundry Berbasis Web (SIM Laundry)

### Data Identitas Siswa:
* **Nama Lengkap:** Nadia Miranti
* **Kelas:** XII RPL 1 / XII RPL 2
* **Link GitHub Repository:** https://github.com/Nadia-miranti/Luxury
* **Teknologi:** Laravel (PHP 8.5, MySQL), Bootstrap 5, JavaScript

---

## 1. PENJELASAN DATABASE & MIGRATION (Untuk Kolom 4 Google Form)

Basis data aplikasi SIM Laundry bernama `db_laundry` pada MySQL yang memuat 3 tabel utama yang saling berelasi:

### A. Tabel `users`
* **File:** `database/migrations/0001_01_01_000000_create_users_table.php`
* **Kolom:**
  - `id` (BigInteger, Primary Key)
  - `name` (String, Nama lengkap pengguna)
  - `email` (String, Unique, Email pengguna)
  - `password` (String, Hash Bcrypt sandi)
  - `role` (Enum: `'admin'`, `'kasir'`, default `'kasir'`): Membedakan hak akses halaman & modul operasional.
  - `timestamps` (`created_at` & `updated_at`)

### B. Tabel `layanans`
* **File:** `database/migrations/2026_11_24_052329_create_layanans_table.php`
* **Kolom:**
  - `id` (BigInteger, Primary Key)
  - `nama_layanan` (String, Nama paket cuci)
  - `harga_per_kg` (Integer, Tarif dalam rupiah per kilogram)
  - `estimasi_waktu` (String, Perkiraan waktu pengerjaan)
  - `timestamps`

### C. Tabel `transaksis`
* **File:** `database/migrations/2026_11_24_052339_create_transaksis_table.php`
* **Kolom:**
  - `id` (BigInteger, Primary Key)
  - `layanan_id` (ForeignId): Berelasi ke `layanans(id)` dengan `onDelete('cascade')`.
  - `nama_pelanggan` (String, Nama pemilik cucian)
  - `no_hp` (String, Nomor telepon / WhatsApp)
  - `berat` (Decimal 8,2, Berat cucian dalam kilogram)
  - `total_harga` (Integer, Hasil perkalian berat * harga_per_kg)
  - `status` (Enum: `'Proses'`, `'Selesai'`, default `'Proses'`)
  - `timestamps`

> *[Sisipkan Screenshot code file migration atau screenshot tabel di phpMyAdmin di sini]*

---

## 2. LOGIN & REGISTER SERTA PERBEDAAN ROLE (Untuk Kolom 5 Google Form)

### A. Perbedaan Hak Akses Role Admin vs Kasir
1. **Role Administrator (Admin):**
   * Memiliki akses penuh (*Full Access*) ke seluruh sistem.
   * Mengelola CRUD data layanan cuci: Menambah jenis layanan baru, mengubah tarif/waktu, dan menghapus layanan.
   * Mengelola seluruh data transaksi (mencatat cucian baru, mengubah status proses ke selesai, cetak nota, dan menghapus data).
   * Mengakses **Laporan Keuangan** untuk memantau omzet pemasukan laundry.
2. **Role Kasir:**
   * Memiliki akses operasional harian (*Operational Access*).
   * Mengakses Dashboard dan Rekap Transaksi.
   * Mencatat pesanan cucian baru pelanggan dan memperbarui status pengerjaan dari *Proses* menjadi *Selesai*.
   * Mencetak struk/nota pembayaran untuk pelanggan.
   * Melihat daftar layanan cuci (*Read-only*) sebagai referensi timbangan, tanpa tombol aksi tambah/ubah/hapus layanan.
   * Tidak memiliki akses ke menu Laporan Keuangan Administrator.

### B. Kredensial Akun Pengujian
* **Admin:** `admin@laundry.com` (atau `admin@gmail.com`) | Password: `password`
* **Kasir:** `kasir@laundry.com` (atau `kasir@gmail.com`) | Password: `password`

> *[Sisipkan Screenshot Login & Register pada 3 ukuran: Desktop (1280px), Tablet (768px), dan HP (375px)]*

---

## 3. CRUD DATA LAYANAN (Untuk Kolom 6 Google Form)

Fitur CRUD Layanan memfasilitasi pengelolaan paket cuci secara dinamis oleh Administrator:
1. **Reguler:** Rp 7.000 / kg (3-4 hari)
2. **Ekspres:** Rp 12.000 / kg (1-2 hari)
3. **Superkilat:** Rp 25.000 / kg (5 jam selesai)

### Fungsionalitas CRUD:
* **Create (Tambah):** Menambahkan paket layanan baru melalui modal form.
* **Read (Tampil):** Menampilkan daftar layanan lengkap beserta tarif per kg dalam format Rupiah dan estimasi waktu.
* **Update (Ubah):** Mengedit nama, harga per kg, atau durasi pengerjaan.
* **Delete (Hapus):** Menghapus layanan dengan dialog konfirmasi keamanan.

> *[Sisipkan Screenshot CRUD Layanan pada ukuran Desktop, Tablet, dan HP]*

---

## 4. ORDER & REKAP ORDER TRANSAKSI (Untuk Kolom 7 Google Form)

### A. Form Pencatatan Transaksi (Order Baru)
* **Input Data:** Nama Pelanggan, Nomor HP/WhatsApp, Pilihan Jenis Layanan, dan Berat Timbangan Cucian (kg).
* **Live Calculation:** Total tarif dihitung secara otomatis saat berat diketik di frontend (`berat * harga_per_kg`) dan divalidasi kembali secara akurat di backend controller.

### B. Tabel Rekap Transaksi
* Menampilkan nomor nota transaksi, tanggal, data pelanggan, layanan, berat, total harga, dan badge status pengerjaan (*Proses* = kuning, *Selesai* = hijau).
* Dilengkapi multi-filter (berdasarkan status pengerjaan, paket layanan, rentang tanggal, dan pencarian nama/no HP).
* Dilengkapi tombol aksi cepat 1-klik untuk memperbarui status pengerjaan dari *Proses* menjadi *Selesai*.
* Dilengkapi tombol cetak nota struk pelanggan (*thermal receipt*) dan cetak rekapitulasi laporan (`window.print()`).

> *[Sisipkan Screenshot Form Order, Rekap Tabel, dan Cetak Nota pada ukuran Desktop, Tablet, dan HP]*

---

## 5. LOG PENGGUNAAN AI (AI PROMPTING LOG) (Untuk Kolom 8 Google Form)

| No. | Tahap Pengembangan | Tujuan / Masalah yang Diselesaikan | Ringkasan Prompt ke AI | Penyesuaian Manual yang Dilakukan Siswa |
| :---: | :--- | :--- | :--- | :--- |
| **1** | **Konfigurasi Database & Migration** | Menyesuaikan koneksi database MySQL `db_laundry` serta membuat skema migrasi tabel users (role admin & kasir), layanans, dan transaksis dengan relasi foreign key cascade. | *"Konfigurasikan database MySQL db_laundry dan buat skema migrasi tabel users (enum role admin/kasir), layanans, dan transaksis dengan foreignId onDelete cascade."* | Memeriksa file `.env` untuk memastikan nama database sesuai dan mengecek tipe data decimal pada kolom berat cucian. |
| **2** | **Model & Relasi Eloquent** | Membangun relasi One-to-Many antara model `Layanan` (hasMany) dan `Transaksi` (belongsTo) serta menambahkan method helper format Rupiah. | *"Buat model Eloquent Layanan dan Transaksi lengkap dengan relasi hasMany dan belongsTo serta helper format mata uang Rupiah."* | Menambahkan helper `getStatusBadgeAttribute` pada model Transaksi agar warna badge status tampil otomatis di view. |
| **3** | **Seeder Data Awal** | Membuat seeder untuk pengguna default (Admin & Kasir) serta 3 layanan wajib (Reguler 7k, Ekspres 12k, Superkilat 25k). | *"Buat Seeder untuk akun pengguna default Admin dan Kasir serta 3 data layanan: Reguler, Ekspres, dan Superkilat, lalu jalankan php artisan migrate:fresh --seed."* | Menambahkan akun alias `admin@gmail.com` dan `kasir@gmail.com` agar fleksibel saat pengujian di sekolah. |
| **4** | **Autentikasi & Hak Akses (Role)** | Membangun sistem login/logout manual dan custom middleware untuk membedakan hak akses Administrator dan Kasir. | *"Buatkan AuthController untuk login/logout dan Middleware untuk membatasi hak akses role admin (akses penuh) dan role kasir (transaksi & read-only layanan)."* | Mendaftarkan alias middleware `'admin'` dan `'role'` pada konfigurasi `bootstrap/app.php`. |
| **5** | **UI/UX Sederhana & Kalkulator Real-time** | Merancang tampilan antarmuka sederhana menggunakan Bootstrap 5 murni (navbar atas, card ringkasan, tabel bergaris, dan form dengan live kalkulasi tarif otomatis). | *"Buat tampilan antarmuka yang sederhana menggunakan Bootstrap 5 murni dengan navbar atas, kartu ringkasan, live hitung total harga otomatis (berat * tarif/kg), dan fitur cetak nota laundry."* | Menyederhanakan desain agar tidak menggunakan CSS berlebihan sehingga terlihat natural seperti proyek siswa kejuruan. |
| **6** | **Pengujian & Registrasi Akun** | Membuat automated feature test PHPUnit untuk memverifikasi logika tarif, hak akses role, serta menambahkan halaman registrasi akun. | *"Tambahkan rute dan view register akun baru dan buat rangkaian Feature Test PHPUnit untuk memverifikasi login, CRUD layanan, dan alur transaksi."* | Menjalankan perintah `php artisan test` (10/10 test passed) dan memformat kode menggunakan Laravel Pint. |
