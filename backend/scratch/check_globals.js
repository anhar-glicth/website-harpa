const db = require('../config/db');

async function checkGlobals() {
    try {
        const [[readOnly]] = await db.query('SHOW VARIABLES LIKE "read_only"');
        const [[superReadOnly]] = await db.query('SHOW VARIABLES LIKE "super_read_only"');
        console.log('--- Global Variables ---');
        console.log(`read_only: ${readOnly.Value}`);
        console.log(`super_read_only: ${superReadOnly.Value}`);
    } catch (error) {
        console.error('Error checking globals:', error.message);
    } finally {
        process.exit();
    }
}

checkGlobals();
