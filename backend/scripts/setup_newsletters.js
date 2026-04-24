const db = require('../config/db');

async function setup() {
    try {
        console.log('--- SETTING UP NEWSLETTERS TABLE ---');
        await db.query(`
            CREATE TABLE IF NOT EXISTS newsletters (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL UNIQUE,
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);
        console.log('✅ Newsletters table created successfully.');
        process.exit(0);
    } catch (e) {
        console.error('❌ Error creating newsletters table:', e.message);
        process.exit(1);
    }
}

setup();
