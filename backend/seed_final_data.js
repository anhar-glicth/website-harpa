const db = require('./config/db');
const bcrypt = require('bcryptjs');

async function seed() {
    console.log('--- MEMULAI PENGISIAN DATA AWAL SESUAI README ---');
    
    try {
        // Password standar sesuai README: password123
        const commonPassword = await bcrypt.hash('password123', 10);

        await db.query('SET FOREIGN_KEY_CHECKS = 0');
        const tables = ['users', 'campaigns', 'donations', 'todo_list', 'zakats', 'volunteers', 'news', 'settings', 'prayers'];
        for (const table of tables) {
            await db.query(`TRUNCATE TABLE ${table}`);
        }
        await db.query('SET FOREIGN_KEY_CHECKS = 1');

        // 1. Buat Akun Sesuai README
        const [adminRes] = await db.query(
            'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)',
            ['Admin Humanity', 'admin@humanity.org', commonPassword, 'admin']
        );
        const adminId = adminRes.insertId;

        const [staffRes] = await db.query(
            'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)',
            ['Petugas Lapangan', 'petugas@humanity.org', commonPassword, 'field_worker']
        );
        const staffId = staffRes.insertId;

        const [donorRes] = await db.query(
            'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)',
            ['Andi Donatur', 'donatur@humanity.org', commonPassword, 'user']
        );
        const donorId = donorRes.insertId;

        // 2. Buat Data Contoh Pendukung
        const [campRes] = await db.query(
            'INSERT INTO campaigns (author_id, title, description, category, target_amount, status) VALUES (?, ?, ?, ?, ?, ?)',
            [adminId, 'Wakaf Al-Quran Pelosok', 'Distribusi mushaf Al-Quran ke pesantren di wilayah terpencil.', 'Dakwah', 25000000, 'approved']
        );
        const campaignId = campRes.insertId;

        await db.query(
            'INSERT INTO donations (campaign_id, user_id, donor_name, amount, payment_status, tracking_status) VALUES (?, ?, ?, ?, ?, ?)',
            [campaignId, donorId, 'Andi Donatur', 500000, 'Success', 'Logistik']
        );

        await db.query('UPDATE campaigns SET current_amount = 500000 WHERE id = ?', [campaignId]);

        await db.query(
            'INSERT INTO news (title, tag, content) VALUES (?, ?, ?)',
            ['Laporan Distribusi Tahap 1', 'Laporan', 'Alhamdulillah, 500 mushaf telah sampai di NTT.']
        );

        console.log('✔ Akun & Data Contoh berhasil diselaraskan dengan README.');
        console.log('\n--- KREDENSIAL LOGIN ---');
        console.log('ADMIN   : admin@humanity.org / password123');
        console.log('PETUGAS : petugas@humanity.org / password123');
        console.log('DONATUR : donatur@humanity.org / password123');

    } catch (error) {
        console.error('❌ GAGAL SEEDING:', error.message);
    } finally {
        process.exit();
    }
}

seed();
