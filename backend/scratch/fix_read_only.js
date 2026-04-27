const db = require('../config/db');

async function fixReadOnly() {
    try {
        console.log('Attempting to fix read-only state via SQL...');
        await db.query('SET GLOBAL read_only = 0');
        await db.query('UNLOCK TABLES');
        await db.query('FLUSH PRIVILEGES');
        console.log('SQL commands executed. Testing write again...');
        
        await db.query('CREATE TABLE IF NOT EXISTS fix_test (id INT)');
        await db.query('DROP TABLE fix_test');
        console.log('SUCCESS: Database is now writable!');
    } catch (error) {
        console.error('FAILED to fix read-only state:', error.message);
    } finally {
        process.exit();
    }
}

fixReadOnly();
