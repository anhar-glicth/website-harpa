const db = require('../backend/config/db');

async function check() {
    try {
        const [rows] = await db.query('SELECT COUNT(*) as count FROM distributions');
        console.log('Distributions count:', rows[0].count);
        
        const [rows2] = await db.query('SELECT * FROM distributions LIMIT 1');
        console.log('Sample row keys:', Object.keys(rows2[0] || {}));
        console.log('Sample row values:', rows2[0]);
        
        process.exit(0);
    } catch (e) {
        console.error('DATABASE ERROR:', e.message);
        process.exit(1);
    }
}

check();
