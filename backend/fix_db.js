const db = require('./config/db');

async function fix() {
    try {
        console.log('Checking database schema...');

        // 1. Tambah kolom 'status' ke tabel campaigns jika belum ada
        try {
            await db.query(`ALTER TABLE campaigns ADD COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'`);
            console.log('Kolom status berhasil ditambahkan ke campaigns.');
        } catch (e) {
            console.log('Kolom status mungkin sudah ada atau gagal ditambahkan:', e.message);
        }

        // 2. Set semua kampanye lama ke 'approved' agar muncul di halaman depan
        await db.query(`UPDATE campaigns SET status = 'approved'`);
        console.log('Semua kampanye diset ke approved.');

        // 3. Tambah kolom 'payment_status' ke tabel donations jika belum ada
        try {
            await db.query(`ALTER TABLE donations ADD COLUMN payment_status ENUM('Pending', 'Success', 'Failed') DEFAULT 'Pending'`);
            console.log('Kolom payment_status berhasil ditambahkan ke donations.');
        } catch (e) {
            console.log('Kolom payment_status mungkin sudah ada:', e.message);
        }

        // 4. Tambah kolom 'proof_image' ke tabel donations jika belum ada
        try {
            await db.query(`ALTER TABLE donations ADD COLUMN proof_image LONGTEXT`);
            console.log('Kolom proof_image berhasil ditambahkan ke donations.');
        } catch (e) {
            console.log('Kolom proof_image mungkin sudah ada:', e.message);
        }

        console.log('Perbaikan database selesai!');
    } catch (err) {
        console.error('Kesalahan fatal saat memperbaiki DB:', err.message);
    } finally {
        process.exit();
    }
}

fix();
