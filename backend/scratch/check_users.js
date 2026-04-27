const db = require('../config/db');

async function check() {
    try {
        const [users] = await db.query('SELECT id, name, email, role, password FROM users');
        console.log('--- DAFTAR USER DI DATABASE ---');
        users.forEach(u => {
            console.log(`ID: ${u.id} | Nama: ${u.name} | Email: ${u.email} | Role: ${u.role}`);
            console.log(`Hash Password: ${u.password.substring(0, 20)}...`);
            console.log('-----------------------------------');
        });
        
        if (users.length === 0) {
            console.log('❌ DATABASE KOSONG! Silakan jalankan node backend/seed_final_data.js');
        }
    } catch (e) {
        console.error('❌ GAGAL CEK DB:', e.message);
    } finally {
        process.exit();
    }
}

check();
