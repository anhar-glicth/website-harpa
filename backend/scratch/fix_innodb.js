const db = require('../config/db');

async function fixInnoDB() {
    try {
        console.log('Attempting to set innodb_read_only = OFF...');
        await db.query('SET GLOBAL innodb_read_only = 0');
        console.log('Successfully set innodb_read_only = OFF.');
    } catch (error) {
        console.error('FAILED to set innodb_read_only:', error.message);
        console.log('This variable might be read-only at runtime. You may need to check my.ini or restart MySQL.');
    } finally {
        process.exit();
    }
}

fixInnoDB();
