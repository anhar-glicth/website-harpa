const db = require('../config/db');

async function checkGrants() {
    try {
        const [rows] = await db.query('SHOW GRANTS FOR CURRENT_USER');
        console.log('--- Grants ---');
        rows.forEach(r => console.log(Object.values(r)[0]));
    } catch (error) {
        console.error('Error checking grants:', error.message);
    } finally {
        process.exit();
    }
}

checkGrants();
