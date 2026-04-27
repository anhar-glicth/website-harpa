const db = require('../config/db');

async function testSetForceRecovery() {
    try {
        console.log('Attempting to set innodb_force_recovery = 0...');
        await db.query('SET GLOBAL innodb_force_recovery = 0');
        console.log('Success.');
    } catch (error) {
        console.error('Error:', error.message);
    } finally {
        process.exit();
    }
}

testSetForceRecovery();
