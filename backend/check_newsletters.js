const db = require('./config/db');

async function check() {
    try {
        const [rows] = await db.query('DESCRIBE newsletters');
        console.log('--- NEWSLETTERS TABLE SCHEMA ---');
        console.table(rows);
        process.exit(0);
    } catch (e) {
        console.error('❌ Error describing table:', e.message);
        process.exit(1);
    }
}

check();
