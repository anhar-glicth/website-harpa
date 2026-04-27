# 🕊️ PEDULI SESAMA - Platform Donasi Humanity

![Banner](https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1200&h=400&fit=crop)

**PEDULI SESAMA** adalah platform donasi digital modern yang dirancang untuk menghubungkan kebaikan hati para donatur dengan mereka yang membutuhkan secara transparan, cepat, dan aman.

---

## 👨‍💻 Pengembang
*   **Nama:** Anhar Glicth
*   **Peran:** Fullstack Developer
*   **Dibuat Dengan:** Semangat untuk kemanusiaan dan keahlian teknis tingkat tinggi.

---

## 🛠️ Teknologi yang Digunakan
Platform ini dibangun menggunakan teknologi mutakhir untuk menjamin performa dan keamanan:

*   **Frontend:** [Vue.js 3](https://vuejs.org/) & [Vite](https://vitejs.dev/) - Antarmuka yang responsif dan sangat cepat.
*   **Styling:** [Tailwind CSS](https://tailwindcss.com/) & [Vanilla CSS Custom] - Desain premium dan estetik.
*   **Backend:** [Node.js](https://nodejs.org/) & [Express.js](https://expressjs.com/) - Server yang handal dan skalabel.
*   **Database:** [MySQL](https://www.mysql.com/) - Penyimpanan data yang terstruktur dan aman.
*   **Visualisasi:** [Chart.js](https://www.chartjs.org/) - Grafik analisis data donasi yang interaktif.
*   **Keamanan:** [JWT](https://jwt.io/) & [Bcrypt](https://github.com/kelektiv/node.bcrypt.js) - Enkripsi password dan otentikasi aman.

---

## 🔐 Akses Akun (Demo)
Gunakan akun berikut untuk menguji berbagai peran di dalam sistem:

| Peran | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **🛡️ Admin** | `admin@humanity.org` | `password123` | Manajemen total, Verifikasi, Data Analis |
| **👷 Petugas** | `petugas@humanity.org` | `password123` | Kelola Kampanye, Berita, Absensi, To-Do |
| **👤 Donatur** | `donatur@humanity.org` | `password123` | Berdonasi, Cek Riwayat, Profil |

---

## 🚀 Cara Menjalankan Website

### 1. Persiapan Database
1. Pastikan **XAMPP (MySQL)** sudah dalam status **Running**.
2. Masuk ke folder backend: `cd backend`.
3. Jalankan skrip pengaturan database otomatis:
   ```bash
   node setup_final_database.js
   node seed_final_data.js
   ```

### 2. Jalankan Backend
1. Buka terminal baru di folder `backend`.
2. Instal dependensi (jika belum): `npm install`.
3. Jalankan server:
   ```bash
   npm run dev
   ```
   *Server akan berjalan di: `http://localhost:5000`*

### 3. Jalankan Frontend
1. Buka terminal baru di folder `frontend`.
2. Instal dependensi (jika belum): `npm install`.
3. Jalankan aplikasi:
   ```bash
   npm run dev
   ```
   *Aplikasi akan tersedia di: `http://localhost:3000` (atau port lain yang muncul)*

---

## ✨ Fitur Unggulan
*   📊 **Dashboard Analitik**: Grafik donasi bulanan dan distribusi program yang interaktif.
*   ✅ **Verifikasi Bukti Transfer**: Sistem validasi donasi oleh Admin.
*   📍 **Absensi GPS**: Petugas lapangan dapat melakukan absensi dengan deteksi lokasi otomatis.
*   📝 **To-Do List Terintegrasi**: Manajemen tugas petugas langsung dari dashboard.
*   📰 **Update Dampak (Impact Stories)**: Donatur dapat melihat hasil nyata dari donasi mereka.

---

> "Kebaikan kecil yang Anda lakukan hari ini, adalah harapan besar bagi mereka yang membutuhkan."

Copyright © 2026 **Anhar Glicth** - All Rights Reserved.
