# 🕊️ AMAL ABADI - Platform Donasi & Zakat

![Banner](https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1200&h=400&fit=crop)

**AMAL ABADI** adalah platform donasi dan zakat digital modern yang dirancang untuk menghubungkan kebaikan hati para donatur dengan mereka yang membutuhkan secara transparan, cepat, dan aman.

---

## 👨‍💻 Pengembang
*   **Nama:** Anhar Glicth
*   **Peran:** Fullstack Developer
*   **Dibuat Dengan:** Semangat untuk kemanusiaan dan keahlian teknis tingkat tinggi.

---

## 🛠️ Teknologi yang Digunakan
Platform ini dibangun menggunakan teknologi mutakhir untuk menjamin performa dan keamanan:

*   **Core Backend & Frontend:** [Laravel 12](https://laravel.com/) (Full-stack Blade & Eloquent ORM)
*   **Styling:** [Tailwind CSS](https://tailwindcss.com/) & [Vanilla CSS Custom] - Desain premium dan estetik.
*   **Database:** [MySQL](https://www.mysql.com/) - Penyimpanan data yang terstruktur dan aman.
*   **Visualisasi:** [Chart.js](https://www.chartjs.org/) - Grafik analisis data donasi yang interaktif.
*   **Interaktivitas:** [Alpine.js](https://alpinejs.dev/) - Efek animasi dan kelonggaran navigasi (Accordion, Dropdown, Modals).

---

## 🔐 Akses Akun (Demo & Pengujian)
Gunakan akun berikut untuk menguji berbagai peran di dalam sistem Amal Abadi:

| Peran | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **🛡️ Admin** | `admin@humanity.org` | `password123` | Manajemen total, Verifikasi Bukti, Data Analis, Monitoring Staff |
| **👷 Petugas** | `petugas@humanity.org` | `password123` | Kelola Kampanye, Berita Dampak, Absensi GPS, To-Do List, CRM Prospek |
| **👤 Donatur** | `donatur@humanity.org` | `password123` | Kalkulator & Bayar Zakat, Donasi Program, Cek Riwayat & Tracking, Papan Doa |

---

## 🚀 Cara Menjalankan Website

### 1. Persiapan Database (XAMPP)
1. Pastikan **XAMPP (MySQL)** sudah dalam status **Running**.
2. Buat database baru bernama `humanity_db` melalui phpMyAdmin.

### 2. Konfigurasi & Migrasi Laravel
1. Buka terminal lalu masuk ke folder `laravel`:
   ```bash
   cd laravel
   ```
2. Jalankan migrasi tabel database dan masukkan data demo awal (seeding):
   ```bash
   php artisan migrate:fresh --seed
   ```

### 3. Jalankan Server Lokal
1. Jalankan perintah server development Laravel:
   ```bash
   php artisan serve
   ```
2. Buka browser dan akses aplikasi melalui alamat:
   **[http://localhost:8000](http://localhost:8000)**

---

## ✨ Fitur Unggulan
*   📊 **Dashboard Analitik**: Grafik donasi bulanan dan distribusi program yang interaktif.
*   📡 **Monitoring Staff Terpadu**: Admin dapat melacak kehadiran GPS petugas, delegasi tugas lapangan, dan memantau persentase efektivitas kerja staff secara real-time.
*   📍 **Absensi GPS Petugas**: Petugas lapangan dapat melakukan absensi check-in/out dengan deteksi koordinat otomatis (HTML5 Geolocation API).
*   📋 **CRM Prospek Donatur**: Pelacakan interaksi calon donatur baru lengkap dengan log respon follow-up.
*   🤲 **Papan Doa Bersama**: Donatur dapat saling mengirimkan doa dan mengaminkan doa donatur lainnya secara real-time.

---

> "Kebaikan kecil yang Anda lakukan hari ini, adalah harapan besar bagi mereka yang membutuhkan."

Copyright © 2026 **Amal Abadi & Anhar Glicth** - All Rights Reserved.
