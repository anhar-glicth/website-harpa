# DESIGN.md: Harapan Palestina (Yayasan Harpa Indonesia Madani)

Dokumen ini menjadi sumber arah visual resmi untuk proyek `website-harpa` (`harapanpalestina.com`) sesuai aturan **R-37** dari `antislop`. Seluruh keputusan desain diekstrak dari identitas lembaga Yayasan Harpa Indonesia Madani yang telah ada pada codebase.

## 1. Identity & Brand Voice

- **Nama Brand:** Yayasan Harpa Indonesia Madani (`harapanpalestina.com`)
- **Peran Produk:** Platform crowdfunding kemanusiaan publik serta sistem operasional internal CRM, penyaluran, dan analisis data donatur (Sistem Data Harpa).
- **Legalitas Resmi:** Kementerian Hukum dan HAM RI, SK No. AHU-0017523.AH.01.04.Tahun 2025.
- **Karakter & Nada Bahasa (Copy Voice):** Hangat, amanah, jernih, informatif, dan lugas. Seluruh angka dampak dan riwayat donasi wajib berasal dari data aktual database, bukan angka perkiraan atau dummy.

## 2. Liveliness Dials (Default)

- **Halaman Publik (`home`, `campaigns`, `news`, `prayers`, `about`, `contact`, `zakat`, `auth`):**
  `Dial: ENERGY 2 / RHYTHM 2 / MOTION 1`
  - *Alasan:* Penggalangan dana publik membutuhkan kehangatan visual dan keterbacaan tegas (ENERGY 2), variasi komposisi antar bagian agar laporan peta, kampanye, dan doa terasa hidup (RHYTHM 2), serta gerakan terbatas pada hover/transisi fungsional tanpa animasi berdenyut terus-menerus (MOTION 1).
- **Dashboard Admin, Petugas Lapangan & Sistem Data Harpa:**
  `Dial: ENERGY 1 / RHYTHM 1 / MOTION 1`
  - *Alasan:* Layar operasional CRM, verifikasi transaksi, dan daftar kirim mengutamakan ketenangan visual, scan cepat, kepadatan tabel yang rapi, dan kejelasan tindakan utama.

## 3. Color Palette (Maks. 2 Core + 1 Accent + Neutrals, R-29 & R-25)

1. **Core 1 (Primary): Deep Navy `#063f5c`** (`primary-600`, `brand.navy`)
   - Turunan gelap untuk tombol hover dan penegasan latar: `#04324a` (`primary-700`), `#021a28` (`primary-900`).
   - Tint permukaan aktif ringan: `#edf5f9` (`primary-50`) dengan teks `#04324a` (rasio kontras `12.20:1`, PASS WCAG AA).
   - *Alasan:* Memberi kesan lembaga yang berwibawa, tenang, dan terpercaya.
2. **Core 2 (Neutral Canvas): Crisp Light Mode**
   - Latar halaman: `#F8FAFC`, permukaan kartu: `#FFFFFF`, garis pembatas kartu/tabel: `#E2E8F0` (`slate-200`).
   - Teks utama: `#1E293B` (`slate-800`) dan `#0F172A` (`slate-900`).
   - Teks sekunder minimum di atas latar terang (`#FFFFFF` / `#F8FAFC`): `#64748B` (`slate-500`, rasio `4.76:1` PASS) atau `#475569` (`slate-600`). Dilarang memakai `slate-400` (`#94a3b8`, `2.56:1` FAIL) untuk teks informasional.
   - Teks sekunder minimum di atas Navy (`#063f5c`): `#CBD5E1` (`slate-300`, `7.55:1` PASS).
3. **Accent (Single Deliberate Accent): Warm Gold `#f7ad1a`** (`secondary-500`, `brand.gold`)
   - *Aturan Kontras (R-25):* Di atas latar `#f7ad1a`, selalu gunakan teks Navy `#063f5c` (`5.84:1`, PASS). Untuk teks putih di atas tombol bernuansa emas, wajib memakai `#93580d` (`5.77:1`, PASS), bukan `#b97307` (`3.81:1`, FAIL).
   - Dipakai hemat pada CTA utama hero, aksen garis judul bagian, ikon sorotan di latar gelap Navy, dan indikator pencapaian.
4. **Semantic Status Colors (Khusus Status Data)**
   - **Sukses / Aktif / Read:** Hijau tegas `#047857` (atau latar `#ECFDF5` dengan teks `#065F46`) dan tidak boleh ditimpa alias kuning/emas pada konfigurasi Tailwind.
   - **Peringatan / Pending:** Amber `#92400E` di atas `#FFFBEB`.
   - **Gagal / Nomor Mati:** Rose `#BE123C` di atas `#FFF1F2`.

## 4. Typography (R-06)

- **Display & Headings:** `Montserrat` (weights 700, 900)
  - *Alasan:* Proporsi geometris lebar memberi penekanan kuat pada judul program kemanusiaan dan angka nominal donasi agar langsung terbaca.
- **Body & UI Controls:** `Plus Jakarta Sans` (weights 400, 500, 600, 700)
  - *Alasan:* Keterbacaan bersih untuk paragraf cerita penyaluran, label formulir, dan tabel CRM padat.
- **Aturan Kapitalisasi & Tracking:** Gunakan sentence case untuk paragraf dan pesan error; hindari label sangat kecil dengan `tracking-widest` pada semua elemen sekaligus agar ritme baca tetap nyaman.

## 5. Radii, Elevation & Identity Motif (R-11, R-12, Part 3)

- **Hierarki Radius (bukan serba pill):**
  - Panel/Kartu besar: `rounded-2xl` (16px) atau `rounded-3xl` (24px).
  - Tombol aksi, dropdown, input form: `rounded-xl` (12px).
  - Lencana status & tag kategori: `rounded-md` / `rounded-lg` (6px–8px).
- **Elevasi (Shadow):** Sebagian besar kartu data duduk rata dengan border `#E2E8F0` + `shadow-sm`; bayangan lebih tinggi (`shadow-xl`) hanya untuk modal, bar sticky aksi massal, dan hover kartu kampanye.
- **Glassmorphism (R-10):** Dibatasi maksimal 1-2 elemen transparan (seperti navbar utama yang menempel saat scroll). Kartu, modal, tombol, dan toast tidak perlu menggunakan `backdrop-blur` bersamaan.
- **Identity Motif:** Garis aksen horizontal Gold `#f7ad1a` selebar `48px` (`w-12 h-1.5 rounded-full`) tepat di bawah heading utama, berpadu dengan blok Navy `#063f5c`.

## 6. Accessibility, Motion & Mobile Rules (R-03, R-32, R-39, R-40)

- Setiap kontrol interaktif (`button`, `a[href]`, `select`, `summary`, `label[for]`) wajib menggunakan elemen semantik native (bukan `<div @click>`) dan memiliki `cursor: pointer`, target sentuh minimal `44x44px` pada mobile, serta cincin `:focus-visible` dengan kontras `>= 3:1`.
- Setiap modal wajib mendukung penutupan lewat tombol `Escape` dan tombol tutup bertipe icon wajib memiliki `aria-label`.
- Setiap `<input>`, `<select>`, dan `<textarea>` wajib terhubung lewat `<label for="...">`.
- Gambar hero HTML utama harus memiliki dimensi eksplisit (`width`/`height` atau `aspect-ratio`), tanpa `loading="lazy"`, dan menggunakan `fetchpriority="high"`. Gerakan otomatis seperti slider atau animasi harus memiliki kontrol jeda dan mematuhi `@media (prefers-reduced-motion: reduce)`.

