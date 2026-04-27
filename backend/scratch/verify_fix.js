const db = require('../config/db');

async function verify() {
    try {
        console.log('--- Database Verification ---');
        const [[row]] = await db.query('SHOW VARIABLES LIKE "innodb_read_only"');
        console.log('innodb_read_only:', row ? row.Value : 'N/A');
        
        if (row && row.Value === 'ON') {
            console.error('CRITICAL: Database is still READ-ONLY.');
            console.error('Please make sure you have restarted MySQL from the XAMPP Control Panel.');
        } else {
            console.log('Testing write access...');
            await db.query('CREATE TABLE IF NOT EXISTS final_test (id INT)');
            await db.query('INSERT INTO final_test VALUES (1)');
            await db.query('DROP TABLE final_test');
            console.log('SUCCESS: Database is now fully WRITABLE!');
        }
    } catch (error) {
        console.error('Error during verification:', error.message);
    } finally {
        process.exit();
    }
}

verify();
