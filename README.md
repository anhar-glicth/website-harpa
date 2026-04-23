# PROJECT UAS SISTEM DONASI

HumanityCare adalah web donasi kemanusiaan berbasis online yang memungkinkan pengguna melihat campaign, melakukan donasi, serta mengunggah bukti pembayaran. Sistem ini menerapkan Role-Based Access Control (RBAC) dengan peran user, admin, dan field worker, menggunakan arsitektur REST API dan SPA, autentikasi JWT, serta basis data MySQL untuk mendukung pengelolaan campaign dan verifikasi pembayaran secara aman.

---

## 1. Fitur Utama

**Fitur Umum & Keamanan:**
* 🔐 **Autentikasi JWT:** Login dan akses API diamankan menggunakan JSON Web Token.
* 🛡️ **Protected Routes:** Halaman tertentu seperti dashboard dan transaksi hanya dapat diakses oleh pengguna yang telah login.
* 👮 **Role-Based Access Control (RBAC):** Pengaturan hak akses berdasarkan peran User, Admin, dan Field Worker.

**Fitur User:**
* 📋 **Lihat Campaign Donasi:** Mengakses daftar campaign donasi yang sedang berlangsung.
* 💰 **Melakukan Donasi:** Mengirim donasi ke campaign yang dipilih.
* 🧾 **Upload Bukti Pembayaran:** Mengunggah bukti transfer donasi secara online.
* 📊 **Riwayat Donasi:** Melihat histori donasi dan status verifikasi pembayaran.

**Fitur Admin:**
* 📋 **Manajemen Campaign:** Melihat dan mengelola seluruh campaign donasi.
* ✅ **Verifikasi Pembayaran:** Memverifikasi bukti pembayaran donasi dari pengguna.
* 📈 **Monitoring Donasi:** Memantau total dana dan status donasi pada setiap campaign.

**Fitur Field Worker:**
* ✏️ **Kelola Campaign:** Membuat dan memperbarui campaign donasi.
* 📰 **Update Informasi Lapangan:** Menyampaikan perkembangan kondisi atau laporan lapangan terkait campaign.

---

## 2. Instalasi yang Dibutuhkan

Sebelum menjalankan HumanityCare, pastikan perangkat telah terpasang:

1.  **Node.js** dan NPM.
2.  **MySQL Database** (disarankan menggunakan XAMPP atau Laragon).
3.  **Git** (opsional, untuk clone repository).

**Langkah Awal Instalasi:**
1.  Clone atau unduh repository HumanityCare.
2.  Import file database MySQL ke server lokal.
3.  Konfigurasikan file .env pada folder backend dengan menyesuaikan variabel berikut:
* DB_HOST
* DB_USER
* DB_PASS
* DB_NAME
* JWT_SECRET
* PORT

---

## 3. Cara Run Frontend

Frontend dibangun menggunakan **Vue 3** dengan **Vite** dan menerapkan konsep **Single Page Application (SPA)**.

1.  Buka terminal baru, masuk ke folder frontend:
    ```bash
    cd frontend
    ```
2.  Install library yang dibutuhkan :
    ```bash
    npm install
    ```
3.  Jalankan server frontend:
    ```bash
    npm run dev
    ```
4.  Akses aplikasi di browser melalui link yang muncul URL local `http://localhost:3000/`. Disini berada pada `3000` karena sudah di set di file `vite.config.js`.

---

## 4. Cara Run Backend

Backend HumanityCare dibangun menggunakan Node.js dan Express.js dengan arsitektur REST API.

1.  Buka terminal baru, masuk ke folder backend:
    ```bash
    cd backend
    ```
2.  Install library yang dibutuhkan (hanya pertama kali):
    ```bash
    npm install
    ```
3.  Jalankan server backend:
    ```bash
    npm start
    ```
4.  Jika berhasil, terminal akan menampilkan informasi:
    ```bash
    Server berjalan di: `http://localhost:5000`
    Payload Limit: `50MB (Foto Aktif)`
    ```

---

## 5. Akun Default & Role Sistem

Gunakan akun ini untuk menguji fungsionalitas sistem berdasarkan peran masing-masing. Semua akun menggunakan password: **`password123`**

### 🔐 Admin
Akses penuh ke semua fitur manajemen dan CMS.
* **Email:** `admin@humanity.org`
* **Password:** `password123`

### 👷 Field Worker (Petugas Lapangan)
Akses untuk mengelola kampanye dan update berita lapangan.
* **Email:** `petugas@humanity.org`
* **Password:** `password123`

### 👤 User (Donatur)
Akses untuk berdonasi, zakat, dan melihat riwayat donasi.
* **Email:** `donatur@humanity.org`
* **Password:** `password123`

---




## 7. Daftar Endpoint API

Base URL: `http://localhost:5000/api`
Semua endpoint API berada di bawah prefix: `/api`

