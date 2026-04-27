const db = require('../config/db');
const bcrypt = require('bcryptjs');

async function testLogin() {
    const email = 'admin@humanity.org';
    const plainPassword = 'password123';

    try {
        const [users] = await db.query('SELECT * FROM users WHERE email = ?', [email]);
        if (users.length === 0) {
            console.log('❌ USER TIDAK DITEMUKAN');
            return;
        }

        const user = users[0];
        console.log(`[TEST] User: ${user.email}`);
        console.log(`[TEST] Hash di DB: ${user.password}`);
        
        const isMatch = await bcrypt.compare(plainPassword, user.password);
        console.log(`[TEST] Hasil Komparasi (password123): ${isMatch}`);

        if (isMatch) {
            console.log('✅ BERHASIL! Berarti masalah ada di pengiriman data dari Frontend.');
        } else {
            console.log('❌ GAGAL! Berarti hash di DB tidak cocok dengan password123.');
        }
    } catch (e) {
        console.error('Error:', e.message);
    } finally {
        process.exit();
    }
}

testLogin();
