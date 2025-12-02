# 🔐 GEMBOK LARA - ISP Billing & Management System

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)

**GEMBOK LARA** adalah sistem manajemen tagihan dan operasional ISP (Internet Service Provider) yang dibangun ulang menggunakan **Laravel 12**. Proyek ini merupakan porting modern dari aplikasi Node.js "Gembok Bill", dirancang untuk skalabilitas, keamanan, dan kemudahan penggunaan.

---

## 🚀 Fitur Utama

Aplikasi ini mencakup seluruh siklus bisnis ISP, dari manajemen pelanggan hingga infrastruktur jaringan.

### 👥 Manajemen Pelanggan (CRM)
- **Database Terpusat**: Simpan data lengkap pelanggan, paket langganan, dan riwayat.
- **Status Tracking**: Monitor status pelanggan (Active, Inactive, Suspended).
- **Billing History**: Riwayat tagihan dan pembayaran yang transparan.

### 💰 Sistem Billing & Invoice
- **Auto-Invoicing**: Generate tagihan bulanan secara otomatis.
- **Invoice Management**: Buat, edit, hapus, dan cetak invoice profesional.
- **Payment Tracking**: Pelacakan status pembayaran (Paid/Unpaid) dengan tanggal bayar.
- **Revenue Stats**: Dashboard statistik pendapatan real-time.

### 📦 Manajemen Paket Internet
- **Flexible Pricing**: Buat paket dengan harga, kecepatan, dan deskripsi custom.
- **Tax Configuration**: Dukungan pengaturan PPN otomatis.
- **PPPoE Integration**: Mapping profil paket ke profil PPPoE Mikrotik (Placeholder).

### 🎫 Sistem Voucher (Hotspot)
- **Voucher Generator**: Generate ribuan voucher sekaligus dengan prefix custom.
- **Pricing Tiers**: Harga berbeda untuk Customer vs Reseller/Agen.
- **Sales Tracking**: Laporan penjualan voucher harian/bulanan.
- **Agent Portal**: Portal khusus agen untuk topup saldo dan beli voucher (Coming Soon).

### 🛠️ Manajemen Staff & Operasional
- **Teknisi**: Pelacakan area coverage dan penugasan teknisi.
- **Kolektor**: Manajemen kolektor lapangan dengan sistem komisi.
- **Agen**: Manajemen saldo deposit agen dan riwayat transaksi.

### 🌐 Manajemen Jaringan (ODP)
- **ODP Mapping**: Database Optical Distribution Point dengan koordinat GPS.
- **Capacity Planning**: Visualisasi kapasitas port (Terpakai vs Tersedia).
- **Status Monitoring**: Monitor status ODP (Active, Maintenance, Full).

### ⚙️ Pengaturan Sistem
- **Company Profile**: Kustomisasi nama, alamat, dan logo perusahaan pada invoice.
- **Integrasi Payment**: Konfigurasi Midtrans Gateway (Sandbox/Production).
- **WhatsApp Gateway**: Konfigurasi API untuk notifikasi otomatis.

---

## 🔒 Keamanan (Security)

GEMBOK LARA dibangun dengan standar keamanan Laravel yang ketat:

1.  **Authentication**: Menggunakan sistem autentikasi session-based yang aman dengan hashing password **Bcrypt**.
2.  **CSRF Protection**: Semua form dilindungi dari serangan Cross-Site Request Forgery.
3.  **SQL Injection Protection**: Menggunakan Eloquent ORM yang secara otomatis mem-binding parameter query.
4.  **XSS Protection**: Blade templating engine otomatis meng-escape output untuk mencegah Cross-Site Scripting.
5.  **Role-Based Access Control (RBAC)**: Struktur database siap untuk implementasi multi-role (Admin, Teknisi, Kolektor, Pelanggan).
6.  **Input Validation**: Validasi ketat pada setiap input form menggunakan Form Requests.

---

## 🛠️ Instalasi & Setup

Ikuti langkah ini untuk menjalankan aplikasi di lingkungan lokal Anda.

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM

### Langkah Instalasi

1.  **Clone Repository**
    ```bash
    git clone https://github.com/rizkylab/gembok-lara.git
    cd gembok-lara
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**
    Salin file `.env.example` ke `.env` dan sesuaikan database credentials.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Setup Database**
    Jalankan migrasi dan seeder untuk mengisi data dummy.
    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Build Assets**
    ```bash
    npm run build
    ```

6.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

Akses aplikasi di: `http://localhost:8000`

---

## 🔑 Akun Demo

Gunakan kredensial berikut untuk masuk ke sistem:

| Role | Email | Password |
|------|-------|----------|
| **Administrator** | `admin@gembok.com` | `admin123` |

---

## 📚 Struktur Proyek

- `app/Http/Controllers/Admin`: Logika bisnis utama (Customer, Invoice, dll).
- `app/Models`: Representasi tabel database (Eloquent).
- `resources/views/admin`: Tampilan antarmuka (Blade Templates).
- `routes/web.php`: Definisi routing aplikasi.
- `database/migrations`: Skema database.

---

## 🤝 Cara Kontribusi

Kami sangat menghargai kontribusi Anda untuk pengembangan GEMBOK LARA. Berikut adalah panduan untuk berkontribusi:

1.  **Fork Repository**
    Klik tombol "Fork" di pojok kanan atas halaman repository ini untuk menyalin proyek ke akun GitHub Anda.

2.  **Clone Repository**
    Clone repository yang sudah Anda fork ke komputer lokal Anda:
    ```bash
    git clone https://github.com/rizkylab/gembok-lara.git
    ```

3.  **Buat Branch Baru**
    Selalu buat branch baru untuk setiap fitur atau perbaikan bug yang Anda kerjakan:
    ```bash
    git checkout -b fitur-baru-anda
    ```

4.  **Lakukan Perubahan**
    Lakukan coding sesuai standar Laravel dan pastikan kode Anda bersih.

5.  **Commit Perubahan**
    Gunakan pesan commit yang deskriptif:
    ```bash
    git commit -m "Menambahkan fitur X: deskripsi singkat"
    ```

6.  **Push ke GitHub**
    Push branch Anda ke repository fork Anda:
    ```bash
    git push origin fitur-baru-anda
    ```

7.  **Buat Pull Request (PR)**
    Buka halaman repository asli dan buat Pull Request dari branch Anda. Jelaskan perubahan yang Anda lakukan secara detail.

---

## 💬 Dukungan

Jika Anda mengalami masalah atau memiliki pertanyaan seputar penggunaan GEMBOK LARA, silakan:

*   **Buat Issue**: Laporkan bug atau request fitur melalui tab [Issues](https://github.com/rizkylab/gembok-lara/issues) di GitHub.
*   **Diskusi**: Bergabunglah dalam diskusi komunitas (jika tersedia) atau hubungi pengembang utama.

---

## 📄 License

Proyek ini dilisensikan di bawah **MIT License**. Anda bebas menggunakan, memodifikasi, dan mendistribusikan ulang proyek ini sesuai dengan ketentuan lisensi. Lihat file `LICENSE` untuk detail lebih lanjut.

---

## 🔗 Referensi

Proyek ini dibangun sebagai referensi dan pengembangan modern dari proyek open-source:
**[Gembok Bill](https://github.com/alijayanet/gembok-bill)** oleh Ali Jaya Net.

Kami mengucapkan terima kasih kepada pengembang asli atas inspirasi dan fondasi logika bisnis yang telah dibangun.

---

**GEMBOK LARA** - _Simplifying ISP Management_