Untuk endpoint yang diproteksi, sertakan **Header** seperti `Authorization: Bearer <token>`.

### 🔐 Autentikasi
| Method | Endpoint | Deskripsi | Akses |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/auth/register` | Registrasi publik (role otomatis user/donatur) | Public |
| `POST` | `/api/auth/login` | Login & mendapatkan JWT | Public |

### 📢 campaign
| Method | Endpoint | Deskripsi | Akses |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/campaigns` | Melihat seluruh kampanye donasi | public |
| `GET` | `/api/campaigns/:id` | Melihat detail kampanye | public |
| `POST` | `/api/campaigns` | Membuat kampanye baru | 🔐 Admin |
| `PUT` | `/api/campaigns/:id` | Mengubah data kampanye | 🔐 Admin |
| `DELETE` | `/api/campaigns/:id` | Menghapus kampanye | 🔐 Admin |

### 💰 Donasi
| Method | Endpoint | Deskripsi | Akses |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/donations` | Melihat seluruh riwayat donasi | 🔐 Admin |
| `POST` | `/api/donations` | Mengirim donasi ke kampanye | public |

### 📊 Statistik & Dashboard
| Method | Endpoint | Deskripsi | Akses |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/stats` | Statistik total donasi & kampanye | public |
| `GET` | `/api/admin/dashboard-stats` | Statistik dashboard admin | 🔐 Admin |

---

## 8. Penjelasan Uji API

Berikut merupakan dokumentasi pengujian REST API `HumanityCare` menggunakan `Postman` untuk memastikan setiap endpoint backend berjalan dengan baik sesuai prinsip `CRUD (Create, Read, Update, Delete)` serta mekanisme `JWT Authentication`.

**Register User Baru**
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/7ff0c412-9a27-4bc5-9652-d47c128d94dc" />

**Login(dengan token)**
<img width="1920" height="1080" alt="image-1" src="https://github.com/user-attachments/assets/4ce2b9dc-c94c-4462-ad59-10e2437786d5" />

**Read Data**
<img width="1920" height="1080" alt="image2" src="https://github.com/user-attachments/assets/e62f06b3-304c-4e18-9e60-ca2351b7da3b" />

**Update Data**
<img width="1920" height="1080" alt="image-4" src="https://github.com/user-attachments/assets/bcd3c07a-f9c0-4887-8881-4de0809c3377" />

**Protected Route**
<img width="1920" height="1080" alt="image3" src="https://github.com/user-attachments/assets/25119789-0bce-46ff-bf06-76d6e624f071" />

**Delete Data**
<img width="1920" height="1080" alt="image-5" src="https://github.com/user-attachments/assets/cdedec86-0d6f-4744-9d0e-7bba60746dc1" />

## 9. Penjelasan ERD (Entity Relationship Diagram)

Sistem ini menggunakan database MySQL dengan relasi antar tabel sebagai berikut:

### 🗂️ Daftar Tabel

<img width="1001" height="581" alt="Humanitycare-ERD" src="https://github.com/user-attachments/assets/81aa1475-2f7d-4c73-9792-8acb26a841aa" />


ERD HumanityCare terdiri dari tiga entitas utama: users, campaigns, dan donations. Entitas users menyimpan data pengguna, campaigns merepresentasikan program donasi yang dibuat oleh pengguna, dan donations mencatat transaksi donasi ke suatu kampanye. Relasi antar entitas menunjukkan bahwa satu pengguna dapat membuat dan mendonasikan ke banyak kampanye, serta satu kampanye dapat menerima banyak donasi, sehingga proses pengelolaan donasi tercatat secara terstruktur.

---

### 🔗 Hubungan Relasi (Relationships)

1.  **users ➔ donations (One-to-Many)**
    * *Satu User* bisa melakukan *Banyak donasi*.
    * Setiap *donasi* bisa dilakukan oleh *Satu User*.

2.  **users ➔ campaigns (One-to-Many)**
    * *Satu User* bisa membuat *Banyak campaigns*.
    * Setiap *campaigns* bisa dibuat oleh *Satu user*.

3.  **donation ➔ campaigns (OnManye-to-One)**
    * Setiap *donations* bisa untuk *satu campaigns*.
    * *satu campaigns* bisa dari *banyak donasi*.
