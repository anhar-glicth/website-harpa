const db = require('./config/db');
const bcrypt = require('bcryptjs');

async function seed() {
    const password = await bcrypt.hash('password123', 10);
    
    const users = [
        ['Admin Utama', 'admin@humanity.org', password, 'admin'],
        ['Petugas Lapangan', 'petugas@humanity.org', password, 'field_worker'],
        ['Donatur Setia', 'donatur@humanity.org', password, 'user']
    ];

    for (const user of users) {
        try {
            await db.query('INSERT IGNORE INTO users (name, email, password, role) VALUES (?, ?, ?, ?)', user);
            console.log(`User created: ${user[1]}`);
        } catch (e) {
            console.error(`Error creating ${user[1]}:`, e.message);
        }
    }
    process.exit();
}

seed();
